<?php

namespace App\Http\Controllers\Marketer;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadCommunication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('marketer.leads.index');
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
            abort(403, 'غير مصرح لك بإضافة تواصل لهذا الـ lead');
        }

        $request->validate([
            'type' => 'required|in:call,email,meeting,whatsapp,other',
            'notes' => 'required|string',
            'communication_date' => 'required|date',
        ]);

        LeadCommunication::create([
            'lead_id' => $lead->id,
            'marketer_id' => Auth::guard('marketer')->id(),
            'type' => $request->type,
            'notes' => $request->notes,
            'communication_date' => $request->communication_date,
        ]);

        // Update lead's last contact date
        $lead->update(['last_contact_date' => $request->communication_date]);

        return redirect()->route('marketer.leads.show', $lead)
            ->with('success', 'تم إضافة التواصل بنجاح');
    }

    /**
     * Check if marketer has completed all leads and can request new ones
     */
    public function canRequestNewLeads()
    {
        $marketerId = Auth::guard('marketer')->id();
        
        // Check if marketer has any leads that are not closed
        $activeLeads = Lead::where('marketer_id', $marketerId)
            ->whereNotIn('status', ['closed_won', 'closed_lost'])
            ->count();
            
        return $activeLeads === 0;
    }

    /**
     * Request new leads assignment
     */
    public function requestNewLeads()
    {
        if (!$this->canRequestNewLeads()) {
            return redirect()->route('marketer.leads.index')
                ->with('error', 'يجب إنهاء جميع الـ leads الحالية قبل طلب leads جديدة');
        }

        $marketer = Auth::guard('marketer')->user();
        
        // Find unassigned leads in the same category as the marketer
        $unassignedLeads = Lead::whereNull('marketer_id')
            ->where('category_id', $marketer->category_id)
            ->where('status', 'new')
            ->limit(5) // Assign 5 leads at a time
            ->get();

        if ($unassignedLeads->isEmpty()) {
            return redirect()->route('marketer.leads.index')
                ->with('info', 'لا توجد leads جديدة متاحة في فئتك حالياً');
        }

        // Assign leads to marketer
        foreach ($unassignedLeads as $lead) {
            $lead->update(['marketer_id' => $marketer->id]);
        }

        return redirect()->route('marketer.leads.index')
            ->with('success', 'تم تعيين ' . $unassignedLeads->count() . ' leads جديدة لك');
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
}
