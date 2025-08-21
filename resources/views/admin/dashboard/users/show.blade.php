@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Show User')
@section('title', trans('users.show_user'))

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Left: Back and Title -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="text-gray-600 hover:text-gray-900 transition duration-150 transform hover:scale-110 mb-2 sm:mb-0">
                        <i class="fas fa-arrow-right text-xl"></i>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ trans('users.user_details') }}</h2>
                        <p class="text-sm text-gray-500">{{ trans('users.view_and_manage_user_info') }}</p>
                    </div>
                </div>
                <!-- Right: Actions -->
                <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto mt-4 md:mt-0">
                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-lg hover:from-yellow-500 hover:to-yellow-600 transition duration-150 transform hover:scale-105 shadow-md">
                        <i class="fas fa-edit ml-2"></i>
                        {{ trans('users.edit_user') }}
                    </a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block"
                        id="deleteUserForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete()"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 w-full sm:w-auto mt-2 sm:mt-0">
                            <i class="fas fa-trash-alt ml-2"></i>
                            {{ trans('users.delete_user') }}
                        </button>
                    </form>
                    <div class="w-full sm:w-auto">
                        <span
                            class="flex items-center justify-center w-full px-3 py-2 bg-gray-100 text-gray-700 rounded-lg mt-2 sm:mt-0">
                            <i class="fas fa-info-circle ml-2"></i>
                            <span class="ml-1">{{ trans('users.account_management') }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- User Profile Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-card group">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="relative group-hover:scale-105 transition-transform duration-300">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full opacity-0 group-hover:opacity-20 transition-opacity duration-300">
                            </div>
                            @if ($user->image)
                                <img class="h-32 w-32 rounded-full ring-4 ring-white shadow-lg"
                                    src="{{ asset('storage/' . $user->image) }}"
                                    alt="{{ $user->full_name ?? $user->company_name }}">
                            @else
                                <img class="h-32 w-32 rounded-full ring-4 ring-white shadow-lg"
                                    src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name ?? $user->company_name) }}&background=random&size=128"
                                    alt="{{ $user->full_name ?? $user->company_name }}">
                            @endif
                            <span
                                class="absolute bottom-2 left-2 block h-4 w-4 rounded-full ring-2 ring-white bg-green-400"></span>
                        </div>
                        <h3
                            class="mt-4 text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300">
                            {{ $user->full_name ?? $user->company_name }}</h3>
                        <p class="text-sm text-gray-500">{{ trans('users.user_id') }}: {{ $user->id }}</p>
                        <div class="mt-2">
                            <span
                                class="px-3 py-1 text-sm font-semibold rounded-full bg-gradient-to-r from-green-100 to-green-200 text-green-800 shadow-sm">
                                <i class="fas fa-check-circle ml-1"></i> {{ trans('users.' . $user->client_type) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-card group">
                <div class="p-6">
                    <h3
                        class="text-lg font-semibold text-gray-900 mb-4 flex items-center group-hover:text-blue-600 transition-colors duration-300">
                        <i class="fas fa-address-card text-blue-500"></i>
                        <span class="mx-2">{{ trans('users.contact_information') }}</span>
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start group/item">
                            <div class="flex-shrink-0">
                                <i
                                    class="fas fa-envelope text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-sm font-medium text-gray-500">{{ trans('users.email') }}</p>
                                <p
                                    class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                    {{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-start group/item">
                            <div class="flex-shrink-0">
                                <i
                                    class="fas fa-phone text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-sm font-medium text-gray-500">{{ trans('users.phone') }}</p>
                                <p
                                    class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                    {{ $user->phone ?? trans('users.not_provided') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-card group">
                <div class="p-6">
                    <h3
                        class="text-lg font-semibold text-gray-900 mb-4 flex items-center group-hover:text-blue-600 transition-colors duration-300">
                        <i class="fas fa-user-shield text-blue-500"></i>
                        <span class="mx-2">{{ trans('users.account_information') }}</span>
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start group/item">
                            <div class="flex-shrink-0">
                                <i
                                    class="fas fa-calendar text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-sm font-medium text-gray-500">{{ trans('users.created_at') }}</p>
                                <p
                                    class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                    {{ $user->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start group/item">
                            <div class="flex-shrink-0">
                                <i
                                    class="fas fa-clock text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-sm font-medium text-gray-500">{{ trans('users.last_updated') }}</p>
                                <p
                                    class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                    {{ $user->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start group/item">
                            <div class="flex-shrink-0">
                                <i
                                    class="fas fa-key text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-sm font-medium text-gray-500">{{ trans('users.account_status') }}</p>
                                <p
                                    class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ trans('users.active') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details Section -->
        <div class="mt-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if ($user->client_type === 'individual')
                    <!-- Personal Information Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-card group">
                        <div class="p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4 flex items-center group-hover:text-blue-600 transition-colors duration-300">
                                <i class="fas fa-user text-blue-500"></i>
                                <span class="mx-2">{{ trans('users.personal_information') }}</span>
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-start group/item">
                                    <div class="flex-shrink-0">
                                        <i
                                            class="fas fa-user text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                                    </div>
                                    <div class="mr-3">
                                        <p class="text-sm font-medium text-gray-500">{{ trans('users.full_name') }}</p>
                                        <p
                                            class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                            {{ $user->full_name ?? trans('users.not_provided') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Company Information Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-card group">
                        <div class="p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4 flex items-center group-hover:text-blue-600 transition-colors duration-300">
                                <i class="fas fa-building text-blue-500"></i>
                                <span class="mx-2">{{ trans('users.company_information') }}</span>
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-start group/item">
                                    <div class="flex-shrink-0">
                                        <i
                                            class="fas fa-building text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                                    </div>
                                    <div class="mr-3">
                                        <p class="text-sm font-medium text-gray-500">{{ trans('users.company_name') }}</p>
                                        <p
                                            class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                            {{ $user->company_name ?? trans('users.not_provided') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start group/item">
                                    <div class="flex-shrink-0">
                                        <i
                                            class="fas fa-user-tie text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                                    </div>
                                    <div class="mr-3">
                                        <p class="text-sm font-medium text-gray-500">{{ trans('users.contact_person') }}
                                        </p>
                                        <p
                                            class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                            {{ $user->contact_person ?? trans('users.not_provided') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start group/item">
                                    <div class="flex-shrink-0">
                                        <i
                                            class="fas fa-receipt text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                                    </div>
                                    <div class="mr-3">
                                        <p class="text-sm font-medium text-gray-500">{{ trans('users.vat_number') }}</p>
                                        <p
                                            class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                            {{ $user->vat_number ?? trans('users.not_provided') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start group/item">
                                    <div class="flex-shrink-0">
                                        <i
                                            class="fas fa-file-contract text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                                    </div>
                                    <div class="mr-3">
                                        <p class="text-sm font-medium text-gray-500">{{ trans('users.cr_number') }}</p>
                                        <p
                                            class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                            {{ $user->cr_number ?? trans('users.not_provided') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Information Card for Company Users -->
                    @if ($user->client_type === 'company' && ($user->cr_document_path || $user->vat_document_path))
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-card group">
                            <div class="p-6">
                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-4 flex items-center group-hover:text-blue-600 transition-colors duration-300">
                                    <i class="fas fa-file-alt text-blue-500 ml-2"></i>
                                    {{ trans('users.document_information') }}
                                </h3>
                                <div class="space-y-4">
                                    @if ($user->cr_document_path)
                                        <div class="flex items-start group/item">
                                            <div class="flex-shrink-0">
                                                <i
                                                    class="fas fa-file-contract text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                                            </div>
                                            <div class="mr-3">
                                                <p class="text-sm font-medium text-gray-500">
                                                    {{ trans('users.cr_document') }}</p>
                                                <a href="{{ Storage::url($user->cr_document_path) }}" target="_blank"
                                                    class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-300">
                                                    <i class="fas fa-download ml-1"></i>
                                                    {{ trans('users.download_document') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($user->vat_document_path)
                                        <div class="flex items-start group/item">
                                            <div class="flex-shrink-0">
                                                <i
                                                    class="fas fa-receipt text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                                            </div>
                                            <div class="mr-3">
                                                <p class="text-sm font-medium text-gray-500">
                                                    {{ trans('users.vat_document') }}</p>
                                                <a href="{{ Storage::url($user->vat_document_path) }}" target="_blank"
                                                    class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-300">
                                                    <i class="fas fa-download ml-1"></i>
                                                    {{ trans('users.download_document') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Address Information Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-card group">
                    <div class="p-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 mb-4 flex items-center group-hover:text-blue-600 transition-colors duration-300">
                            <i class="fas fa-map-marker-alt text-blue-500"></i>
                            <span class="mx-2">{{ trans('users.address_information') }}</span>
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start group/item">
                                <div class="flex-shrink-0">
                                    <i
                                        class="fas fa-map-marker-alt text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-gray-500">{{ trans('users.address') }}</p>
                                    <p
                                        class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                        {{ $user->address ?? trans('users.not_provided') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start group/item">
                                <div class="flex-shrink-0">
                                    <i
                                        class="fas fa-city text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-gray-500">{{ trans('users.city') }}</p>
                                    <p
                                        class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                        {{ $user->city ?? trans('users.not_provided') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start group/item">
                                <div class="flex-shrink-0">
                                    <i
                                        class="fas fa-flag text-gray-400 w-6 group-hover/item:text-blue-500 transition-colors duration-300"></i>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-gray-500">{{ trans('users.country') }}</p>
                                    <p
                                        class="text-sm text-gray-900 group-hover/item:text-blue-600 transition-colors duration-300">
                                        {{ $user->country ?? trans('users.not_provided') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if ($user->notes)
                <div class="mt-6">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-card group">
                        <div class="p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4 flex items-center group-hover:text-blue-600 transition-colors duration-300">
                                <i class="fas fa-sticky-note text-blue-500 ml-2"></i>
                                {{ trans('users.notes') }}
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700">{{ $user->notes }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- User Orders Section - Coming Soon -->
    <div class="max-w-7xl mx-auto mt-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">{{ trans('users.user_orders') }}</h2>
                <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 rounded-lg">
                    <i class="fas fa-clock"></i>
                    <span class="mx-2">{{ trans('users.coming_soon') }}</span>
                </span>
            </div>
            <div class="text-center py-8">
                <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ trans('users.orders_feature_coming_soon') }}</p>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            Swal.fire({
                title: '{{ trans('users.confirm_delete_title') }}',
                text: "{{ trans('users.confirm_delete_user') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ trans('users.delete') }}',
                cancelButtonText: '{{ trans('users.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteUserForm').submit();
                }
            });
        }
    </script>
@endsection
