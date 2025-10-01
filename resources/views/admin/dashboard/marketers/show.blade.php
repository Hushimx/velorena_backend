@extends('admin.layouts.app')

@section('pageTitle', __('admin.marketer_details'))
@section('title', __('admin.marketer_details'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.marketer_details') }}</h1>
                <p class="text-gray-600">{{ $marketer->name }}</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.marketers.edit', $marketer) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-edit pl-2"></i>
                    <span>{{ __('admin.edit') }}</span>
                </a>
                <a href="{{ route('admin.marketers.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-arrow-right pl-2"></i>
                    <span>{{ __('admin.back_to_list') }}</span>
                </a>
            </div>
        </div>

        <!-- Marketer Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.basic_information') }}</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.name') }}</label>
                            <p class="text-gray-900">{{ $marketer->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.email') }}</label>
                            <p class="text-gray-900">{{ $marketer->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.phone') }}</label>
                            <p class="text-gray-900">{{ $marketer->phone ?? __('admin.not_specified') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.status') }}</label>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $marketer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $marketer->is_active ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.statistics') }}</h3>
                    <div class="space-y-3">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700">{{ __('admin.assigned_leads_count') }}</label>
                            <p class="text-2xl font-bold text-blue-600">{{ $marketer->leads_count ?? 0 }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.created_at') }}</label>
                            <p class="text-gray-900">{{ $marketer->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin.last_updated') }}</label>
                            <p class="text-gray-900">{{ $marketer->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leads Assigned to this Marketer -->
        @if ($marketer->leads->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.assigned_leads') }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.company_name') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.contact_person') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.status') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.priority') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($marketer->leads as $lead)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.leads.show', $lead) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            {{ $lead->company_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $lead->contact_person }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($lead->status == 'new') bg-gray-100 text-gray-800
                                    @elseif($lead->status == 'contacted') bg-blue-100 text-blue-800
                                    @elseif($lead->status == 'qualified') bg-yellow-100 text-yellow-800
                                    @elseif($lead->status == 'proposal_sent') bg-purple-100 text-purple-800
                                    @elseif($lead->status == 'negotiation') bg-orange-100 text-orange-800
                                    @elseif($lead->status == 'closed_won') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($lead->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($lead->priority == 'high') bg-red-100 text-red-800
                                    @elseif($lead->priority == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($lead->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $lead->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
