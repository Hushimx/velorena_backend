@extends('admin.layouts.app')

@section('pageTitle', __('admin.edit_support_ticket'))
@section('title', __('admin.edit_support_ticket'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.edit_support_ticket') }}</h1>
            <p class="text-gray-600">{{ __('admin.ticket_number') }}: {{ $supportTicket->ticket_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.support-tickets.show', $supportTicket) }}" class="btn btn-secondary">
                <i class="fas fa-eye"></i>
                <span>{{ __('admin.view_ticket') }}</span>
            </a>
            <a href="{{ route('admin.support-tickets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('admin.back') }}</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.edit_ticket_details') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.support-tickets.update', $supportTicket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Subject -->
                            <div>
                                <label for="subject" class="form-label">{{ __('admin.subject') }}</label>
                                <input type="text" name="subject" id="subject" 
                                       value="{{ old('subject', $supportTicket->subject) }}"
                                       class="form-control @error('subject') border-red-500 @enderror" required>
                                @error('subject')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="form-label">{{ __('admin.description') }}</label>
                                <textarea name="description" id="description" rows="6" 
                                          class="form-control @error('description') border-red-500 @enderror" 
                                          placeholder="{{ __('admin.enter_ticket_description') }}" required>{{ old('description', $supportTicket->description) }}</textarea>
                                @error('description')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status and Priority -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="status" class="form-label">{{ __('admin.status') }}</label>
                                    <select name="status" id="status" class="form-control @error('status') border-red-500 @enderror" required>
                                        <option value="open" {{ old('status', $supportTicket->status) === 'open' ? 'selected' : '' }}>
                                            {{ __('admin.open') }}
                                        </option>
                                        <option value="in_progress" {{ old('status', $supportTicket->status) === 'in_progress' ? 'selected' : '' }}>
                                            {{ __('admin.in_progress') }}
                                        </option>
                                        <option value="pending" {{ old('status', $supportTicket->status) === 'pending' ? 'selected' : '' }}>
                                            {{ __('admin.pending') }}
                                        </option>
                                        <option value="resolved" {{ old('status', $supportTicket->status) === 'resolved' ? 'selected' : '' }}>
                                            {{ __('admin.resolved') }}
                                        </option>
                                        <option value="closed" {{ old('status', $supportTicket->status) === 'closed' ? 'selected' : '' }}>
                                            {{ __('admin.closed') }}
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="priority" class="form-label">{{ __('admin.priority') }}</label>
                                    <select name="priority" id="priority" class="form-control @error('priority') border-red-500 @enderror" required>
                                        <option value="low" {{ old('priority', $supportTicket->priority) === 'low' ? 'selected' : '' }}>
                                            {{ __('admin.low') }}
                                        </option>
                                        <option value="medium" {{ old('priority', $supportTicket->priority) === 'medium' ? 'selected' : '' }}>
                                            {{ __('admin.medium') }}
                                        </option>
                                        <option value="high" {{ old('priority', $supportTicket->priority) === 'high' ? 'selected' : '' }}>
                                            {{ __('admin.high') }}
                                        </option>
                                        <option value="urgent" {{ old('priority', $supportTicket->priority) === 'urgent' ? 'selected' : '' }}>
                                            {{ __('admin.urgent') }}
                                        </option>
                                    </select>
                                    @error('priority')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category and Assignment -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="category" class="form-label">{{ __('admin.category') }}</label>
                                    <select name="category" id="category" class="form-control @error('category') border-red-500 @enderror" required>
                                        <option value="general" {{ old('category', $supportTicket->category) === 'general' ? 'selected' : '' }}>
                                            {{ __('admin.general') }}
                                        </option>
                                        <option value="technical" {{ old('category', $supportTicket->category) === 'technical' ? 'selected' : '' }}>
                                            {{ __('admin.technical') }}
                                        </option>
                                        <option value="billing" {{ old('category', $supportTicket->category) === 'billing' ? 'selected' : '' }}>
                                            {{ __('admin.billing') }}
                                        </option>
                                        <option value="feature_request" {{ old('category', $supportTicket->category) === 'feature_request' ? 'selected' : '' }}>
                                            {{ __('admin.feature_request') }}
                                        </option>
                                        <option value="bug_report" {{ old('category', $supportTicket->category) === 'bug_report' ? 'selected' : '' }}>
                                            {{ __('admin.bug_report') }}
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="assigned_to" class="form-label">{{ __('admin.assigned_to') }}</label>
                                    <select name="assigned_to" id="assigned_to" class="form-control @error('assigned_to') border-red-500 @enderror">
                                        <option value="">{{ __('admin.unassigned') }}</option>
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ old('assigned_to', $supportTicket->assigned_to) == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Admin Notes -->
                            <div>
                                <label for="admin_notes" class="form-label">{{ __('admin.admin_notes') }}</label>
                                <textarea name="admin_notes" id="admin_notes" rows="4" 
                                          class="form-control @error('admin_notes') border-red-500 @enderror" 
                                          placeholder="{{ __('admin.enter_admin_notes') }}">{{ old('admin_notes', $supportTicket->admin_notes) }}</textarea>
                                <small class="form-text text-muted">{{ __('admin.admin_notes_help') }}</small>
                                @error('admin_notes')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('admin.support-tickets.show', $supportTicket) }}" class="btn btn-secondary">
                                    {{ __('admin.cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    <span>{{ __('admin.save_changes') }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ticket Information -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.ticket_info') }}</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('admin.customer') }}</label>
                            <p class="text-gray-900">{{ $supportTicket->user->full_name ?? $supportTicket->user->company_name ?? $supportTicket->user->email }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.email') }}</label>
                            <p class="text-gray-900">{{ $supportTicket->user->email }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.created_at') }}</label>
                            <p class="text-gray-900">{{ $supportTicket->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.last_updated') }}</label>
                            <p class="text-gray-900">{{ $supportTicket->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                        @if($supportTicket->resolved_at)
                            <div>
                                <label class="form-label">{{ __('admin.resolved_at') }}</label>
                                <p class="text-gray-900">{{ $supportTicket->resolved_at->format('Y-m-d H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status History -->
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.status_history') }}</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        @if($supportTicket->created_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('admin.created') }}</p>
                                    <p class="text-xs text-gray-500">{{ $supportTicket->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($supportTicket->updated_at && $supportTicket->updated_at != $supportTicket->created_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('admin.updated') }}</p>
                                    <p class="text-xs text-gray-500">{{ $supportTicket->updated_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($supportTicket->resolved_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('admin.resolved') }}</p>
                                    <p class="text-xs text-gray-500">{{ $supportTicket->resolved_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($supportTicket->closed_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('admin.closed') }}</p>
                                    <p class="text-xs text-gray-500">{{ $supportTicket->closed_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection