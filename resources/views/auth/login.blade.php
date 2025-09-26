@extends('components.layout')

@section('pageTitle', __('User Login - Qaads'))

@section('additionalHead')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Brand Theme - Yellow & Brown */
        :root {
            --brand-yellow: #ffde9f;
            --brand-yellow-dark: #f5d182;
            --brand-brown: #2a1e1e;
            --brand-brown-light: #3a2e2e;
            --brand-yellow-light: #fff4e6;
            --brand-yellow-hover: #f0d4a0;
            --brand-brown-dark: #1a1414;
            --brand-brown-hover: #4a3e3e;
        }

        .tab-active {
            background: var(--brand-brown);
            color: white;
        }

        .tab-inactive {
            background: #f3f4f6;
            color: #6b7280;
        }

        .tab-inactive:hover {
            background: #e5e7eb;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            z-index: 10;
        }

        .input-field {
            padding-left: 40px;
            padding-right: 16px;
        }

        /* Input with eye toggle (password fields) */
        .input-field.pr-12 {
            padding-right: 50px;
        }

        .btn-primary {
            background: var(--brand-brown);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--brand-brown-dark);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(42, 30, 30, 0.3);
        }

        .illustration-bg {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, rgba(255, 222, 159, 0.1) 100%);
        }

        .floating-shape {
            animation: float 6s ease-in-out infinite;
        }

        .floating-shape:nth-child(2) {
            animation-delay: -2s;
        }

        .floating-shape:nth-child(3) {
            animation-delay: -4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Enhanced Form Styles */
        .form-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .form-input:focus-within {
            transform: translateY(-2px);
        }

        .form-input:focus-within .input-icon {
            color: var(--brand-brown);
            transform: translateY(-50%) scale(1.1);
        }

        .form-input:focus-within input {
            border-color: var(--brand-brown);
            box-shadow: 0 0 0 3px rgba(42, 30, 30, 0.1);
        }

        .form-input input:valid {
            border-color: #10b981;
        }

        .form-input input:valid + .input-icon {
            color: #10b981;
        }

        .loading-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .success-checkmark {
            animation: checkmark 0.6s ease-in-out;
        }

        @keyframes checkmark {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-weak { background: #ef4444; width: 25%; }
        .strength-fair { background: #f59e0b; width: 50%; }
        .strength-good { background: #10b981; width: 75%; }
        .strength-strong { background: #059669; width: 100%; }

        .floating-label {
            position: absolute;
            left: 40px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            transition: all 0.3s ease;
            pointer-events: none;
            background: white;
            padding: 0 4px;
            z-index: 5;
        }

        .form-input:focus-within .floating-label,
        .form-input input:not(:placeholder-shown) + .floating-label,
        .form-input input:focus + .floating-label {
            top: 0;
            font-size: 0.75rem;
            color: var(--brand-brown);
            font-weight: 500;
        }

        /* Placeholder animation when input has value */
        .form-input input:not(:placeholder-shown) + .floating-label {
            animation: labelFloat 0.3s ease-out;
        }

        @keyframes labelFloat {
            from {
                top: 50%;
                transform: translateY(-50%);
                font-size: 1rem;
            }
            to {
                top: 0;
                transform: translateY(0);
                font-size: 0.75rem;
            }
        }

        /* RTL Support */
        [dir="rtl"] .floating-label {
            left: auto;
            right: 40px;
        }

        [dir="rtl"] .input-icon {
            left: auto;
            right: 12px;
        }

        [dir="rtl"] .input-field {
            padding-left: 16px;
            padding-right: 40px;
        }

        [dir="rtl"] .input-field.pr-12 {
            padding-left: 50px;
            padding-right: 40px;
        }

        [dir="rtl"] .eye-toggle {
            left: 12px;
            right: auto;
        }

        /* RTL Tab Support */
        [dir="rtl"] .tab-icon {
            margin-left: 0.5rem;
            margin-right: 0;
        }

        /* RTL Grid Support */
        [dir="rtl"] .grid-cols-2 {
            direction: rtl;
        }

        /* RTL Button Support */
        [dir="rtl"] .btn-icon {
            margin-left: 0.5rem;
            margin-right: 0;
        }

        .eye-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
            transition: color 0.3s ease;
        }

        .eye-toggle:hover {
            color: var(--brand-brown);
        }

        .form-step {
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
        }

        .form-step.active {
            opacity: 1;
            transform: translateX(0);
        }

        .progress-bar {
            height: 3px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--brand-brown), var(--brand-yellow));
            transition: width 0.3s ease;
        }
    </style>
@endsection

@section('content')
    <x-navbar />
    
    <div class="min-h-screen flex" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" x-data="{ 
        activeTab: 'login', 
        isLoading: false, 
        showPassword: false,
        showConfirmPassword: false,
        clientType: '{{ old('client_type', 'individual') }}',
        email: '',
        password: '',
        errors: {}
    }">
        <!-- Left Side - Form Section -->
        <div class="w-full lg:w-2/5 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md">
                <!-- Brand Logo -->
                <div class="flex items-center justify-center mb-8">
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-gray-900">Qaads</h1>
                        <p class="text-sm text-gray-500">Smart Solutions</p>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="flex bg-gray-100 rounded-lg p-1 mb-8">
                    <button @click="activeTab = 'login'" 
                        :class="activeTab === 'login' ? 'tab-active' : 'tab-inactive'"
                        class="flex-1 flex items-center justify-center py-3 px-4 rounded-md text-sm font-medium transition-all duration-200">
                        <svg class="w-4 h-4 mr-2 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Login
                    </button>
                    <button @click="activeTab = 'register'" 
                        :class="activeTab === 'register' ? 'tab-active' : 'tab-inactive'"
                        class="flex-1 flex items-center justify-center py-3 px-4 rounded-md text-sm font-medium transition-all duration-200">
                        <svg class="w-4 h-4 mr-2 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Sign Up
                    </button>
            </div>

                <!-- Login Form -->
                <div x-show="activeTab === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <form action="{{ route('login') }}" method="POST" class="space-y-6" @submit="isLoading = true">
                @csrf
                        
                        <!-- Email Field -->
                        <div class="form-input">
                            <div class="relative">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                                <input type="email" name="email" required value="{{ old('email') }}" x-model="email"
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('email') border-red-500 @enderror"
                                    placeholder=" ">
                                <label class="floating-label">Email Address</label>
                            </div>
                        @error('email')
                                <p class="text-red-600 text-sm mt-1 animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                        <!-- Password Field -->
                        <div class="form-input">
                            <div class="relative">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required x-model="password"
                                    class="input-field w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('password') border-red-500 @enderror"
                                    placeholder=" ">
                                <label class="floating-label">Password</label>
                                <button type="button" @click="showPassword = !showPassword" class="eye-toggle">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                                    </svg>
                                </button>
                            </div>
                        @error('password')
                                <p class="text-red-600 text-sm mt-1 animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="remember" class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500 transition-all duration-200">
                                <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-800 transition-colors">Remember Me</span>
                            </label>
                            <a href="#" class="text-sm text-yellow-600 hover:text-yellow-500 transition-colors font-medium">Forgot your password?</a>
                </div>

                        <!-- Error Messages -->
                @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shake">
                        <div class="text-red-700 text-sm">
                            @foreach ($errors->all() as $error)
                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $error }}
                                        </p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (session('error'))
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shake">
                                <div class="text-red-700 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                        <!-- Login Button -->
                        <button type="submit" :disabled="isLoading" 
                            class="btn-primary w-full py-3 px-4 rounded-lg text-white font-medium text-sm transition-all duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="isLoading" class="loading-spinner w-5 h-5 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <svg x-show="!isLoading" class="w-5 h-5 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span x-text="isLoading ? 'Signing In...' : 'LOGIN'"></span>
                    </button>
                    </form>
                </div>

                <!-- Register Form -->
                <div x-show="activeTab === 'register'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <form action="{{ route('register') }}" method="POST" class="space-y-6" @submit="isLoading = true">
                        @csrf
                        
                        <!-- Client Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Account Type</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="p-4 border-2 rounded-lg text-center cursor-pointer transition-all duration-200"
                                    :class="clientType === 'individual' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="client_type" value="individual" x-model="clientType" class="sr-only">
                                    <div class="text-sm font-medium text-gray-900">Individual</div>
                                    <div class="text-xs text-gray-500 mt-1">Personal Account</div>
                                </label>
                                <label class="p-4 border-2 rounded-lg text-center cursor-pointer transition-all duration-200"
                                    :class="clientType === 'company' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="client_type" value="company" x-model="clientType" class="sr-only">
                                    <div class="text-sm font-medium text-gray-900">Company</div>
                                    <div class="text-xs text-gray-500 mt-1">Business Account</div>
                                </label>
                            </div>
                            @error('client_type')
                                <p class="text-red-600 text-sm mt-1 animate-pulse">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Individual Fields -->
                        <div x-show="clientType === 'individual'">
                            <div class="form-input">
                                <div class="relative">
                                    <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <input type="text" name="full_name" required value="{{ old('full_name') }}"
                                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('full_name') border-red-500 @enderror"
                                        placeholder=" ">
                                    <label class="floating-label">Full Name</label>
                                </div>
                                @error('full_name')
                                    <p class="text-red-600 text-sm mt-1 animate-pulse">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Company Fields -->
                        <div x-show="clientType === 'company'" class="space-y-4">
                            <div class="form-input">
                                <div class="relative">
                                    <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <input type="text" name="company_name" value="{{ old('company_name') }}"
                                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('company_name') border-red-500 @enderror"
                                        placeholder=" ">
                                    <label class="floating-label">Company Name</label>
                                </div>
                                @error('company_name')
                                    <p class="text-red-600 text-sm mt-1 animate-pulse">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-input">
                                <div class="relative">
                                    <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('contact_person') border-red-500 @enderror"
                                        placeholder=" ">
                                    <label class="floating-label">Contact Person</label>
                                </div>
                                @error('contact_person')
                                    <p class="text-red-600 text-sm mt-1 animate-pulse">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="form-input">
                            <div class="relative">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                                <input type="email" name="email" required value="{{ old('email') }}"
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('email') border-red-500 @enderror"
                                    placeholder=" ">
                                <label class="floating-label">Email Address</label>
                            </div>
                            @error('email')
                                <p class="text-red-600 text-sm mt-1 animate-pulse">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div class="form-input">
                            <div class="relative">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('phone') border-red-500 @enderror"
                                    placeholder=" ">
                                <label class="floating-label">Phone Number</label>
                            </div>
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1 animate-pulse">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-input">
                                <div class="relative">
                                    <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <input :type="showPassword ? 'text' : 'password'" name="password" required
                                        class="input-field w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('password') border-red-500 @enderror"
                                        placeholder=" ">
                                    <label class="floating-label">Password</label>
                                    <button type="button" @click="showPassword = !showPassword" class="eye-toggle">
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-red-600 text-sm mt-1 animate-pulse">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-input">
                                <div class="relative">
                                    <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required
                                        class="input-field w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none transition duration-200"
                                        placeholder=" ">
                                    <label class="floating-label">Confirm Password</label>
                                    <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="eye-toggle">
                                        <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shake">
                                <div class="text-red-700 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $error }}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Register Button -->
                        <button type="submit" :disabled="isLoading" 
                            class="btn-primary w-full py-3 px-4 rounded-lg text-white font-medium text-sm transition-all duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="isLoading" class="loading-spinner w-5 h-5 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <svg x-show="!isLoading" class="w-5 h-5 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            <span x-text="isLoading ? 'Creating Account...' : 'CREATE ACCOUNT'"></span>
                        </button>
            </form>
                </div>
            </div>
        </div>

        <!-- Right Side - Illustration Section -->
        <div class="hidden lg:flex lg:w-3/5 illustration-bg items-center justify-center p-12">
            <div class="max-w-lg text-center">
                <!-- Illustration -->
                <div class="relative mb-8">
                    <!-- Floating shapes -->
                    <div class="absolute top-0 left-0 w-16 h-16 bg-yellow-200 rounded-full opacity-60 floating-shape"></div>
                    <div class="absolute top-20 right-0 w-12 h-12 bg-yellow-300 rounded-full opacity-40 floating-shape"></div>
                    <div class="absolute bottom-0 left-1/4 w-20 h-20 bg-yellow-100 rounded-full opacity-50 floating-shape"></div>
                    
                    <!-- Main illustration -->
                    <div class="relative z-10">
                        <svg class="w-80 h-80 mx-auto" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- People figures -->
                            <circle cx="100" cy="120" r="25" fill="#fbbf24" stroke="#f59e0b" stroke-width="2"/>
                            <rect x="85" y="145" width="30" height="40" rx="15" fill="#fbbf24" stroke="#f59e0b" stroke-width="2"/>
                            <path d="M70 185 Q100 200 130 185" stroke="#f59e0b" stroke-width="2" fill="none"/>
                            
                            <circle cx="300" cy="100" r="25" fill="#fbbf24" stroke="#f59e0b" stroke-width="2"/>
                            <rect x="285" y="125" width="30" height="40" rx="15" fill="#fbbf24" stroke="#f59e0b" stroke-width="2"/>
                            <path d="M270 165 Q300 180 330 165" stroke="#f59e0b" stroke-width="2" fill="none"/>
                            
                            <circle cx="200" cy="200" r="25" fill="#fbbf24" stroke="#f59e0b" stroke-width="2"/>
                            <rect x="185" y="225" width="30" height="40" rx="15" fill="#fbbf24" stroke="#f59e0b" stroke-width="2"/>
                            <path d="M170 265 Q200 280 230 265" stroke="#f59e0b" stroke-width="2" fill="none"/>
                            
                            <!-- Communication elements -->
                            <circle cx="150" cy="80" r="15" fill="#fbbf24" opacity="0.7"/>
                            <path d="M135 80 Q150 60 165 80" stroke="#f59e0b" stroke-width="2" fill="none"/>
                            
                            <rect x="250" y="60" width="30" height="20" rx="10" fill="#fbbf24" opacity="0.7"/>
                            <circle cx="255" cy="70" r="2" fill="#f59e0b"/>
                            <circle cx="265" cy="70" r="2" fill="#f59e0b"/>
                            <circle cx="275" cy="70" r="2" fill="#f59e0b"/>
                            
                            <rect x="170" y="150" width="25" height="15" rx="7" fill="#fbbf24" opacity="0.7"/>
                            <path d="M182 150 Q195 140 208 150" stroke="#f59e0b" stroke-width="2" fill="none"/>
                            
                            <!-- Connection lines -->
                            <path d="M125 120 Q200 100 275 100" stroke="#f59e0b" stroke-width="1" fill="none" stroke-dasharray="5,5" opacity="0.5"/>
                            <path d="M125 145 Q200 200 175 225" stroke="#f59e0b" stroke-width="1" fill="none" stroke-dasharray="5,5" opacity="0.5"/>
                            <path d="M275 125 Q200 200 225 225" stroke="#f59e0b" stroke-width="1" fill="none" stroke-dasharray="5,5" opacity="0.5"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Marketing Text -->
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fast, Efficient And Productive</h2>
                <p class="text-lg text-gray-600 leading-relaxed">
                    There is a solution that supports you make the most out of your social media marketing campaigns and manage them with finesse. Our platform can help simplify your work as well as improve your efficiency.
                </p>
            </div>
        </div>
    </div>
@endsection