@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Designer Details')
@section('title', trans('designers.show_designer'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('designers.designer_details') }}</h1>
                <p class="text-gray-600">{{ trans('designers.view_and_manage_designer_info') }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                <a href="{{ route('admin.designers.edit', $designer) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors w-full sm:w-auto">
                    <i class="fas fa-edit mx-1.5"></i>
                    <span>{{ trans('Edit') }}</span>
                </a>
                <a href="{{ route('admin.designers.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors w-full sm:w-auto">
                    <i class="fas fa-arrow-right pl-2"></i>
                    <span>{{ trans('designers.back_to_designers') }}</span>
                </a>
            </div>
        </div>

        <!-- Designer Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('designers.personal_information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-500">{{ trans('designers.full_name') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $designer->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ trans('designers.email') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $designer->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ trans('designers.phone') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $designer->phone ?: trans('designers.not_provided') }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-500">{{ trans('designers.portfolio_url') }}</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if ($designer->portfolio_url)
                                    <a href="{{ $designer->portfolio_url }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800">
                                        {{ $designer->portfolio_url }}
                                    </a>
                                @else
                                    {{ trans('designers.not_provided') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('designers.address_information') }}</h2>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">{{ trans('designers.address') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $designer->address ?: trans('designers.not_provided') }}
                        </p>
                    </div>
                </div>

                <!-- Bio -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('designers.bio') }}</h2>
                    <div>
                        <p class="text-sm text-gray-900">{{ $designer->bio ?: trans('designers.not_provided') }}</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Account Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('designers.account_information') }}</h2>
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-500">{{ trans('designers.designer_id') }}</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $designer->id }}</p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-500">{{ trans('designers.created_at') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $designer->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-500">{{ trans('designers.last_updated') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $designer->updated_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-500">{{ trans('designers.account_status') }}</label>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ trans('designers.active') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('designers.account_management') }}</h2>
                    <div class="space-y-3">
                        <a href="{{ route('admin.designers.edit', $designer) }}"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                            <i class="fas fa-edit"></i>
                            <span>{{ trans('edit') }}</span>
                        </a>
                        <form action="{{ route('admin.designers.destroy', $designer) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('{{ trans('designers.confirm_delete_designer') }}')"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                                <i class="fas fa-trash"></i>
                                <span>{{ trans('designers.delete_designer') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
