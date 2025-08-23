@extends('layouts.app')

@section('pageTitle', trans('dashboard.dashboard'))
@section('title', trans('dashboard.dashboard'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('dashboard.welcome_back') }}, {{ Auth::user()->name }}!
                </h1>
                <p class="text-gray-600">{{ trans('dashboard.whats_happening') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('user.products.index') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-box"></i>
                    <span>{{ trans('dashboard.view_products') }}</span>
                </a>
                <a href="{{ route('appointments.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-calendar-plus"></i>
                    <span>{{ trans('dashboard.book_new_appointment') }}</span>
                </a>
                <a href="{{ route('appointments.index') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ trans('dashboard.view_all_appointments') }}</span>
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span class="text-green-800 font-medium">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Appointments -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-calendar-alt text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ trans('dashboard.total_appointments') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            <a href="{{ route('appointments.index') }}" class="hover:text-blue-600 transition-colors">
                                {{ \App\Models\Appointment::where('user_id', Auth::id())->count() }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending Appointments -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ trans('dashboard.pending') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Appointment::where('user_id', Auth::id())->where('status', 'pending')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Accepted Appointments -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ trans('dashboard.confirmed') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Appointment::where('user_id', Auth::id())->where('status', 'accepted')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Completed Appointments -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-check-double text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ trans('dashboard.completed') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Appointment::where('user_id', Auth::id())->where('status', 'completed')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Available Products -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-box text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ trans('dashboard.available_products') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            <a href="{{ route('user.products.index') }}" class="hover:text-orange-600 transition-colors">
                                {{ \App\Models\Product::where('is_active', true)->count() }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Profile Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        {{ trans('dashboard.profile_information') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">{{ trans('users.full_name') }}</span>
                            <span class="text-sm text-gray-900">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">{{ trans('users.email') }}</span>
                            <span class="text-sm text-gray-900">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">{{ trans('dashboard.member_since') }}</span>
                            <span class="text-sm text-gray-900">{{ Auth::user()->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">{{ trans('dashboard.last_updated') }}</span>
                            <span class="text-sm text-gray-900">{{ Auth::user()->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt text-green-600 mr-2"></i>
                        {{ trans('dashboard.quick_actions') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('user.products.index') }}"
                            class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                            <i class="fas fa-box text-purple-600 mr-3"></i>
                            <span class="font-medium text-purple-900">{{ trans('dashboard.browse_products') }}</span>
                        </a>
                        <a href="{{ route('appointments.create') }}"
                            class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <i class="fas fa-calendar-plus text-blue-600 mr-3"></i>
                            <span class="font-medium text-blue-900">{{ trans('dashboard.book_new_appointment') }}</span>
                        </a>
                        <a href="{{ route('appointments.index') }}"
                            class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                            <i class="fas fa-calendar-alt text-green-600 mr-3"></i>
                            <span class="font-medium text-green-900">{{ trans('dashboard.view_all_appointments') }}</span>
                        </a>
                        <a href="{{ route('user.orders.index') }}"
                            class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                            <i class="fas fa-shopping-cart text-orange-600 mr-3"></i>
                            <span class="font-medium text-orange-900">{{ trans('orders.my_orders') }}</span>
                        </a>
                        <a href="#"
                            class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-user-edit text-gray-600 mr-3"></i>
                            <span class="font-medium text-gray-900">{{ trans('dashboard.update_profile') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-history text-purple-600 mr-2"></i>
                    {{ trans('dashboard.recent_appointments') }}
                </h3>
                <a href="{{ route('appointments.index') }}"
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    {{ trans('dashboard.view_all') }}
                </a>
            </div>
            <div class="p-6">
                @php
                    $recentAppointments = \App\Models\Appointment::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @if ($recentAppointments->count() > 0)
                    <div class="space-y-4">
                        @foreach ($recentAppointments as $appointment)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $appointment->formatted_date }}</p>
                                        <p class="text-sm text-gray-500">{{ $appointment->formatted_time }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                @if ($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($appointment->status === 'accepted') bg-green-100 text-green-800
                                @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800
                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                @elseif($appointment->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                        {{ trans('dashboard.' . $appointment->status) }}
                                    </span>
                                    <a href="{{ route('appointments.show', $appointment) }}"
                                        class="text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ trans('dashboard.no_appointments_yet') }}
                        </h4>
                        <p class="text-gray-600 mb-4">{{ trans('dashboard.no_appointments_description') }}</p>
                        <a href="{{ route('appointments.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            {{ trans('dashboard.book_first_appointment') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
