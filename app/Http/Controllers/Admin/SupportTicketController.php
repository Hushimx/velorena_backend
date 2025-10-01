<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedAdmin', 'replies']);

        // Filtering
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('assigned_to')) {
            $query->byAssignedAdmin($request->assigned_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('full_name', 'like', "%{$search}%")
                               ->orWhere('company_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->latest()->paginate(20);
        $admins = Admin::all();

        return view('admin.dashboard.support-tickets.index', compact('tickets', 'admins'));
    }

    public function show(SupportTicket $supportTicket)
    {
        $supportTicket->load(['user', 'assignedAdmin', 'replies.user', 'replies.admin']);
        $admins = Admin::all();

        return view('admin.dashboard.support-tickets.show', compact('supportTicket', 'admins'));
    }

    public function create()
    {
        $admins = Admin::all();
        $users = \App\Models\User::select('id', 'full_name', 'company_name', 'email')->get();

        return view('admin.dashboard.support-tickets.create', compact('admins', 'users'));
    }

    public function store(Request $request)
    {
        $validationRules = SupportTicket::getAdminValidationRules();
        $validationRules['user_id'] = 'required|exists:users,id';
        
        $request->validate($validationRules);

        $supportTicket = SupportTicket::create([
            'user_id' => $request->user_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.support-tickets.show', $supportTicket)
            ->with('success', __('admin.support_ticket_created_success'));
    }

    public function edit(SupportTicket $supportTicket)
    {
        $supportTicket->load(['user', 'assignedAdmin']);
        $admins = Admin::all();

        return view('admin.dashboard.support-tickets.edit', compact('supportTicket', 'admins'));
    }

    public function update(Request $request, SupportTicket $supportTicket)
    {
        $request->validate(SupportTicket::getAdminValidationRules());

        $supportTicket->update([
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
            'admin_notes' => $request->admin_notes,
        ]);

        // Update resolved/closed timestamps
        if (in_array($request->status, ['resolved', 'closed'])) {
            if ($request->status === 'resolved' && !$supportTicket->resolved_at) {
                $supportTicket->update(['resolved_at' => now()]);
            }
            if ($request->status === 'closed' && !$supportTicket->closed_at) {
                $supportTicket->update(['closed_at' => now()]);
            }
        }

        return redirect()->route('admin.support-tickets.show', $supportTicket)
            ->with('success', __('admin.support_ticket_updated_success'));
    }

    public function destroy(SupportTicket $supportTicket)
    {
        $supportTicket->delete();

        return redirect()->route('admin.support-tickets.index')
            ->with('success', __('admin.support_ticket_deleted_success'));
    }

    public function assign(Request $request, SupportTicket $supportTicket)
    {
        $request->validate([
            'assigned_to' => 'required|exists:admins,id',
        ]);

        $supportTicket->assignToAdmin($request->assigned_to);

        return redirect()->back()
            ->with('success', __('admin.support_ticket_assigned_success'));
    }

    public function addReply(Request $request, SupportTicket $supportTicket)
    {
        $request->validate(SupportTicketReply::getAdminValidationRules());

        $reply = $supportTicket->replies()->create([
            'admin_id' => Auth::guard('admin')->id(),
            'message' => $request->message,
            'is_internal' => $request->boolean('is_internal'),
        ]);

        // Handle file attachments if any
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support-tickets/attachments', 'public');
                $attachments[] = $path;
            }
            $reply->update(['attachments' => $attachments]);
        }

        // Update ticket status to in_progress if it was open
        if ($supportTicket->status === 'open') {
            $supportTicket->update(['status' => 'in_progress']);
        }

        return redirect()->back()
            ->with('success', __('admin.reply_added_success'));
    }

    public function updateReply(Request $request, SupportTicketReply $reply)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'is_internal' => 'boolean',
        ]);

        $reply->update([
            'message' => $request->message,
            'is_internal' => $request->boolean('is_internal'),
        ]);

        return redirect()->back()
            ->with('success', __('admin.reply_updated_success'));
    }

    public function deleteReply(SupportTicketReply $reply)
    {
        $reply->delete();

        return redirect()->back()
            ->with('success', __('admin.reply_deleted_success'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:assign,change_status,change_priority,delete',
            'ticket_ids' => 'required|array|min:1',
            'ticket_ids.*' => 'exists:support_tickets,id',
        ]);

        $tickets = SupportTicket::whereIn('id', $request->ticket_ids);

        switch ($request->action) {
            case 'assign':
                $request->validate(['assigned_to' => 'required|exists:admins,id']);
                $tickets->update(['assigned_to' => $request->assigned_to, 'status' => 'in_progress']);
                $message = __('admin.tickets_assigned_success');
                break;

            case 'change_status':
                $request->validate(['status' => 'required|in:open,in_progress,pending,resolved,closed']);
                $tickets->update(['status' => $request->status]);
                $message = __('admin.tickets_status_updated_success');
                break;

            case 'change_priority':
                $request->validate(['priority' => 'required|in:low,medium,high,urgent']);
                $tickets->update(['priority' => $request->priority]);
                $message = __('admin.tickets_priority_updated_success');
                break;

            case 'delete':
                $tickets->delete();
                $message = __('admin.tickets_deleted_success');
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    public function statistics()
    {
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::open()->count(),
            'closed' => SupportTicket::closed()->count(),
            'by_priority' => SupportTicket::selectRaw('priority, count(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority'),
            'by_category' => SupportTicket::selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category'),
            'by_status' => SupportTicket::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
            'assigned_to_me' => SupportTicket::byAssignedAdmin(Auth::guard('admin')->id())->open()->count(),
        ];

        return view('admin.dashboard.support-tickets.statistics', compact('stats'));
    }
}

