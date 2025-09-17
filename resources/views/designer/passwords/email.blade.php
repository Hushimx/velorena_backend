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
                    <p class="mt-1" style="color: #ffde9f;">Enter your email to receive a password reset link</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-lock text-4xl" style="color: #ffde9f;"></i>
                </div>
            </div>
        </div>

        <!-- Reset Form -->
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3 mb-6">
                        <div class="p-2 rounded-full bg-green-100">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-green-800 font-medium">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('designer.password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" type="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            placeholder="Enter your email address">

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full px-4 py-3 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
                            style="background-color: #2a1e1e;">
                            <i class="fas fa-paper-plane mr-2"></i>
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
