@extends('marketer.layouts.app')

@section('pageTitle', __('marketer.marketer_panel'))
@section('title', __('marketer.dashboard'))

@section('content')
    <div class="space-y-8">
        <!-- Welcome Header -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-2xl">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold mb-3">{{ __('marketer.welcome') }}، {{ auth()->guard('marketer')->user()->name }} 👋</h1>
                        <p class="text-xl text-blue-100 mb-4">{{ __('marketer.manage_your_assigned_leads') }}</p>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center bg-white/20 rounded-full px-4 py-2">
                                <i class="fas fa-calendar-alt ml-2"></i>
                                <span>{{ now()->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex items-center bg-white/20 rounded-full px-4 py-2">
                                <i class="fas fa-clock ml-2"></i>
                                <span>{{ now()->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-6xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-32 translate-x-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Leads -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">{{ __('marketer.total_leads') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->count() }}</p>
                        <p class="text-xs text-blue-600 mt-1">
                            <i class="fas fa-arrow-up ml-1"></i>
                            {{ __('marketer.+12%_from_last_month') }}
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- New Leads -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">{{ __('marketer.new_leads') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'new')->count() }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-plus ml-1"></i>
                            {{ __('marketer.needs_follow_up') }}
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-plus text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Qualified Leads -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">{{ __('marketer.qualified') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'qualified')->count() }}</p>
                        <p class="text-xs text-yellow-600 mt-1">
                            <i class="fas fa-star ml-1"></i>
                            {{ __('marketer.ready_for_proposal') }}
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Closed Won -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">{{ __('marketer.closed_won') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'closed_won')->count() }}</p>
                        <p class="text-xs text-purple-600 mt-1">
                            <i class="fas fa-trophy ml-1"></i>
                            {{ __('marketer.successes_this_month') }}
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-trophy text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Leads -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('marketer.recent_leads') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('marketer.manage_your_assigned_leads') }}</p>
                    </div>
                    <a href="{{ route('marketer.leads.index') }}" 
                        class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 flex items-center space-x-2 shadow-lg">
                        <span>{{ __('marketer.view_all') }}</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
            <div class="p-8">
                @if(auth()->guard('marketer')->user()->leads()->latest()->take(5)->count() > 0)
                    <div class="space-y-4">
                        @foreach(auth()->guard('marketer')->user()->leads()->latest()->take(5)->get() as $lead)
                        <div class="flex items-center justify-between p-6 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02]">
                            <div class="flex items-center space-x-6">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-building text-white text-xl"></i>
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
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                    @if($lead->status == 'new') bg-gray-100 text-gray-800 border border-gray-200
                                    @elseif($lead->status == 'contacted') bg-blue-100 text-blue-800 border border-blue-200
                                    @elseif($lead->status == 'qualified') bg-yellow-100 text-yellow-800 border border-yellow-200
                                    @elseif($lead->status == 'proposal_sent') bg-purple-100 text-purple-800 border border-purple-200
                                    @elseif($lead->status == 'negotiation') bg-orange-100 text-orange-800 border border-orange-200
                                    @elseif($lead->status == 'closed_won') bg-green-100 text-green-800 border border-green-200
                                    @else bg-red-100 text-red-800 border border-red-200
                                    @endif">
                                    {{ ucfirst($lead->status) }}
                                </span>
                                <a href="{{ route('marketer.leads.show', $lead) }}" 
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-xl flex items-center justify-center hover:shadow-lg transition-all duration-300">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-inbox text-4xl text-gray-500"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('marketer.no_leads_assigned_to_you_yet') }}</h3>
                        <p class="text-gray-500 mb-6">{{ __('marketer.leads_will_be_assigned_by_admin') }}</p>
                        <a href="{{ route('marketer.leads.index') }}" 
                            class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 inline-flex items-center space-x-2">
                            <span>{{ __('marketer.view_leads') }}</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('marketer.quick_actions') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('marketer.leads.index') }}" 
                    class="p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl border border-blue-200 hover:shadow-lg transition-all duration-300 group">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ __('marketer.view_leads') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('marketer.manage_all_leads') }}</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('marketer.leads.index') }}?status=new" 
                    class="p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl border border-green-200 hover:shadow-lg transition-all duration-300 group">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-plus text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ __('marketer.new_leads') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('marketer.follow_new_leads') }}</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('marketer.leads.index') }}?status=qualified" 
                    class="p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl border border-purple-200 hover:shadow-lg transition-all duration-300 group">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-star text-white text-xl"></i>
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
