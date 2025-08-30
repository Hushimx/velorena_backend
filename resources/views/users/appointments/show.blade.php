@extends('layouts.app')

@section('pageTitle', trans('dashboard.appointment_details'))
@section('title', trans('dashboard.appointment_details'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('dashboard.appointment_details') }}</h1>
                <p class="text-gray-600">{{ trans('dashboard.view_consultation_details') }}</p>
            </div>
            <a href="{{ route('appointments.index') }}"
                class="inline-flex items-center gap-3 px-4 py-2 border border-gray-300 rounded-lg font-medium text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                <i class="fas fa-arrow-left"></i>
                {{ trans('dashboard.back_to_appointments') }}
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-calendar-check text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ trans('dashboard.consultation_information') }}
                        </h3>
                        <p class="text-sm text-gray-500">Appointment #{{ $appointment->id }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Status Banner -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="mr-3">
                            <span
                                class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                        @if ($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($appointment->status === 'accepted') bg-green-100 text-green-800
                        @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800
                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                        @elseif($appointment->status === 'rejected') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                                {{ trans('dashboard.' . $appointment->status) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <h6 class="font-semibold text-gray-900">
                                @if ($appointment->status === 'pending')
                                    {{ trans('dashboard.appointment_pending') }}
                                @elseif($appointment->status === 'accepted')
                                    {{ trans('dashboard.appointment_confirmed') }}
                                @elseif($appointment->status === 'completed')
                                    {{ trans('dashboard.consultation_completed') }}
                                @elseif($appointment->status === 'cancelled')
                                    {{ trans('dashboard.appointment_cancelled') }}
                                @elseif($appointment->status === 'rejected')
                                    {{ trans('dashboard.appointment_rejected') }}
                                @else
                                    {{ trans('dashboard.appointment_status') }}
                                @endif
                            </h6>
                            <p class="text-sm text-gray-600">
                                @if ($appointment->status === 'pending')
                                    {{ trans('dashboard.waiting_for_assignment') }}
                                @elseif($appointment->status === 'accepted')
                                    {{ trans('dashboard.confirmed_by_designer') }}
                                @elseif($appointment->status === 'completed')
                                    {{ trans('dashboard.completed_successfully') }}
                                @elseif($appointment->status === 'cancelled')
                                    {{ trans('dashboard.appointment_cancelled_desc') }}
                                @elseif($appointment->status === 'rejected')
                                    {{ trans('dashboard.rejected_by_designer') }}
                                @else
                                    {{ trans('dashboard.status_information') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Main Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Designer Information -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-user text-blue-600 mr-2"></i>{{ trans('dashboard.designer_information') }}
                        </h5>
                        @if ($appointment->designer)
                            <div class="flex items-center gap-3">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <i class="fas fa-user text-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-semibold text-gray-900">{{ $appointment->designer->name }}</h6>
                                    @if ($appointment->designer->specialization)
                                        <p class="text-sm text-gray-600">{{ $appointment->designer->specialization }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500">{{ $appointment->designer->email }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-3">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                    <i class="fas fa-clock text-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-semibold text-yellow-800">{{ trans('dashboard.pending_assignment') }}
                                    </h6>
                                    <p class="text-sm text-gray-600">{{ trans('dashboard.designer_assigned_soon') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Appointment Details -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center gap-3">
                            <i
                                class="fas fa-calendar-alt text-green-600 mr-2"></i>{{ trans('dashboard.appointment_details_title') }}
                        </h5>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ trans('dashboard.date') }}</span>
                                <p class="font-semibold text-gray-900">
                                    {{ $appointment->formatted_date ?? trans('dashboard.not_set') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ trans('dashboard.time') }}</span>
                                <p class="font-semibold text-gray-900">
                                    {{ $appointment->formatted_time ?? trans('dashboard.not_set') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ trans('dashboard.duration') }}</span>
                                <p class="font-semibold text-gray-900">{{ $appointment->duration_minutes }}
                                    {{ trans('dashboard.minutes') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ trans('dashboard.end_time') }}</span>
                                <p class="font-semibold text-gray-900">
                                    {{ $appointment->formatted_end_time ?? trans('dashboard.not_set') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                @if ($appointment->notes)
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center gap-3">
                            <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>{{ trans('dashboard.your_notes') }}
                        </h5>
                        <div class="bg-white rounded p-3">
                            <p class="text-gray-900">{{ $appointment->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Linked Order Information -->
                @if ($appointment->order)
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center gap-3">
                            <i
                                class="fas fa-shopping-cart text-green-600 mr-2"></i>{{ trans('dashboard.linked_order', ['default' => 'Linked Order']) }}
                        </h5>
                        <div class="bg-white rounded p-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span
                                        class="text-sm font-medium text-gray-500">{{ trans('dashboard.order_number', ['default' => 'Order Number']) }}</span>
                                    <p class="font-semibold text-gray-900">{{ $appointment->order->order_number }}</p>
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium text-gray-500">{{ trans('dashboard.order_status', ['default' => 'Order Status']) }}</span>
                                    <p class="font-semibold text-gray-900">
                                        {{ trans('orders.' . $appointment->order->status) }}</p>
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium text-gray-500">{{ trans('dashboard.order_total', ['default' => 'Order Total']) }}</span>
                                    <p class="font-semibold text-gray-900">
                                        ${{ number_format($appointment->order->total, 2) }}</p>
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium text-gray-500">{{ trans('dashboard.items_count', ['default' => 'Items']) }}</span>
                                    <p class="font-semibold text-gray-900">{{ $appointment->order->items->count() }}</p>
                                </div>
                            </div>

                            @if ($appointment->order_notes)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <span
                                        class="text-sm font-medium text-gray-500">{{ trans('dashboard.order_notes', ['default' => 'Order Notes']) }}</span>
                                    <p class="text-gray-900 mt-1">{{ $appointment->order_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Designer Response -->
                @if ($appointment->designer_notes)
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center gap-3">
                            <i class="fas fa-comment text-blue-600 mr-2"></i>{{ trans('dashboard.designer_response') }}
                        </h5>
                        <div class="bg-blue-50 rounded p-3">
                            <p class="text-gray-900">{{ $appointment->designer_notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center gap-3">
                        <i class="fas fa-history text-gray-600 mr-2"></i>{{ trans('dashboard.timeline') }}
                    </h5>
                    <div class="bg-white rounded p-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">{{ trans('dashboard.booked') }}:</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ $appointment->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            @if ($appointment->accepted_at)
                                <div class="flex justify-between">
                                    <span class="text-sm text-green-600">{{ trans('dashboard.accepted') }}:</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $appointment->accepted_at->format('M d, Y H:i') }}</span>
                                </div>
                            @endif
                            @if ($appointment->rejected_at)
                                <div class="flex justify-between">
                                    <span class="text-sm text-red-600">{{ trans('dashboard.rejected') }}:</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $appointment->rejected_at->format('M d, Y H:i') }}</span>
                                </div>
                            @endif
                            @if ($appointment->completed_at)
                                <div class="flex justify-between">
                                    <span class="text-sm text-blue-600">{{ trans('dashboard.completed') }}:</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $appointment->completed_at->format('M d, Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 gap-3">
                    @if ($appointment->canBeCancelled())
                        <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('{{ trans('dashboard.confirm_cancel') }}')"
                                class="inline-flex items-center gap-3 px-4 py-2 border border-red-300 rounded-lg font-medium text-sm text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                <i class="fas fa-times"></i>
                                {{ trans('dashboard.cancel_appointment') }}
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('appointments.index') }}"
                        class="inline-flex items-center gap-3 px-4 py-2 border border-gray-300 rounded-lg font-medium text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-arrow-left"></i>
                        {{ trans('dashboard.back_to_list') }}
                    </a>

                    <a href="{{ route('appointments.create') }}"
                        class="inline-flex items-center gap-3 px-4 py-2 border border-transparent rounded-lg font-medium text-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-calendar-plus"></i>
                        {{ trans('dashboard.book_new_appointment_action') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
