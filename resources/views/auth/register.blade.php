@extends('components.layout')

@section('pageTitle', __('User Registration - Qaads'))

@section('additionalHead')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
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

        .gradient-bg {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-yellow) 100%);
        }

        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(42, 30, 30, 0.15), 0 10px 10px -5px rgba(255, 222, 159, 0.1);
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(42, 30, 30, 0.2);
            border-color: var(--brand-brown);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-yellow) 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(42, 30, 30, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Override Tailwind focus states for Brand theme */
        .focus\:ring-green-500:focus {
            --tw-ring-color: var(--brand-brown) !important;
        }

        .focus\:border-green-500:focus {
            border-color: var(--brand-brown) !important;
        }

        .form-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, var(--brand-yellow-light) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(42, 30, 30, 0.15);
            box-shadow: 0 25px 50px -12px rgba(42, 30, 30, 0.2);
        }

        /* Override Bootstrap styles for auth forms */
        .auth-form-container {
            position: relative;
            z-index: 10;
        }

        .auth-form-container * {
            box-sizing: border-box !important;
        }

        .auth-form-container input[type="email"],
        .auth-form-container input[type="password"],
        .auth-form-container input[type="text"],
        .auth-form-container input[type="tel"],
        .auth-form-container textarea {
            display: block !important;
            width: 100% !important;
            padding: 0.75rem 1rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            background-color: white !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            color: #111827 !important;
        }

        .auth-form-container input:focus,
        .auth-form-container textarea:focus {
            outline: none !important;
            border-color: var(--brand-brown) !important;
            box-shadow: 0 0 0 3px rgba(42, 30, 30, 0.1) !important;
        }

        .auth-form-container button[type="submit"] {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            padding: 0.75rem 1rem !important;
            border: none !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            color: white !important;
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-yellow) 100%) !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
        }

        .auth-form-container button[type="submit"]:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 10px 25px -5px rgba(42, 30, 30, 0.3) !important;
        }

        .auth-form-container label {
            display: block !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            color: #374151 !important;
            margin-bottom: 0.25rem !important;
        }

        .auth-form-container .text-red-600 {
            color: #dc2626 !important;
            font-size: 0.875rem !important;
            margin-top: 0.25rem !important;
        }

        .auth-form-container .grid {
            display: grid !important;
        }

        .auth-form-container .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        .auth-form-container .gap-4 {
            gap: 1rem !important;
        }
    </style>
@endsection

@section('content')
    <x-navbar />
    
    <div class="min-h-screen flex items-center justify-center p-4 relative"
        style="background: linear-gradient(135deg, var(--brand-yellow-light) 0%, rgba(42, 30, 30, 0.05) 50%, var(--brand-yellow-light) 100%);">
        
        <div class="max-w-2xl w-full space-y-8 p-10 form-card rounded-2xl card-shadow animate-fade-in auth-form-container">
            <div>
                <div class="flex justify-center">

                </div>
                <h2 class="mt-4 text-center text-2xl font-bold text-gray-900 tracking-tight">
                    {{ __('User Registration') }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    {{ __('Join our community and create your account') }}
                </p>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST" x-data="{ clientType: '{{ old('client_type', 'individual') }}' }">
                @csrf
                <div class="space-y-4">
                    <!-- Client Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Account Type') }}</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="client_type" value="individual" x-model="clientType" 
                                    class="sr-only" {{ old('client_type', 'individual') === 'individual' ? 'checked' : '' }}>
                                <div class="p-4 border-2 rounded-lg text-center transition-all duration-200"
                                    :class="clientType === 'individual' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-gray-300'">
                                    <div class="text-sm font-medium text-gray-900">{{ __('Individual') }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ __('Personal Account') }}</div>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="client_type" value="company" x-model="clientType"
                                    class="sr-only" {{ old('client_type') === 'company' ? 'checked' : '' }}>
                                <div class="p-4 border-2 rounded-lg text-center transition-all duration-200"
                                    :class="clientType === 'company' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-gray-300'">
                                    <div class="text-sm font-medium text-gray-900">{{ __('Company') }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ __('Business Account') }}</div>
                                </div>
                            </label>
                        </div>
                        @error('client_type')
                            <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Individual Fields -->
                    <div x-show="clientType === 'individual'">
                        <label for="full_name"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Name') }}</label>
                        <input id="full_name" name="full_name" type="text" required value="{{ old('full_name') }}"
                            class="@error('full_name') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter your full name') }}">
                        @error('full_name')
                            <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Company Fields -->
                    <div x-show="clientType === 'company'" class="space-y-4">
                        <div>
                            <label for="company_name"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Company Name') }}</label>
                            <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}"
                                class="@error('company_name') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                                placeholder="{{ __('Enter company name') }}">
                            @error('company_name')
                                <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_person"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Contact Person') }}</label>
                            <input id="contact_person" name="contact_person" type="text" value="{{ old('contact_person') }}"
                                class="@error('contact_person') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                                placeholder="{{ __('Enter contact person name') }}">
                            @error('contact_person')
                                <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email Address') }}</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="@error('email') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter your email address') }}">
                        @error('email')
                            <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }}</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                            class="@error('phone') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter your phone number') }}">
                        @error('phone')
                            <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Address') }}</label>
                        <input id="address" name="address" type="text" value="{{ old('address') }}"
                            class="@error('address') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter your address') }}">
                        @error('address')
                            <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('City') }}</label>
                        <input id="city" name="city" type="text" value="{{ old('city') }}"
                            class="@error('city') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter city') }}">
                        @error('city')
                            <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Company-specific fields -->
                    <div x-show="clientType === 'company'" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="vat_number"
                                    class="block text-sm font-medium text-gray-700 mb-1">{{ __('VAT Number') }}</label>
                                <input id="vat_number" name="vat_number" type="text" value="{{ old('vat_number') }}"
                                    class="@error('vat_number') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                                    placeholder="{{ __('Enter VAT number') }}">
                                @error('vat_number')
                                    <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <label for="cr_number"
                                    class="block text-sm font-medium text-gray-700 mb-1">{{ __('CR Number') }}</label>
                                <input id="cr_number" name="cr_number" type="text" value="{{ old('cr_number') }}"
                                    class="@error('cr_number') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                                    placeholder="{{ __('Enter CR number') }}">
                                @error('cr_number')
                                    <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Notes') }}</label>
                        <textarea id="notes" name="notes" rows="3" value="{{ old('notes') }}"
                            class="@error('notes') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Any additional notes or requirements') }}"></textarea>
                        @error('notes')
                            <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
                            <input id="password" name="password" type="password" required
                                class="@error('password') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                                placeholder="{{ __('Enter your password') }}">
                            @error('password')
                                <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="password-confirm"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" name="password_confirmation" type="password" required
                                class="input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                                placeholder="{{ __('Re-enter your password') }}">
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <div class="text-red-700 text-sm">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <button type="submit"
                        class="btn-primary group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out transform hover:scale-[1.02]">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-white group-hover:text-white" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                            </svg>
                        </span>
                        {{ __('Create Account') }}
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    {{ __('Already have an account?') }}
                    <a href="{{ route('login') }}"
                        class="font-medium text-yellow-600 hover:text-yellow-500 transition-colors">
                        {{ __('Login') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection