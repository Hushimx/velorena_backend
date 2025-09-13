@extends('admin.layouts.app')

@section('pageTitle', __('admin.support_ticket_details'))
@section('title', __('admin.support_ticket_details'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.support_ticket_details') }}</h1>
            <p class="text-gray-600">{{ __('admin.ticket_number') }}: {{ $supportTicket->ticket_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.support-tickets.edit', $supportTicket) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                <span>{{ __('admin.edit_ticket') }}</span>
            </a>
            <a href="{{ route('admin.support-tickets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('admin.back') }}</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Ticket Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.ticket_information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">{{ __('admin.status') }}</label>
                            <div>
                                <span class="badge badge-{{ $supportTicket->status === 'open' ? 'success' : ($supportTicket->status === 'closed' ? 'secondary' : ($supportTicket->status === 'resolved' ? 'info' : 'primary')) }}">
                                    {{ ucfirst($supportTicket->status) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.priority') }}</label>
                            <div>
                                <span class="badge badge-{{ $supportTicket->priority === 'urgent' ? 'danger' : ($supportTicket->priority === 'high' ? 'warning' : ($supportTicket->priority === 'medium' ? 'info' : 'secondary')) }}">
                                    {{ ucfirst($supportTicket->priority) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.category') }}</label>
                            <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $supportTicket->category)) }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.created_at') }}</label>
                            <p class="text-gray-900">{{ $supportTicket->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="form-label">{{ __('admin.subject') }}</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $supportTicket->subject }}</p>
                    </div>
                    
                    <div class="mt-4">
                        <label class="form-label">{{ __('admin.description') }}</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $supportTicket->description }}</p>
                        </div>
                    </div>

                    @if($supportTicket->attachments && count($supportTicket->attachments) > 0)
                        <div class="mt-4">
                            <label class="form-label">{{ __('admin.attachments') }}</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($supportTicket->attachments as $attachment)
                                    <div class="border rounded-lg p-3">
                                        <a href="{{ Storage::url($attachment) }}" target="_blank" class="flex items-center space-x-2 text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-paperclip"></i>
                                            <span class="truncate">{{ basename($attachment) }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Replies -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.conversation') }}</h3>
                </div>
                <div class="card-body">
                    @forelse($supportTicket->replies as $reply)
                        <div class="border-l-4 {{ $reply->author_type === 'admin' ? 'border-blue-500' : 'border-gray-300' }} pl-4 mb-6">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-900">{{ $reply->author_name }}</span>
                                    @if($reply->is_internal)
                                        <span class="badge badge-secondary">{{ __('admin.internal') }}</span>
                                    @endif
                                    <span class="badge badge-{{ $reply->author_type === 'admin' ? 'primary' : 'secondary' }}">
                                        {{ $reply->author_type === 'admin' ? __('admin.admin') : __('admin.customer') }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg mb-2">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $reply->message }}</p>
                            </div>
                            @if($reply->attachments && count($reply->attachments) > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($reply->attachments as $attachment)
                                        <a href="{{ Storage::url($attachment) }}" target="_blank" class="inline-flex items-center space-x-1 text-sm text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-paperclip"></i>
                                            <span>{{ basename($attachment) }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-comments text-4xl mb-4 text-gray-400"></i>
                            <p class="text-gray-500">{{ __('admin.no_replies_yet') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.customer_information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('admin.customer_name') }}</label>
                            <p class="text-gray-900">{{ $supportTicket->user->full_name ?? $supportTicket->user->company_name ?? $supportTicket->user->email }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.email') }}</label>
                            <p class="text-gray-900">{{ $supportTicket->user->email }}</p>
                        </div>
                        @if($supportTicket->user->phone)
                            <div>
                                <label class="form-label">{{ __('admin.phone') }}</label>
                                <p class="text-gray-900">{{ $supportTicket->user->phone }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="form-label">{{ __('admin.registration_date') }}</label>
                            <p class="text-gray-900">{{ $supportTicket->user->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.assignment') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.support-tickets.assign', $supportTicket) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="assigned_to" class="form-label">{{ __('admin.assign_to') }}</label>
                                <select name="assigned_to" id="assigned_to" class="form-control">
                                    <option value="">{{ __('admin.unassigned') }}</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ $supportTicket->assigned_to == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-full">
                                <i class="fas fa-user-check"></i>
                                <span>{{ __('admin.assign_ticket') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add Reply -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.add_reply') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.support-tickets.add-reply', $supportTicket) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="message" class="form-label">{{ __('admin.message') }}</label>
                                <textarea name="message" id="message" rows="4" 
                                          class="form-control @error('message') border-red-500 @enderror" 
                                          placeholder="{{ __('admin.enter_reply_message') }}" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="is_internal" value="1" class="form-check-input" id="is_internal">
                                <label class="form-check-label" for="is_internal">{{ __('admin.internal_note') }}</label>
                            </div>
                            <button type="submit" class="btn btn-success w-full">
                                <i class="fas fa-reply"></i>
                                <span>{{ __('admin.send_reply') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Admin Notes -->
            @if($supportTicket->admin_notes)
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.admin_notes') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $supportTicket->admin_notes }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection