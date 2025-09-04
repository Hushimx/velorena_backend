@extends('marketer.layouts.app')

@section('pageTitle', __('marketer.your_leads'))
@section('title', __('marketer.your_leads'))

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-3">{{ __('marketer.your_leads') }}</h1>
                    <p class="text-xl text-blue-100 mb-4">{{ __('marketer.manage_and_track_your_assigned_leads') }}</p>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center bg-white/20 rounded-full px-4 py-2">
                            <i class="fas fa-users ml-2"></i>
                            <span>{{ __('marketer.total') }}: {{ auth()->guard('marketer')->user()->leads()->count() }}</span>
                        </div>
                        <div class="flex items-center bg-white/20 rounded-full px-4 py-2">
                            <i class="fas fa-plus ml-2"></i>
                            <span>{{ __('marketer.new') }}: {{ auth()->guard('marketer')->user()->leads()->where('status', 'new')->count() }}</span>
                        </div>
                        <div class="flex items-center bg-white/20 rounded-full px-4 py-2">
                            <i class="fas fa-star ml-2"></i>
                            <span>{{ __('marketer.qualified') }}: {{ auth()->guard('marketer')->user()->leads()->where('status', 'qualified')->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-bar text-6xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">{{ __('marketer.new_leads') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'new')->count() }}</p>
                        <p class="text-xs text-blue-600 mt-1">
                            <i class="fas fa-clock ml-1"></i>
                            {{ __('marketer.needs_follow_up') }}
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-plus text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">{{ __('marketer.contacted') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'contacted')->count() }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-phone ml-1"></i>
                            {{ __('marketer.in_follow_up') }}
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-phone text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
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

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
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

        <!-- Livewire Component -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            @livewire('marketer-leads-table')
        </div>
    </div>
@endsection
