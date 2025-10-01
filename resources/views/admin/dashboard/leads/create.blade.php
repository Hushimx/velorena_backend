@extends('admin.layouts.app')

@section('pageTitle', __('admin.create_lead'))
@section('title', __('admin.create_lead'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.create_lead') }}</h1>
                <p class="text-gray-600">{{ __('admin.add_new_lead_to_platform') }}</p>
            </div>
            <a href="{{ route('admin.leads.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-arrow-right pl-2"></i>
                <span>{{ __('admin.back_to_list') }}</span>
            </a>
        </div>

        <!-- Create Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('admin.leads.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Name -->
                    <div>
                        <label for="company_name"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.company_name') }}</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_name') border-red-500 @enderror"
                            required>
                        @error('company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Person -->
                    <div>
                        <label for="contact_person"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.contact_person') }}</label>
                        <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('contact_person') border-red-500 @enderror"
                            required>
                        @error('contact_person')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.email') }}</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.phone') }}</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.address') }}</label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.status') }}</label>
                        <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                            required>
                            <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>{{ __('admin.new') }}
                            </option>
                            <option value="contacted" {{ old('status') == 'contacted' ? 'selected' : '' }}>
                                {{ __('admin.contacted') }}</option>
                            <option value="qualified" {{ old('status') == 'qualified' ? 'selected' : '' }}>
                                {{ __('admin.qualified') }}</option>
                            <option value="proposal_sent" {{ old('status') == 'proposal_sent' ? 'selected' : '' }}>
                                {{ __('admin.proposal_sent') }}</option>
                            <option value="negotiation" {{ old('status') == 'negotiation' ? 'selected' : '' }}>
                                {{ __('admin.negotiation') }}</option>
                            <option value="closed_won" {{ old('status') == 'closed_won' ? 'selected' : '' }}>
                                {{ __('admin.closed_won') }}</option>
                            <option value="closed_lost" {{ old('status') == 'closed_lost' ? 'selected' : '' }}>
                                {{ __('admin.closed_lost') }}</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.priority') }}</label>
                        <select id="priority" name="priority"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror"
                            required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ __('admin.low') }}
                            </option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>
                                {{ __('admin.medium') }}</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                {{ __('admin.high') }}</option>
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Marketer -->
                    <div>
                        <label for="marketer_id"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.responsible_marketer') }}</label>
                        <select id="marketer_id" name="marketer_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('marketer_id') border-red-500 @enderror">
                            <option value="">{{ __('admin.select_marketer') }}</option>
                            @foreach ($marketers as $marketer)
                                <option value="{{ $marketer->id }}"
                                    {{ old('marketer_id') == $marketer->id ? 'selected' : '' }}>
                                    {{ $marketer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('marketer_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.category') }}</label>
                        <select id="category_id" name="category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                            <option value="">{{ __('admin.select_category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Next Follow Up -->
                    <div>
                        <label for="next_follow_up"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.next_follow_up') }}</label>
                        <input type="datetime-local" id="next_follow_up" name="next_follow_up"
                            value="{{ old('next_follow_up') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('next_follow_up') border-red-500 @enderror">
                        @error('next_follow_up')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.notes') }}</label>
                        <textarea id="notes" name="notes" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4 mt-6">
                    <a href="{{ route('admin.leads.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition-colors">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        {{ __('admin.add_lead') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
