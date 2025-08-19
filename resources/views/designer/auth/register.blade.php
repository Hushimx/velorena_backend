<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب مصمم - Jarak</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
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

        /* Navy Blue and Mint Green Theme - Clean & Focused */
        :root {
            --navy-blue: #000080;
            --mint-green: #98FF98;
            --navy-blue-light: rgba(0, 0, 128, 0.1);
            --mint-green-light: rgba(152, 255, 152, 0.1);
            --navy-blue-dark: #000066;
            --mint-green-dark: #7FFF7F;
        }

        .gradient-bg {
            background: linear-gradient(135deg, var(--navy-blue) 0%, var(--mint-green) 100%);
        }

        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 128, 0.15), 0 10px 10px -5px rgba(152, 255, 152, 0.1);
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 128, 0.2);
            border-color: var(--navy-blue);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--navy-blue) 0%, var(--mint-green) 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 128, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Override Tailwind focus states for Navy Blue theme */
        .focus\:ring-green-500:focus {
            --tw-ring-color: var(--navy-blue) !important;
        }

        .focus\:border-green-500:focus {
            border-color: var(--navy-blue) !important;
        }

        .form-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, var(--mint-green-light) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 128, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 128, 0.2);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50 via-blue-50 to-green-100 min-h-screen"
    style="background: linear-gradient(135deg, var(--mint-green-light) 0%, rgba(0, 0, 128, 0.05) 50%, var(--mint-green-light) 100%);">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-lg w-full space-y-8 p-10 form-card rounded-2xl card-shadow animate-fade-in">
            <div>
                <div class="flex justify-center">
                    <div class="text-center">
                        <h1
                            class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-800 to-green-500 mb-2">
                            Jarak</h1>
                        <p class="text-sm text-gray-500">منصة الإيجار الرائدة</p>
                    </div>
                </div>
                <h2 class="mt-4 text-center text-2xl font-bold text-gray-900 tracking-tight">
                    {{ __('Create Designer Account') }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    {{ __('Join the designer community and showcase your work') }}
                </p>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('designer.register') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                            class="@error('name') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter your full name') }}">
                        @error('name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email Address') }}</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="@error('email') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter your email address') }}">
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="phone"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }}</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                            class="@error('phone') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter your phone number (optional)') }}">
                        @error('phone')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="address"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Address') }}</label>
                        <textarea id="address" name="address" rows="2"
                            class="@error('address') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter your address (optional)') }}">{{ old('address') }}</textarea>
                        @error('address')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="bio"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Bio') }}</label>
                        <textarea id="bio" name="bio" rows="3"
                            class="@error('bio') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Tell us about yourself and your design experience (optional)') }}">{{ old('bio') }}</textarea>
                        @error('bio')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="portfolio_url"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Portfolio URL') }}</label>
                        <input id="portfolio_url" name="portfolio_url" type="url"
                            value="{{ old('portfolio_url') }}"
                            class="@error('portfolio_url') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Your portfolio URL (optional)') }}">
                        @error('portfolio_url')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
                        <input id="password" name="password" type="password" required
                            class="@error('password') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter your password') }}">
                        @error('password')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm Password') }}</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Re-enter your password') }}">
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

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        {{ __('Already have an account?') }}
                        <a href="{{ route('designer.login') }}"
                            class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                            {{ __('Login') }}
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
