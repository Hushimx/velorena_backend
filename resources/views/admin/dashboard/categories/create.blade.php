@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Create Category')
@section('title', trans('categories.create_category'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('categories.create_category') }}</h1>
                <p class="text-gray-600">{{ trans('categories.add_new_category_description') }}</p>
            </div>
            <a href="{{ route('admin.categories.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-arrow-left pl-2"></i>
                <span>{{ trans('categories.back_to_categories') }}</span>
            </a>
        </div>

        <!-- Create Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name (English) -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('categories.name') }} ({{ trans('categories.english') }})
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror"
                            placeholder="{{ trans('categories.enter_name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name (Arabic) -->
                    <div>
                        <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('categories.name') }} ({{ trans('categories.arabic') }})
                        </label>
                        <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('name_ar') border-red-500 @enderror"
                            placeholder="{{ trans('categories.enter_name_ar') }}">
                        @error('name_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description (English) -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('categories.description') }} ({{ trans('categories.english') }})
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('description') border-red-500 @enderror"
                            placeholder="{{ trans('categories.enter_description') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description (Arabic) -->
                    <div>
                        <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('categories.description') }} ({{ trans('categories.arabic') }})
                        </label>
                        <textarea name="description_ar" id="description_ar" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('description_ar') border-red-500 @enderror"
                            placeholder="{{ trans('categories.enter_description_ar') }}">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('categories.image') }}
                        </label>
                        <input type="file" name="image" id="image" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('image') border-red-500 @enderror">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">{{ trans('categories.image_help') }}</p>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('categories.sort_order') }}
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                            min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('sort_order') border-red-500 @enderror"
                            placeholder="{{ trans('categories.enter_sort_order') }}">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('categories.status') }}
                        </label>
                        <select name="is_active" id="is_active"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('is_active') border-red-500 @enderror">
                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>
                                {{ trans('categories.active') }}
                            </option>
                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>
                                {{ trans('categories.inactive') }}
                            </option>
                        </select>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.categories.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        {{ trans('categories.cancel') }}
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        {{ trans('categories.create_category') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
