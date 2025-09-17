@extends('marketer.layouts.app')

@section('title', __('marketer.lead_details'))

@section('content')
<div class="space-y-6">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-white border-l-4 border-green-500 shadow-lg rounded-lg p-4 mb-4 animate-slide-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-green-400 hover:text-green-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-white border-l-4 border-red-500 shadow-lg rounded-lg p-4 mb-4 animate-slide-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation text-red-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-red-400 hover:text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('marketer.lead_details') }}</h1>
                <p class="mt-1" style="color: #ffde9f;">{{ $lead->company_name }}</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('marketer.leads.edit', $lead) }}"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    <span class="hidden sm:block">{{ __('marketer.edit') }}</span>
                </a>
                <a href="{{ route('marketer.leads.index') }}"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-arrow-right"></i>
                    <span class="hidden sm:block">{{ __('marketer.back_to_list') }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Lead Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('marketer.basic_information') }}</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('marketer.company_name') }}</label>
                            <p class="text-gray-900">{{ $lead->company_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('marketer.contact_person') }}</label>
                            <p class="text-gray-900">{{ $lead->contact_person }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('marketer.email') }}</label>
                            <p class="text-gray-900">{{ $lead->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('marketer.phone') }}</label>
                            <p class="text-gray-900">{{ $lead->phone ?? __('marketer.not_specified') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('marketer.address') }}</label>
                            <p class="text-gray-900">{{ $lead->address ?? __('marketer.not_specified') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lead Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('marketer.lead_information') }}</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('marketer.status') }}</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($lead->status == 'new') bg-gray-100 text-gray-800
                                @elseif($lead->status == 'contacted') bg-blue-100 text-blue-800
                                @elseif($lead->status == 'didnt_respond') bg-red-100 text-red-800
                                @elseif($lead->status == 'qualified') bg-yellow-100 text-yellow-800
                                @elseif($lead->status == 'proposal_sent') bg-purple-100 text-purple-800
                                @elseif($lead->status == 'negotiation') bg-orange-100 text-orange-800
                                @elseif($lead->status == 'closed_won') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ __('marketer.' . $lead->status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('marketer.priority') }}</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($lead->priority == 'high') bg-red-100 text-red-800
                                @elseif($lead->priority == 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ __('marketer.' . $lead->priority) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('marketer.last_contact') }}</label>
                            <p class="text-gray-900">{{ $lead->last_contact_date ? $lead->last_contact_date->format('Y-m-d H:i') : __('marketer.no_contact_yet') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('marketer.next_follow_up') }}</label>
                            <p class="text-gray-900">{{ $lead->next_follow_up ? $lead->next_follow_up->format('Y-m-d H:i') : __('marketer.not_specified') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($lead->notes)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('marketer.notes') }}</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-900 whitespace-pre-line">{{ $lead->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Add Communication Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('marketer.add_new_communication') }}</h3>
            
            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('marketer.leads.communication', $lead) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.communication_type') }}</label>
                        <select id="type" name="type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('marketer.select_communication_type') }}</option>
                            <option value="call">{{ __('marketer.call') }}</option>
                            <option value="email">{{ __('marketer.email') }}</option>
                            <option value="meeting">{{ __('marketer.meeting') }}</option>
                            <option value="whatsapp">{{ __('marketer.whatsapp') }}</option>
                            <option value="other">{{ __('marketer.other') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="communication_date" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.communication_date') }}</label>
                        <input type="datetime-local" id="communication_date" name="communication_date" 
                            value="{{ now()->format('Y-m-d\TH:i') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.update_lead_status') }}</label>
                        <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('marketer.select_status') }}</option>
                            <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>{{ __('marketer.contacted') }}</option>
                            <option value="didnt_respond" {{ $lead->status == 'didnt_respond' ? 'selected' : '' }}>{{ __('marketer.didnt_respond') }}</option>
                            <option value="qualified" {{ $lead->status == 'qualified' ? 'selected' : '' }}>{{ __('marketer.qualified') }}</option>
                            <option value="proposal_sent" {{ $lead->status == 'proposal_sent' ? 'selected' : '' }}>{{ __('marketer.proposal_sent') }}</option>
                            <option value="negotiation" {{ $lead->status == 'negotiation' ? 'selected' : '' }}>{{ __('marketer.negotiation') }}</option>
                            <option value="closed_won" {{ $lead->status == 'closed_won' ? 'selected' : '' }}>{{ __('marketer.closed_won') }}</option>
                            <option value="closed_lost" {{ $lead->status == 'closed_lost' ? 'selected' : '' }}>{{ __('marketer.closed_lost') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="next_follow_up" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.next_follow_up') }}</label>
                        <input type="datetime-local" id="next_follow_up" name="next_follow_up" 
                            value="{{ $lead->next_follow_up ? $lead->next_follow_up->format('Y-m-d\TH:i') : '' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.communication_notes') }}</label>
                        <textarea id="notes" name="notes" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('marketer.communication_notes_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ __('marketer.add_communication') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Add User to Platform Section -->
        @if(!$lead->user_id)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('marketer.add_user_to_platform') }}</h3>
                    <p class="text-gray-600 text-sm mt-1">{{ __('marketer.create_user_account_for_lead') }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
            </div>
            
            <form action="{{ route('marketer.leads.create-user', $lead) }}" method="POST" id="createUserForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_type" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.client_type') }}</label>
                        <select id="client_type" name="client_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('marketer.select_client_type') }}</option>
                            <option value="individual">{{ __('marketer.individual') }}</option>
                            <option value="company">{{ __('marketer.company') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.password') }}</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('marketer.password_placeholder') }}">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.password_confirmation') }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('marketer.password_confirmation_placeholder') }}">
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.city') }}</label>
                        <input type="text" id="city" name="city"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('marketer.city') }}">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.country') }}</label>
                        <input type="text" id="country" name="country"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('marketer.country') }}">
                    </div>
                    <div>
                        <label for="vat_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.vat_number') }}</label>
                        <input type="text" id="vat_number" name="vat_number"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('marketer.vat_number') }}">
                    </div>
                    <div>
                        <label for="cr_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('marketer.cr_number') }}</label>
                        <input type="text" id="cr_number" name="cr_number"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('marketer.cr_number') }}">
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        {{ __('marketer.create_user_account') }}
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('marketer.user_account_exists') }}</h3>
                    <p class="text-gray-600 text-sm mt-1">{{ __('marketer.user_account_already_created') }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-green-100 text-green-600">
                    <i class="fas fa-check text-xl"></i>
                </div>
            </div>
        </div>
        @endif

        <!-- Communications History -->
        @if($lead->communications->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('marketer.communication_history') }}</h3>
            <div class="space-y-4">
                @foreach($lead->communications as $communication)
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-medium text-gray-900">
                                {{ __('marketer.' . $communication->type) }} - {{ $communication->marketer->name }}
                            </h4>
                            <p class="text-sm text-gray-600">{{ $communication->communication_date->format('Y-m-d H:i') }}</p>
                            <p class="text-gray-900 mt-2">{{ $communication->notes }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection
