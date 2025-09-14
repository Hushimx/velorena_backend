@extends('marketer.layouts.app')

@section('title', __('marketer.your_leads'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('marketer.your_leads') }}</h1>
                <p class="mt-1" style="color: #ffde9f;">{{ __('marketer.manage_and_track_your_assigned_leads') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden md:block">
                    <i class="fas fa-users text-4xl" style="color: #ffde9f;"></i>
                </div>
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

    @if(session('info'))
        <div class="bg-white border-l-4 border-blue-500 shadow-lg rounded-lg p-4 mb-4 animate-slide-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-info text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-blue-400 hover:text-blue-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-white border-l-4 border-yellow-500 shadow-lg rounded-lg p-4 mb-4 animate-slide-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-yellow-400 hover:text-yellow-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-plus text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('marketer.new_leads') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'new')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-phone text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('marketer.contacted') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'contacted')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('marketer.qualified') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'qualified')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-trophy text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('marketer.closed_won') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->guard('marketer')->user()->leads()->where('status', 'closed_won')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Request New Leads Section -->
    @php
        $marketer = auth()->guard('marketer')->user();
        $activeLeads = $marketer->leads()->whereNotIn('status', ['closed_won', 'closed_lost'])->count();
        $canRequestNew = $activeLeads === 0;
    @endphp
    
    @if($canRequestNew)
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('marketer.ready_for_new_leads') }}</h3>
                <p class="text-gray-600">{{ __('marketer.all_leads_completed_request_new') }}</p>
            </div>
            <form action="{{ route('marketer.leads.request-new') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition-colors duration-200 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    {{ __('marketer.request_new_leads') }}
                </button>
            </form>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('marketer.complete_current_leads') }}</h3>
                <p class="text-gray-600">{{ __('marketer.you_have_active_leads', ['count' => $activeLeads]) }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg px-4 py-2">
                <span class="text-lg font-semibold text-gray-700">{{ $activeLeads }} {{ __('marketer.active_leads') }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Livewire Component -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @livewire('marketer-leads-table')
    </div>
</div>
@endsection
