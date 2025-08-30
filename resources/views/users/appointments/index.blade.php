@extends('layouts.app')

@section('pageTitle', trans('dashboard.my_appointments'))
@section('title', trans('dashboard.my_appointments'))

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-blue-500 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">{{ trans('dashboard.my_appointments') }}</h1>
                    <p class="text-blue-100 mt-1">
                        {{ trans('dashboard.manage_consultations', ['default' => 'Manage your scheduled consultations with expert designers']) }}
                    </p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-calendar-alt text-4xl text-blue-200"></i>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mx-3"></i>
                    <div class="text-green-800">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="flex justify-between items-center">
            <div>
                <small class="text-gray-500">{{ trans('dashboard.total_appointments') }}: <span
                        class="font-semibold text-gray-900">{{ $appointments->total() }}</span></small>
            </div>
            <a href="{{ route('appointments.create') }}"
                class="bg-blue-600 hover:bg-blue-700 gap-3 text-white px-4 py-2 rounded-lg inline-flex items-center transition-colors duration-200">
                <i class="fas fa-plus"></i>{{ trans('dashboard.book_new_appointment') }}
            </a>
        </div>

        <!-- Appointments List -->
        @if ($appointments->count() > 0)
            <div class="space-y-4">
                @foreach ($appointments as $appointment)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-100 rounded-full p-3">
                                        <i class="fas fa-calendar text-blue-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900 mb-1">{{ $appointment->formatted_date }}</h5>
                                        <p class="text-gray-500 text-sm">{{ $appointment->formatted_time }} -
                                            {{ $appointment->formatted_end_time }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span
                                        class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                                        @if ($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($appointment->status === 'accepted') bg-green-100 text-green-800
                                        @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800
                                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($appointment->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <!-- Action Buttons -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open"
                                            class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                            <div class="py-1">
                                                <a href="{{ route('appointments.show', $appointment) }}"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-eye mr-2"></i>{{ trans('dashboard.view') }}
                                                </a>
                                                @if ($appointment->canBeCancelled())
                                                    <form action="{{ route('appointments.cancel', $appointment) }}"
                                                        method="POST" class="block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            onclick="return confirm('{{ trans('dashboard.confirm_cancel', ['default' => 'Are you sure you want to cancel this appointment?']) }}')"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                            <i
                                                                class="fas fa-times mr-2"></i>{{ trans('dashboard.cancel_appointment') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <!-- Designer Info -->
                                <div>
                                    <h6 class="text-gray-500 text-sm font-medium mb-1">{{ trans('dashboard.designer') }}
                                    </h6>
                                    @if ($appointment->designer)
                                        <p class="font-medium text-gray-900">{{ $appointment->designer->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $appointment->designer->email }}</p>
                                    @else
                                        <p class="text-yellow-600 font-medium">{{ trans('dashboard.pending_assignment') }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Duration -->
                                <div>
                                    <h6 class="text-gray-500 text-sm font-medium mb-1">{{ trans('dashboard.duration') }}
                                    </h6>
                                    <p class="font-medium text-gray-900">{{ $appointment->duration_minutes }}
                                        {{ trans('dashboard.minutes') }}</p>
                                </div>

                                <!-- Booked Date -->
                                <div>
                                    <h6 class="text-gray-500 text-sm font-medium mb-1">
                                        {{ trans('dashboard.booked', ['default' => 'Booked']) }}</h6>
                                    <p class="font-medium text-gray-900">{{ $appointment->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Linked Orders -->
                            @if ($appointment->orders && $appointment->orders->count() > 0)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <h6 class="font-medium text-green-800 mb-3 flex items-center gap-3">
                                        <i class="fas fa-shopping-cart text-green-600"></i>
                                        {{ trans('dashboard.linked_orders', ['default' => 'Linked Orders']) }}
                                        ({{ $appointment->orders->count() }})
                                    </h6>
                                    <div class="space-y-2">
                                        @foreach ($appointment->orders as $order)
                                            <div class="flex justify-between items-center bg-white rounded p-2">
                                                <div class="flex items-center space-x-3">
                                                    <span
                                                        class="font-medium text-gray-900">{{ $order->order_number }}</span>
                                                    <span class="text-sm text-gray-500">{{ $order->items->count() }}
                                                        {{ trans('dashboard.items', ['default' => 'items']) }}</span>
                                                </div>
                                                <div class="text-right">
                                                    <span
                                                        class="font-semibold text-green-600">${{ number_format($order->total, 2) }}</span>
                                                    @if ($order->pivot->notes)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            {{ Str::limit($order->pivot->notes, 50) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if ($appointment->notes)
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <h6 class="font-medium text-gray-900 mb-2 flex items-center gap-3">
                                        <i class="fas fa-sticky-note text-blue-600"></i>{{ trans('dashboard.your_notes') }}
                                    </h6>
                                    <p class="text-sm text-gray-600">{{ Str::limit($appointment->notes, 100) }}</p>
                                </div>
                            @endif

                            <!-- Designer Response -->
                            @if ($appointment->designer_notes)
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h6 class="font-medium text-gray-900 mb-2 flex items-center">
                                        <i
                                            class="fas fa-comment mr-2 text-blue-600"></i>{{ trans('dashboard.designer_response') }}
                                    </h6>
                                    <p class="text-sm text-gray-600">{{ Str::limit($appointment->designer_notes, 100) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $appointments->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="text-center py-12">
                    <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-2xl text-gray-400"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">{{ trans('dashboard.no_appointments_yet') }}</h4>
                    <p class="text-gray-500 mb-6">{{ trans('dashboard.no_appointments_description') }}</p>
                    <a href="{{ route('appointments.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>{{ trans('dashboard.book_first_appointment') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
