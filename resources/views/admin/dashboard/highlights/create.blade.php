@extends('admin.layouts.app')

@section('pageTitle', __('admin.add_new_highlight'))
@section('title', __('admin.add_new_highlight'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.add_new_highlight') }}</h1>
                <p class="text-gray-600">{{ __('admin.create_new_highlight_description') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.highlights.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('admin.back_to_highlights') }}</span>
                </a>
            </div>
        </div>

        <!-- Create Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.highlights.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- English Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('name') border-red-500 @enderror"
                               placeholder="{{ __('admin.enter_highlight_name') }}"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Arabic Name -->
                    <div>
                        <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.name_ar') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name_ar" 
                               name="name_ar" 
                               value="{{ old('name_ar') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('name_ar') border-red-500 @enderror"
                               placeholder="{{ __('admin.enter_highlight_name_ar') }}"
                               required>
                        @error('name_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.sort_order') }}
                        </label>
                        <select id="sort_order" 
                                name="sort_order" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('sort_order') border-red-500 @enderror">
                            <option value="1" {{ old('sort_order', 1) == 1 ? 'selected' : '' }}>1 - First</option>
                            <option value="2" {{ old('sort_order') == 2 ? 'selected' : '' }}>2 - Second</option>
                            <option value="3" {{ old('sort_order') == 3 ? 'selected' : '' }}>3 - Third</option>
                            <option value="4" {{ old('sort_order') == 4 ? 'selected' : '' }}>4 - Fourth</option>
                            <option value="5" {{ old('sort_order') == 5 ? 'selected' : '' }}>5 - Fifth</option>
                        </select>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- English Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.description') }}
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('description') border-red-500 @enderror"
                                  placeholder="{{ __('admin.enter_highlight_description') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Arabic Description -->
                    <div class="md:col-span-2">
                        <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.description_ar') }}
                        </label>
                        <textarea id="description_ar" 
                                  name="description_ar" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('description_ar') border-red-500 @enderror"
                                  placeholder="{{ __('admin.enter_highlight_description_ar') }}">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                {{ __('admin.is_active') }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.highlights.index') }}" class="btn btn-secondary">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <span>{{ __('admin.create_highlight') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
