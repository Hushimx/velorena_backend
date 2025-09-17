@extends('marketer.layouts.app')

@section('title', __('marketer.urgent_leads'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('marketer.urgent_leads') }}</h1>
                <p class="mt-1" style="color: #ffde9f;">{{ __('marketer.leads_need_immediate_attention') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-red-500 text-white px-4 py-2 rounded-lg">
                    <span class="text-lg font-bold">{{ $urgentLeads->count() }}</span>
                    <span class="text-sm">{{ __('marketer.urgent') }}</span>
                </div>
                <a href="{{ route('marketer.leads.index') }}"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-arrow-right"></i>
                    <span class="hidden sm:block">{{ __('marketer.all_leads') }}</span>
                </a>
            </div>
        </div>
    </div>

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

    @if($urgentLeads->count() > 0)
        <!-- Urgent Leads List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('marketer.priority_level') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('marketer.company') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('marketer.contact_person') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('marketer.status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('marketer.urgency_reason') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('marketer.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($urgentLeads as $lead)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($lead->status == 'new')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ __('marketer.critical') }}
                                    </span>
                                @elseif($lead->status == 'didnt_respond' && !$lead->next_follow_up)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ __('marketer.high') }}
                                    </span>
                                @elseif($lead->next_follow_up && $lead->next_follow_up <= now())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        {{ __('marketer.overdue') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('marketer.medium') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-lg flex items-center justify-center" style="background-color: #ffde9f; color: #2a1e1e;">
                                            <i class="fas fa-building text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $lead->company_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $lead->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lead->contact_person }}</div>
                                @if($lead->phone)
                                    <div class="text-sm text-gray-500">{{ $lead->phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($lead->status == 'new')
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                                        {{ __('marketer.new_lead_not_contacted') }}
                                    </div>
                                @elseif($lead->status == 'didnt_respond' && !$lead->next_follow_up)
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-phone-slash text-red-500 mr-1"></i>
                                        {{ __('marketer.didnt_respond_no_followup') }}
                                    </div>
                                @elseif($lead->next_follow_up && $lead->next_follow_up <= now())
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-calendar-times text-orange-500 mr-1"></i>
                                        {{ __('marketer.followup_overdue') }}: {{ $lead->next_follow_up->format('Y-m-d H:i') }}
                                    </div>
                                @else
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-clock text-blue-500 mr-1"></i>
                                        {{ __('marketer.needs_attention') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('marketer.leads.show', $lead) }}"
                                        class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded-lg transition-colors duration-200 flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        <span class="hidden sm:inline">{{ __('marketer.view') }}</span>
                                    </a>
                                    <a href="{{ route('marketer.leads.edit', $lead) }}"
                                        class="text-purple-600 hover:text-purple-900 bg-purple-100 hover:bg-purple-200 px-3 py-1 rounded-lg transition-colors duration-200 flex items-center gap-1">
                                        <i class="fas fa-edit"></i>
                                        <span class="hidden sm:inline">{{ __('marketer.edit') }}</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- No Urgent Leads -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('marketer.no_urgent_leads') }}</h3>
            <p class="text-gray-600 mb-4">{{ __('marketer.all_leads_under_control') }}</p>
            <a href="{{ route('marketer.leads.index') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                <i class="fas fa-list mr-2"></i>
                {{ __('marketer.view_all_leads') }}
            </a>
        </div>
    @endif
</div>
@endsection
