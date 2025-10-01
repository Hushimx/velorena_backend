@extends('admin.layouts.app')

@section('pageTitle', __('admin.lead_details'))
@section('title', __('admin.lead_details'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.lead_details') }}</h1>
                <p class="text-gray-600">{{ $lead->company_name }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.leads.edit', $lead) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors gap-2">
                    <i class="fas fa-edit"></i>
                    <span>{{ __('admin.edit') }}</span>
                </a>
                <a href="{{ route('admin.leads.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-arrow-right pl-2"></i>
                    <span>{{ __('admin.back_to_list') }}</span>
                </a>
            </div>
        </div>

        <!-- Lead Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.basic_information') }}</h3>
                    <div class="space-y-3">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.company_name') }}</label>
                            <p class="text-gray-900">{{ $lead->company_name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.contact_person') }}</label>
                            <p class="text-gray-900">{{ $lead->contact_person }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.email') }}</label>
                            <p class="text-gray-900">{{ $lead->email }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.phone') }}</label>
                            <p class="text-gray-900">{{ $lead->phone ?? __('admin.not_specified') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.address') }}</label>
                            <p class="text-gray-900">{{ $lead->address ?? __('admin.not_specified') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lead Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.lead_information') }}</h3>
                    <div class="space-y-3">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.status') }}</label>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($lead->status == 'new') bg-gray-100 text-gray-800
                                @elseif($lead->status == 'contacted') bg-blue-100 text-blue-800
                                @elseif($lead->status == 'qualified') bg-yellow-100 text-yellow-800
                                @elseif($lead->status == 'proposal_sent') bg-purple-100 text-purple-800
                                @elseif($lead->status == 'negotiation') bg-orange-100 text-orange-800
                                @elseif($lead->status == 'closed_won') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ __('admin.' . $lead->status) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.priority') }}</label>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($lead->priority == 'high') bg-red-100 text-red-800
                                @elseif($lead->priority == 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ __('admin.' . $lead->priority) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <label
                                class="block text-sm font-medium text-gray-700">{{ __('admin.responsible_marketer') }}</label>
                            <p class="text-gray-900">{{ $lead->marketer->name ?? __('admin.not_assigned') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.last_contact') }}</label>
                            <p class="text-gray-900">
                                {{ $lead->last_contact_date ? $lead->last_contact_date->format('Y-m-d H:i') : __('admin.not_contacted_yet') }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.next_follow_up') }}</label>
                            <p class="text-gray-900">
                                {{ $lead->next_follow_up ? $lead->next_follow_up->format('Y-m-d H:i') : __('admin.not_specified') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if ($lead->notes)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.notes') }}</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-900 whitespace-pre-line">{{ $lead->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Communications History -->
        @if ($lead->communications->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.communications_history') }}</h3>
                <div class="space-y-4">
                    @foreach ($lead->communications as $communication)
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium text-gray-900">
                                        {{ ucfirst($communication->type) }} - {{ $communication->marketer->name }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $communication->communication_date->format('Y-m-d H:i') }}</p>
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
