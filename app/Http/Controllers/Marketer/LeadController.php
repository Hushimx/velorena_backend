<?php

namespace App\Http\Controllers\Marketer;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadCommunication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:marketer');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $canRequestNew = $this->canRequestNewLeads();
        return view('marketer.leads.index', compact('canRequestNew'));
    }

    /**
     * Display urgent leads that need immediate attention.
     */
    public function urgent()
    {
        $marketerId = Auth::guard('marketer')->id();
        
        // Get leads that need urgent attention
        $urgentLeads = Lead::where('marketer_id', $marketerId)
            ->where(function ($query) {
                $query->where('status', 'new') // New leads that haven't been contacted
                    ->orWhere(function ($subQuery) {
                        // Follow-up due leads
                        $subQuery->whereNotNull('next_follow_up')
                            ->where('next_follow_up', '<=', now())
                            ->whereNotIn('status', ['closed_won', 'closed_lost']);
                    })
                    ->orWhere(function ($subQuery) {
                        // Didn't respond leads that need attention
                        $subQuery->where('status', 'didnt_respond')
                            ->whereNull('next_follow_up');
                    });
            })
            ->orderByRaw("
                CASE 
                    WHEN status = 'new' THEN 1
                    WHEN status = 'didnt_respond' AND next_follow_up IS NULL THEN 2
                    WHEN next_follow_up <= NOW() THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('next_follow_up', 'asc')
            ->get();

        return view('marketer.leads.urgent', compact('urgentLeads'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        // Check if the lead is assigned to the current marketer
        if ($lead->marketer_id !== Auth::guard('marketer')->id()) {
            abort(403, 'غير مصرح لك بالوصول إلى هذا الـ lead');
        }

        $lead->load(['communications.marketer']);
        return view('marketer.leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        // Check if the lead is assigned to the current marketer
        if ($lead->marketer_id !== Auth::guard('marketer')->id()) {
            abort(403, 'غير مصرح لك بتعديل هذا الـ lead');
        }

        return view('marketer.leads.edit', compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        // Check if the lead is assigned to the current marketer
        if ($lead->marketer_id !== Auth::guard('marketer')->id()) {
            abort(403, 'غير مصرح لك بتعديل هذا الـ lead');
        }

        $request->validate([
            'status' => 'required|in:new,contacted,qualified,proposal_sent,negotiation,closed_won,closed_lost',
            'priority' => 'required|in:low,medium,high',
            'notes' => 'nullable|string',
            'next_follow_up' => 'nullable|date',
        ]);

        $lead->update($request->all());

        return redirect()->route('marketer.leads.index')
            ->with('success', 'تم تحديث الـ lead بنجاح');
    }

    /**
     * Add communication to lead
     */
    public function addCommunication(Request $request, Lead $lead)
    {
        // Check if the lead is assigned to the current marketer
        if ($lead->marketer_id !== Auth::guard('marketer')->id()) {
            return redirect()->route('marketer.leads.index')
                ->with('error', __('marketer.unauthorized_lead_access'));
        }

        $request->validate([
            'type' => 'required|in:call,email,meeting,whatsapp,other',
            'notes' => 'required|string',
            'communication_date' => 'required|date',
            'status' => 'nullable|in:contacted,didnt_respond,qualified,proposal_sent,negotiation,closed_won,closed_lost',
            'next_follow_up' => 'nullable|date|after:communication_date',
        ]);

        LeadCommunication::create([
            'lead_id' => $lead->id,
            'marketer_id' => Auth::guard('marketer')->id(),
            'type' => $request->type,
            'notes' => $request->notes,
            'communication_date' => $request->communication_date,
        ]);

        // Update lead's last contact date and optionally status and next follow-up
        $updateData = ['last_contact_date' => $request->communication_date];
        
        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }
        
        if ($request->filled('next_follow_up')) {
            $updateData['next_follow_up'] = $request->next_follow_up;
        }
        
        $lead->update($updateData);

        return redirect()->route('marketer.leads.show', $lead)
            ->with('success', __('marketer.communication_added_successfully'));
    }

    /**
     * Check if marketer has completed all leads and can request new ones
     */
    public function canRequestNewLeads()
    {
        $marketerId = Auth::guard('marketer')->id();
        
        // Check if marketer has any leads that are still "new" (not contacted yet)
        // Only "new" status prevents requesting new leads
        $newLeads = Lead::where('marketer_id', $marketerId)
            ->where('status', 'new')
            ->count();
            
        return $newLeads === 0;
    }

    /**
     * Request new leads assignment
     */
    public function requestNewLeads()
    {
        if (!$this->canRequestNewLeads()) {
            return redirect()->route('marketer.leads.index')
                ->with('error', __('marketer.must_contact_new_leads_first'));
        }

        $marketer = Auth::guard('marketer')->user();
        $maxLeads = 10;
        $assignedLeads = collect();
        
        // Priority 1: Get follow-up due leads first (most urgent)
        $followUpLeads = Lead::where('category_id', $marketer->category_id)
            ->whereNull('marketer_id')
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    // Follow-up date is due
                    $subQuery->whereNotNull('next_follow_up')
                        ->where('next_follow_up', '<=', now());
                })->orWhere(function ($subQuery) {
                    // Didn't respond leads (no follow-up date but status is didnt_respond)
                    $subQuery->where('status', 'didnt_respond')
                        ->whereNull('next_follow_up');
                });
            })
            ->whereNotIn('status', ['closed_won', 'closed_lost'])
            ->orderBy('next_follow_up', 'asc') // Most overdue first
            ->limit($maxLeads)
            ->get();

        $assignedLeads = $assignedLeads->merge($followUpLeads);
        $remainingSlots = $maxLeads - $assignedLeads->count();

        // Priority 2: If we have slots left, get new leads
        if ($remainingSlots > 0) {
            $newLeads = Lead::where('category_id', $marketer->category_id)
                ->whereNull('marketer_id')
                ->where('status', 'new')
                ->orderBy('created_at', 'asc') // Oldest first
                ->limit($remainingSlots)
                ->get();

            $assignedLeads = $assignedLeads->merge($newLeads);
            $remainingSlots = $maxLeads - $assignedLeads->count();
        }

        // Priority 3: If still have slots, get any other unassigned leads (not closed)
        if ($remainingSlots > 0) {
            $otherLeads = Lead::where('category_id', $marketer->category_id)
                ->whereNull('marketer_id')
                ->whereNotIn('status', ['closed_won', 'closed_lost', 'new'])
                ->where(function ($query) {
                    $query->whereNull('next_follow_up')
                        ->orWhere('next_follow_up', '>', now());
                })
                ->orderBy('created_at', 'asc')
                ->limit($remainingSlots)
                ->get();

            $assignedLeads = $assignedLeads->merge($otherLeads);
        }

        if ($assignedLeads->isEmpty()) {
            return redirect()->route('marketer.leads.index')
                ->with('info', __('marketer.no_new_leads_available'));
        }

        // Assign leads to marketer
        foreach ($assignedLeads as $lead) {
            $lead->update(['marketer_id' => $marketer->id]);
        }

        // Prepare success message with breakdown
        $followUpCount = $followUpLeads->count();
        $newCount = $assignedLeads->where('status', 'new')->count();
        $otherCount = $assignedLeads->count() - $followUpCount - $newCount;

        $message = __('marketer.leads_requested_successfully', ['count' => $assignedLeads->count()]);
        
        if ($followUpCount > 0) {
            $message .= ' ' . __('marketer.follow_up_leads_assigned', ['count' => $followUpCount]);
        }
        if ($newCount > 0) {
            $message .= ' ' . __('marketer.new_leads_assigned', ['count' => $newCount]);
        }
        if ($otherCount > 0) {
            $message .= ' ' . __('marketer.other_leads_assigned', ['count' => $otherCount]);
        }

        return redirect()->route('marketer.leads.index')
            ->with('success', $message);
    }

    /**
     * Get marketer dashboard data
     */
    public function dashboard()
    {
        $marketerId = Auth::guard('marketer')->id();
        
        $stats = [
            'total_leads' => Lead::where('marketer_id', $marketerId)->count(),
            'active_leads' => Lead::where('marketer_id', $marketerId)
                ->whereNotIn('status', ['closed_won', 'closed_lost'])
                ->count(),
            'completed_leads' => Lead::where('marketer_id', $marketerId)
                ->whereIn('status', ['closed_won', 'closed_lost'])
                ->count(),
            'can_request_new' => $this->canRequestNewLeads()
        ];

        return view('marketer.dashboard.main', compact('stats'));
    }

    /**
     * Create a user account from lead data
     */
    public function createUserFromLead(Request $request, Lead $lead)
    {
        // Check if the lead is assigned to the current marketer
        if ($lead->marketer_id !== Auth::guard('marketer')->id()) {
            abort(403, 'غير مصرح لك بإنشاء حساب لهذا الـ lead');
        }

        // Check if user already exists with this email
        $existingUser = User::where('email', $lead->email)->first();
        if ($existingUser) {
            return redirect()->route('marketer.leads.show', $lead)
                ->with('error', __('marketer.email_already_exists'));
        }

        $request->validate([
            'client_type' => 'required|in:individual,company',
            'password' => 'required|string|min:8|confirmed',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'vat_number' => 'nullable|string|max:50',
            'cr_number' => 'nullable|string|max:50',
        ]);

        try {
            // Prepare user data from lead
            $userData = [
                'client_type' => $request->client_type,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'address' => $lead->address,
                'city' => $request->city,
                'country' => $request->country,
                'vat_number' => $request->vat_number,
                'cr_number' => $request->cr_number,
                'password' => Hash::make($request->password),
                'notes' => 'تم إنشاء الحساب من lead بواسطة المسوق: ' . Auth::guard('marketer')->user()->name,
            ];

            // Set name fields based on client type
            if ($request->client_type === 'individual') {
                $userData['full_name'] = $lead->contact_person;
                $userData['company_name'] = $lead->company_name;
            } else {
                $userData['company_name'] = $lead->company_name;
                $userData['contact_person'] = $lead->contact_person;
            }

            // Create user
            $user = User::create($userData);

            // Link user to lead
            $lead->update(['user_id' => $user->id]);

            // Add communication record
            LeadCommunication::create([
                'lead_id' => $lead->id,
                'marketer_id' => Auth::guard('marketer')->id(),
                'type' => 'other',
                'notes' => __('marketer.user_creation_communication', ['email' => $user->email]),
                'communication_date' => now(),
            ]);

            return redirect()->route('marketer.leads.show', $lead)
                ->with('success', __('marketer.user_created_successfully'));

        } catch (\Exception $e) {
            return redirect()->route('marketer.leads.show', $lead)
                ->with('error', __('marketer.user_creation_error') . ': ' . $e->getMessage());
        }
    }
}
