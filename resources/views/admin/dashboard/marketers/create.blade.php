@extends('admin.layouts.app')

@section('pageTitle', __('admin.create_marketer'))
@section('title', __('admin.create_marketer'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.create_marketer') }}</h1>
                <p class="text-gray-600">{{ __('admin.add_new_marketer_to_platform') }}</p>
            </div>
            <a href="{{ route('admin.marketers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                <span>{{ __('admin.back_to_list') }}</span>
            </a>
        </div>

        <!-- Create Form -->
        <div class="card">
            <div class="card-body">
            <form action="{{ route('admin.marketers.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="form-label">{{ __('admin.name') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') border-red-500 @enderror"
                            required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="form-label">{{ __('admin.email') }}</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="form-label">{{ __('admin.phone') }}</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                            class="form-control @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="form-label">{{ __('admin.category') }}</label>
                        <select id="category_id" name="category_id"
                            class="form-control @error('category_id') border-red-500 @enderror">
                            <option value="">{{ __('admin.select_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="form-label">{{ __('admin.password') }}</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') border-red-500 @enderror"
                            required>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="form-label">{{ __('admin.confirm_password') }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control @error('password_confirmation') border-red-500 @enderror"
                            required>
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="rounded border-gray-300" style="color: var(--brand-brown);">
                            <span class="mr-2 text-sm text-gray-700">{{ __('admin.active') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('admin.marketers.index') }}" class="btn btn-secondary">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>{{ __('admin.create_marketer') }}</span>
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection
