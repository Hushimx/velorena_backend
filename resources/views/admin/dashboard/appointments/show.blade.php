@extends('admin.layouts.app')

@section('pageTitle', __('admin.appointment_details'))
@section('title', __('admin.appointment_details'))

@section('content')
    <style>
        .form-label {
            color: #1f2937 !important;
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
        }
    </style>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.appointment_details') }}</h1>
                <p class="text-gray-600">{{ __('admin.appointment_id') }}: #{{ $appointment->id }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    <span>{{ __('admin.edit_appointment') }}</span>
                </a>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('admin.back') }}</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Appointment Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="card bg-white">
                    <div class="card-header bg-white">
                        <h3 class="text-lg font-semibold text-white">
                            {{ __('admin.appointment_information') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ __('admin.status') }}</label>
                                <div>
                                    <span class="badge badge-{{ $appointment->status }} text-gray-900">
                                        {{ __('status.appointment.' . $appointment->status) ?: $appointment->status }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.appointment_date') }}</label>
                                <p class="text-gray-900">
                                    {{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : __('admin.not_scheduled') }}
                                </p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.appointment_time') }}</label>
                                <p class="text-gray-900">
                                    {{ $appointment->appointment_time ?: __('admin.not_scheduled') }}
                                </p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.created_at') }}</label>
                                <p class="text-gray-900">{{ $appointment->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card bg-white">
                    <div class="card-header bg-white">
                        <h3 class="text-lg font-semibold text-white">
                            {{ __('admin.customer_information') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ __('admin.customer_name') }}</label>
                                <p class="text-gray-900">{{ $appointment->user->full_name ?? $appointment->user->email }}
                                </p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.email') }}</label>
                                <p class="text-gray-900">{{ $appointment->user->email }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.phone') }}</label>
                                <p class="text-gray-900">{{ $appointment->user->phone ?? __('admin.not_provided') }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.registration_date') }}</label>
                                <p class="text-gray-900">{{ $appointment->user->created_at->format('Y-m-d') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Designer Information -->
                @if ($appointment->designer)
                    <div class="card bg-white">
                        <div class="card-header bg-white">
                            <h3 class="text-lg font-semibold text-white">
                                {{ __('admin.designer_information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">{{ __('admin.designer_name') }}</label>
                                    <p class="text-gray-900">{{ $appointment->designer->name }}</p>
                                </div>
                                <div>
                                    <label class="form-label">{{ __('admin.email') }}</label>
                                    <p class="text-gray-900">{{ $appointment->designer->email }}</p>
                                </div>
                                <div>
                                    <label class="form-label">{{ __('admin.phone') }}</label>
                                    <p class="text-gray-900">
                                        {{ $appointment->designer->phone ?? __('admin.not_provided') }}</p>
                                </div>
                                <div>
                                    <label class="form-label">{{ __('admin.specialization') }}</label>
                                    <p class="text-gray-900">
                                        {{ $appointment->designer->specialization ?? __('admin.not_specified') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if ($appointment->notes || $appointment->designer_notes)
                    <div class="card bg-white">
                        <div class="card-header bg-white">
                            <h3 class="text-lg font-semibold text-white">{{ __('admin.notes') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            @if ($appointment->notes)
                                <div class="mb-4">
                                    <label class="form-label">{{ __('admin.customer_notes') }}</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $appointment->notes }}</p>
                                </div>
                            @endif
                            @if ($appointment->designer_notes)
                                <div>
                                    <label class="form-label">{{ __('admin.designer_notes') }}</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $appointment->designer_notes }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Information -->
            <div class="lg:col-span-1">
                @if ($appointment->order)
                    <div class="card bg-white">
                        <div class="card-header bg-white">
                            <h3 class="text-lg font-semibold text-white">
                                {{ __('admin.order_information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label">{{ __('admin.order_number') }}</label>
                                    <p class="text-gray-900">#{{ $appointment->order->order_number }}</p>
                                </div>
                                <div>
                                    <label class="form-label">{{ __('admin.order_status') }}</label>
                                    <div>
                                        <span class="badge badge-{{ $appointment->order->status }}">
                                            {{ __('status.order.' . $appointment->order->status) ?: $appointment->order->status }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">{{ __('admin.order_total') }}</label>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ number_format($appointment->order->total, 2) }} ر.س
                                    </p>
                                </div>
                                <div>
                                    <label class="form-label">{{ __('admin.order_date') }}</label>
                                    <p class="text-gray-900">{{ $appointment->order->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>


                            <div class="mt-6">
                                <a href="{{ route('admin.orders.show', $appointment->order) }}"
                                    class="btn btn-primary w-full">
                                    <i class="fas fa-eye"></i>
                                    <span>{{ __('admin.view_order') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items with Designs -->
                    @if ($appointment->order->items && $appointment->order->items->count() > 0)
                        <div class="card mt-6 bg-white">
                            <div class="card-header bg-white">
                                <h3 class="text-lg font-semibold text-white">
                                    {{ __('admin.order_items') }} ({{ $appointment->order->items->count() }})</h3>
                            </div>
                            <div class="card-body">
                                <div class="space-y-4">
                                    @foreach ($appointment->order->items as $item)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        @if ($item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                alt="{{ $item->product->name }}"
                                                                class="w-12 h-12 object-cover rounded-lg">
                                                        @else
                                                            <div
                                                                class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-image text-gray-400"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h4 class="font-semibold text-gray-900">
                                                                {{ $item->product->name }}</h4>
                                                            <p class="text-sm text-gray-600">{{ $item->quantity }}x
                                                                {{ number_format($item->unit_price, 2) }} ر.س</p>
                                                        </div>
                                                    </div>

                                                    <!-- Order Item Designs -->
                                                    <!-- Designs are now shown at order level -->
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-lg font-semibold text-gray-900">
                                                        {{ number_format($item->total_price, 2) }} ر.س</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Order Designs Panel -->
                    @if ($appointment->order && $appointment->order->designs && $appointment->order->designs->count() > 0)
                        <div class="card mt-6 bg-white">
                            <div class="card-header bg-white">
                                <h3 class="text-lg font-semibold text-white">
                                    <i class="fas fa-palette mr-2"></i>
                                    {{ __('Order Designs') }} ({{ $appointment->order->designs->count() }})
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($appointment->order->designs as $design)
                                        <div
                                            class="bg-purple-50 rounded-lg p-3 border border-purple-200 hover:shadow-md transition-shadow">
                                            <!-- Design Image -->
                                            <div class="aspect-square mb-3 bg-white rounded-lg overflow-hidden">
                                                @if ($design->thumbnail_url)
                                                    <img src="{{ $design->thumbnail_url }}" alt="{{ $design->title }}"
                                                        class="w-full h-full object-cover hover:scale-105 transition-transform cursor-pointer"
                                                        onclick="openImageModal('{{ $design->image_url }}', '{{ addslashes($design->title) }}')">
                                                @else
                                                    <div
                                                        class="w-full h-full bg-purple-100 flex items-center justify-center">
                                                        <i class="fas fa-image text-purple-400 text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Design Info -->
                                            <div class="space-y-2">
                                                <h4 class="font-medium text-purple-900 text-sm truncate"
                                                    title="{{ $design->title }}">
                                                    {{ $design->title }}
                                                </h4>

                                                @if ($design->notes)
                                                    <div class="text-xs text-purple-700 bg-purple-100 rounded px-2 py-1">
                                                        <i class="fas fa-sticky-note mr-1"></i>
                                                        {{ $design->notes }}
                                                    </div>
                                                @endif

                                                <div class="flex justify-between items-center text-xs text-purple-600">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $design->created_at->diffForHumans() }}
                                                    </span>
                                                    @if ($design->priority > 1)
                                                        <span class="bg-purple-200 text-purple-800 px-2 py-1 rounded-full">
                                                            #{{ $design->priority }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="card bg-white">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart text-4xl mb-4" style="color: var(--brand-yellow);"></i>
                            <p class="text-gray-500">{{ __('admin.no_order_associated') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
            <div id="modalTitle"
                class="absolute bottom-4 left-4 right-4 text-white text-center bg-black bg-opacity-50 rounded p-2"></div>
        </div>
    </div>

    <script>
        function openImageModal(imageUrl, title) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');

            modalImage.src = imageUrl;
            modalImage.alt = title;
            modalTitle.textContent = title;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection
