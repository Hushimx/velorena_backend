@extends('admin.layouts.app')

@section('pageTitle', __('admin.add_new_admin'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.add_new_admin') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('admin.create_admin') }}</p>
        </div>
        <a href="{{ route('admin.admins.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            <span>{{ __('admin.back') }}</span>
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.admin_name') }}</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-offset-2 focus:border-transparent @error('name') border-red-500 @enderror"
                           style="focus:ring-color: #2a1e1e;"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.admin_email') }}</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-offset-2 focus:border-transparent @error('email') border-red-500 @enderror"
                           style="focus:ring-color: #2a1e1e;"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.admin_password') }}</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-offset-2 focus:border-transparent @error('password') border-red-500 @enderror"
                           style="focus:ring-color: #2a1e1e;"
                           required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.admin_password_confirmation') }}</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-offset-2 focus:border-transparent"
                           style="focus:ring-color: #2a1e1e;"
                           required>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-4 mt-8">
                <a href="{{ route('admin.admins.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    {{ __('admin.cancel') }}
                </a>
                <button type="submit" class="px-6 py-2 text-white rounded-lg transition-colors duration-200" style="background-color: #2a1e1e;">
                    {{ __('admin.create_admin') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
