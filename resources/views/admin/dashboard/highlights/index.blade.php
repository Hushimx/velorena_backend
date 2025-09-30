@extends('admin.layouts.app')

@section('pageTitle', __('admin.highlights_management'))
@section('title', __('admin.highlights_management'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.highlights_management') }}</h1>
                <p class="text-gray-600">{{ __('admin.manage_highlights_platform') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.highlights.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>{{ __('admin.add_new_highlight') }}</span>
                </a>
            </div>
        </div>

        <!-- Highlights Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($highlights as $highlight)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <!-- Highlight Badge -->
                        <div class="h-2 w-full rounded-full mb-4 bg-blue-500"></div>

                        <!-- Highlight Info -->
                        <div class="space-y-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $highlight->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $highlight->name_ar }}</p>
                            </div>

                            @if ($highlight->description)
                                <p class="text-sm text-gray-500">{{ $highlight->description }}</p>
                            @endif

                            <!-- Status Badge -->
                            <div class="flex items-center justify-between">
                                @if ($highlight->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ __('admin.active') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ __('admin.inactive') }}
                                    </span>
                                @endif

                                <span class="text-xs text-gray-500">{{ __('admin.sort_order') }}:
                                    {{ $highlight->sort_order }}</span>
                            </div>

                            <!-- Products Count -->
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-box mr-1"></i>
                                {{ $highlight->products_count ?? 0 }} {{ __('admin.products') }}
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                        <div class="flex gap-3">
                            <a href="{{ route('admin.highlights.show', $highlight) }}"
                                class="text-green-600 hover:text-green-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>
                                {{ __('admin.view') }}
                            </a>
                            <a href="{{ route('admin.highlights.edit', $highlight) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-edit mr-1"></i>
                                {{ __('admin.edit') }}
                            </a>
                        </div>

                        <form action="{{ route('admin.highlights.destroy', $highlight) }}" method="POST"
                            onsubmit="return confirm('{{ __('admin.confirm_delete') }}')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                <i class="fas fa-trash mr-1"></i>
                                {{ __('admin.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('admin.no_highlights_found') }}</h3>
                        <p class="text-gray-500 mb-6">{{ __('admin.create_your_first_highlight') }}</p>
                        <a href="{{ route('admin.highlights.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>{{ __('admin.add_new_highlight') }}</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
