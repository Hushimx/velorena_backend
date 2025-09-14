@extends('marketer.layouts.app')

@section('title', __('marketer.dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('marketer.welcome') }}، {{ Auth::guard('marketer')->user()->name }}</h1>
                <p class="mt-1" style="color: #ffde9f;">{{ __('marketer.manage_your_assigned_leads') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('marketer.leads.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-users"></i>
                    <span class="hidden sm:block">{{ __('marketer.view_leads') }}</span>
                </a>
                <div class="hidden md:block">
                    <i class="fas fa-chart-line text-4xl" style="color: #ffde9f;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Marketer Specific -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Leads -->
        <a href="{{ route('marketer.leads.index') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('marketer.total_leads') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->count() }}</p>
                </div>
            </div>
        </a>

        <!-- New Leads -->
        <a href="{{ route('marketer.leads.index', ['status' => 'new']) }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-plus text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('marketer.new_leads') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'new')->count() }}</p>
                </div>
            </div>
        </a>

        <!-- Qualified Leads -->
        <a href="{{ route('marketer.leads.index', ['status' => 'qualified']) }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('marketer.qualified') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'qualified')->count() }}</p>
                </div>
            </div>
        </a>

        <!-- Closed Won -->
        <a href="{{ route('marketer.leads.index', ['status' => 'closed_won']) }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-trophy text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('marketer.closed_won') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'closed_won')->count() }}</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Leads -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('marketer.recent_leads') }}</h2>
                    <p class="text-gray-600 mt-1">{{ __('marketer.manage_your_assigned_leads') }}</p>
                </div>
                <a href="{{ route('marketer.leads.index') }}" 
                    class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-all duration-200 flex items-center gap-2">
                    <span>{{ __('marketer.view_all') }}</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            @if(auth()->guard('marketer')->user()->leads()->latest()->take(5)->count() > 0)
                <div class="space-y-4">
                    @foreach(auth()->guard('marketer')->user()->leads()->latest()->take(5)->get() as $lead)
                    <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-all duration-200">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: #ffde9f; color: #2a1e1e;">
                                    <i class="fas fa-building text-lg"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $lead->company_name }}</h3>
                                <p class="text-gray-600">{{ $lead->contact_person }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-envelope ml-1"></i>
                                    {{ $lead->email }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($lead->status == 'new') bg-gray-100 text-gray-800
                                @elseif($lead->status == 'contacted') bg-blue-100 text-blue-800
                                @elseif($lead->status == 'qualified') bg-yellow-100 text-yellow-800
                                @elseif($lead->status == 'proposal_sent') bg-purple-100 text-purple-800
                                @elseif($lead->status == 'negotiation') bg-orange-100 text-orange-800
                                @elseif($lead->status == 'closed_won') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($lead->status) }}
                            </span>
                            <a href="{{ route('marketer.leads.show', $lead) }}" 
                                class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: #ffde9f; color: #2a1e1e;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: #ffde9f; color: #2a1e1e;">
                        <i class="fas fa-inbox text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('marketer.no_leads_assigned_to_you_yet') }}</h3>
                    <p class="text-gray-500 mb-4">{{ __('marketer.leads_will_be_assigned_by_admin') }}</p>
                    <a href="{{ route('marketer.leads.index') }}" 
                        class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-all duration-200 inline-flex items-center gap-2">
                        <span>{{ __('marketer.view_leads') }}</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('marketer.quick_actions') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('marketer.leads.index') }}" 
                class="p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-all duration-200 group">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200" style="background-color: #ffde9f; color: #2a1e1e;">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ __('marketer.view_leads') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('marketer.manage_all_leads') }}</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('marketer.leads.index') }}?status=new" 
                class="p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-all duration-200 group">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200" style="background-color: #ffde9f; color: #2a1e1e;">
                        <i class="fas fa-plus text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ __('marketer.new_leads') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('marketer.follow_new_leads') }}</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('marketer.leads.index') }}?status=qualified" 
                class="p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-all duration-200 group">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200" style="background-color: #ffde9f; color: #2a1e1e;">
                        <i class="fas fa-star text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ __('marketer.qualified') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('marketer.prepare_proposals') }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
