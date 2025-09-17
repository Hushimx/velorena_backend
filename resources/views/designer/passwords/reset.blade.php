@extends('designer.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">
                        <i class="fas fa-key me-2" style="color: #ffde9f;"></i>
                        {{ __('Reset Password') }}
                    </h1>
                    <p class="mt-1" style="color: #ffde9f;">Enter your new password below</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-lock text-4xl" style="color: #ffde9f;"></i>
                </div>
            </div>
        </div>

        <!-- Reset Form -->
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <form method="POST" action="{{ route('designer.password.update') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" type="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                            placeholder="Enter your email address">

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Password') }}
                        </label>
                        <input id="password" type="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            name="password" required autocomplete="new-password" placeholder="Enter your new password">

                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Confirm Password') }}
                        </label>
                        <input id="password-confirm" type="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Confirm your new password">
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full px-4 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                            style="background-color: #2a1e1e;">
                            <i class="fas fa-save mr-2"></i>
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
