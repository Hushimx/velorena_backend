@extends('admin.layouts.app')

@section('pageTitle', __('admin.support_tickets_management'))
@section('title', __('admin.support_tickets_management'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.support_tickets_management') }}</h1>
            <p class="text-gray-600">{{ __('admin.manage_support_tickets_platform') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.support-tickets.index', ['status' => 'open']) }}" 
               class="btn {{ request()->get('status') === 'open' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-folder-open"></i>
                <span>{{ __('admin.open_tickets') }}</span>
            </a>
            <a href="{{ route('admin.support-tickets.index', ['status' => 'in_progress']) }}" 
               class="btn {{ request()->get('status') === 'in_progress' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-cog"></i>
                <span>{{ __('admin.in_progress_tickets') }}</span>
            </a>
            <a href="{{ route('admin.support-tickets.index') }}" 
               class="btn {{ !request()->get('status') ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-list"></i>
                <span>{{ __('admin.all_tickets') }}</span>
            </a>
            <a href="{{ route('admin.support-tickets.statistics') }}" 
               class="btn btn-info">
                <i class="fas fa-chart-bar"></i>
                <span>{{ __('admin.ticket_statistics') }}</span>
            </a>
        </div>
    </div>

    <!-- Support Tickets Table -->
    <div class="card">
        <div class="card-body">
            <!-- Filters -->
            <div class="mb-4 flex flex-wrap gap-4">
                <div class="flex-1 min-w-48">
                    <select class="form-control" onchange="filterByStatus(this.value)">
                        <option value="">{{ __('admin.all_statuses') }}</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>{{ __('admin.open') }}</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('admin.in_progress') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('admin.pending') }}</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>{{ __('admin.resolved') }}</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>{{ __('admin.closed') }}</option>
                    </select>
                </div>
                <div class="flex-1 min-w-48">
                    <select class="form-control" onchange="filterByPriority(this.value)">
                        <option value="">{{ __('admin.all_priorities') }}</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>{{ __('admin.urgent') }}</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>{{ __('admin.high') }}</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>{{ __('admin.medium') }}</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>{{ __('admin.low') }}</option>
                    </select>
                </div>
                <div class="flex-1 min-w-48">
                    <select class="form-control" onchange="filterByCategory(this.value)">
                        <option value="">{{ __('admin.all_categories') }}</option>
                        <option value="technical" {{ request('category') === 'technical' ? 'selected' : '' }}>{{ __('admin.technical') }}</option>
                        <option value="billing" {{ request('category') === 'billing' ? 'selected' : '' }}>{{ __('admin.billing') }}</option>
                        <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>{{ __('admin.general') }}</option>
                        <option value="feature_request" {{ request('category') === 'feature_request' ? 'selected' : '' }}>{{ __('admin.feature_request') }}</option>
                        <option value="bug_report" {{ request('category') === 'bug_report' ? 'selected' : '' }}>{{ __('admin.bug_report') }}</option>
                    </select>
                </div>
                <div class="flex-1 min-w-48">
                    <input type="text" class="form-control" placeholder="{{ __('admin.search_tickets') }}" 
                           value="{{ request('search') }}" onkeyup="searchTickets(this.value)">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.ticket_number') }}</th>
                            <th>{{ __('admin.customer') }}</th>
                            <th>{{ __('admin.subject') }}</th>
                            <th>{{ __('admin.priority') }}</th>
                            <th>{{ __('admin.status') }}</th>
                            <th>{{ __('admin.category') }}</th>
                            <th>{{ __('admin.assigned_to') }}</th>
                            <th>{{ __('admin.created_at') }}</th>
                            <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold" 
                                             style="background-color: var(--brand-brown);">
                                            {{ substr($ticket->ticket_number, -3) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-medium text-gray-900">{{ $ticket->ticket_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $ticket->user->full_name ?? $ticket->user->company_name ?? $ticket->user->email }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $ticket->user->email }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="max-w-xs">
                                        <div class="font-medium text-gray-900 truncate" title="{{ $ticket->subject }}">
                                            {{ $ticket->subject }}
                                        </div>
                                        <div class="text-sm text-gray-500 truncate" title="{{ $ticket->description }}">
                                            {{ Str::limit($ticket->description, 50) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : ($ticket->priority === 'medium' ? 'info' : 'secondary')) }}">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $ticket->status === 'open' ? 'success' : ($ticket->status === 'closed' ? 'secondary' : ($ticket->status === 'resolved' ? 'info' : 'primary')) }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $ticket->category === 'technical' ? 'danger' : ($ticket->category === 'billing' ? 'warning' : 'info') }}">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($ticket->assignedAdmin)
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $ticket->assignedAdmin->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $ticket->assignedAdmin->email }}</div>
                                        </div>
                                    @else
                                        <span class="text-gray-500">{{ __('admin.unassigned') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $ticket->created_at->format('Y-m-d') }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $ticket->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.support-tickets.show', $ticket) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors" title="{{ __('admin.view_ticket') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.support-tickets.edit', $ticket) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 transition-colors" title="{{ __('admin.edit_ticket') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($ticket->status !== 'closed')
                                            <form action="{{ route('admin.support-tickets.destroy', $ticket) }}" 
                                                  method="POST" class="inline" 
                                                  onsubmit="return confirm('{{ __('admin.confirm_delete_ticket') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="{{ __('admin.delete_ticket') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-8">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-ticket-alt text-4xl mb-4" style="color: var(--brand-yellow);"></i>
                                        <p class="text-gray-500">{{ __('admin.no_tickets_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tickets->hasPages())
                <div class="pagination">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterByStatus(status) {
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

function filterByPriority(priority) {
    const url = new URL(window.location);
    if (priority) {
        url.searchParams.set('priority', priority);
    } else {
        url.searchParams.delete('priority');
    }
    window.location.href = url.toString();
}

function filterByCategory(category) {
    const url = new URL(window.location);
    if (category) {
        url.searchParams.set('category', category);
    } else {
        url.searchParams.delete('category');
    }
    window.location.href = url.toString();
}

function searchTickets(search) {
    if (search.length >= 2 || search.length === 0) {
        const url = new URL(window.location);
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        window.location.href = url.toString();
    }
}
</script>
@endsection