@extends('admin.layouts.app')

@section('pageTitle', __('admin.create_support_ticket'))
@section('title', __('admin.create_support_ticket'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.create_support_ticket') }}</h1>
                <p class="text-gray-600">{{ __('admin.create_new_ticket_description') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.support-tickets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('admin.back') }}</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Create Form -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold">
                            {{ __('admin.create_ticket_details') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.support-tickets.store') }}" method="POST">
                            @csrf

                            <div class="space-y-6">
                                <!-- Customer Selection -->
                                <div>
                                    <label for="user_id" class="form-label">{{ __('admin.customer') }}</label>
                                    <select name="user_id" id="user_id"
                                        class="form-control @error('user_id') border-red-500 @enderror" required>
                                        <option value="">{{ __('admin.select_customer') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->full_name ?? ($user->company_name ?? $user->email) }}
                                                @if ($user->company_name && $user->full_name)
                                                    ({{ $user->company_name }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Subject -->
                                <div>
                                    <label for="subject" class="form-label">{{ __('admin.subject') }}</label>
                                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
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
                                        placeholder="{{ __('admin.enter_ticket_description') }}" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status and Priority -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="status" class="form-label">{{ __('admin.status') }}</label>
                                        <select name="status" id="status"
                                            class="form-control @error('status') border-red-500 @enderror" required>
                                            <option value="open"
                                                {{ old('status', 'open') === 'open' ? 'selected' : '' }}>
                                                {{ __('admin.open') }}
                                            </option>
                                            <option value="in_progress"
                                                {{ old('status') === 'in_progress' ? 'selected' : '' }}>
                                                {{ __('admin.in_progress') }}
                                            </option>
                                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>
                                                {{ __('admin.pending') }}
                                            </option>
                                            <option value="resolved" {{ old('status') === 'resolved' ? 'selected' : '' }}>
                                                {{ __('admin.resolved') }}
                                            </option>
                                            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>
                                                {{ __('admin.closed') }}
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="priority" class="form-label">{{ __('admin.priority') }}</label>
                                        <select name="priority" id="priority"
                                            class="form-control @error('priority') border-red-500 @enderror" required>
                                            <option value="low"
                                                {{ old('priority', 'medium') === 'low' ? 'selected' : '' }}>
                                                {{ __('admin.low') }}
                                            </option>
                                            <option value="medium"
                                                {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>
                                                {{ __('admin.medium') }}
                                            </option>
                                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>
                                                {{ __('admin.high') }}
                                            </option>
                                            <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>
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
                                        <select name="category" id="category"
                                            class="form-control @error('category') border-red-500 @enderror" required>
                                            <option value="general"
                                                {{ old('category', 'general') === 'general' ? 'selected' : '' }}>
                                                {{ __('admin.general') }}
                                            </option>
                                            <option value="technical"
                                                {{ old('category') === 'technical' ? 'selected' : '' }}>
                                                {{ __('admin.technical') }}
                                            </option>
                                            <option value="billing" {{ old('category') === 'billing' ? 'selected' : '' }}>
                                                {{ __('admin.billing') }}
                                            </option>
                                            <option value="feature_request"
                                                {{ old('category') === 'feature_request' ? 'selected' : '' }}>
                                                {{ __('admin.feature_request') }}
                                            </option>
                                            <option value="bug_report"
                                                {{ old('category') === 'bug_report' ? 'selected' : '' }}>
                                                {{ __('admin.bug_report') }}
                                            </option>
                                        </select>
                                        @error('category')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="assigned_to" class="form-label">{{ __('admin.assigned_to') }}</label>
                                        <select name="assigned_to" id="assigned_to"
                                            class="form-control @error('assigned_to') border-red-500 @enderror">
                                            <option value="">{{ __('admin.unassigned') }}</option>
                                            @foreach ($admins as $admin)
                                                <option value="{{ $admin->id }}"
                                                    {{ old('assigned_to') == $admin->id ? 'selected' : '' }}>
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
                                        placeholder="{{ __('admin.enter_admin_notes') }}">{{ old('admin_notes') }}</textarea>
                                    <small class="form-text text-muted">{{ __('admin.admin_notes_help') }}</small>
                                    @error('admin_notes')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.support-tickets.index') }}" class="btn btn-secondary">
                                        {{ __('admin.cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                        <span>{{ __('admin.create_ticket') }}</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help Information -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold">{{ __('admin.help_information') }}
                        </h3>
                    </div>
                    <div class="card-body" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ __('admin.ticket_creation_help') }}</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ __('admin.ticket_creation_help_description') }}
                                </p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ __('admin.priority_guidelines') }}</h4>
                                <ul class="text-sm text-gray-600 mt-1 gap-3">
                                    <li><strong>{{ __('admin.urgent') }}:</strong> {{ __('admin.urgent_description') }}
                                    </li>
                                    <li><strong>{{ __('admin.high') }}:</strong> {{ __('admin.high_description') }}</li>
                                    <li><strong>{{ __('admin.medium') }}:</strong> {{ __('admin.medium_description') }}
                                    </li>
                                    <li><strong>{{ __('admin.low') }}:</strong> {{ __('admin.low_description') }}</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ __('admin.category_guidelines') }}</h4>
                                <ul class="text-sm text-gray-600 mt-1 gap-3">
                                    <li><strong>{{ __('admin.technical') }}:</strong>
                                        {{ __('admin.technical_description') }}</li>
                                    <li><strong>{{ __('admin.billing') }}:</strong> {{ __('admin.billing_description') }}
                                    </li>
                                    <li><strong>{{ __('admin.general') }}:</strong> {{ __('admin.general_description') }}
                                    </li>
                                    <li><strong>{{ __('admin.feature_request') }}:</strong>
                                        {{ __('admin.feature_request_description') }}</li>
                                    <li><strong>{{ __('admin.bug_report') }}:</strong>
                                        {{ __('admin.bug_report_description') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
