<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('User Registration - velorena') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
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
    <div class="min-h-screen flex items-center justify-center p-4 relative">
        <!-- Language Switcher -->
        <div class="absolute top-4 right-4 z-10">
            @php
                use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
                $current = LaravelLocalization::getCurrentLocale();
            @endphp

            <div x-data="{ open: false }" class="relative">
                <!-- Trigger Button -->
                <button @click="open = !open" type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-medium shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    aria-haspopup="listbox" :aria-expanded="open">
                    <span class="inline-flex items-center gap-2">
                        <span class="text-xs rounded px-2 py-0.5 border">{{ strtoupper($current) }}</span>
                        <span class="hidden sm:inline">
                            {{ $current === 'ar' ? 'العربية' : 'English' }}
                        </span>
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-cloak x-show="open" @click.outside="open = false"
                    class="absolute z-50 mt-2 w-40 rounded-xl border border-gray-200 bg-white shadow-lg"
                    :class="{
                        'right-0': '{{ LaravelLocalization::getCurrentLocaleDirection() }}'
                        === 'rtl',
                        'left-0': '{{ LaravelLocalization::getCurrentLocaleDirection() }}'
                        === 'ltr'
                    }">
                    <ul class="py-1" role="listbox">
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            @php
                                $url = LaravelLocalization::getLocalizedURL($localeCode, null, [], true);
                                $active = $localeCode === $current;
                            @endphp

                            <li>
                                <a href="{{ $url }}" hreflang="{{ $localeCode }}" rel="alternate"
                                    class="flex items-center justify-between px-3 py-2 text-sm hover:bg-gray-50 @if ($active) font-semibold @endif">
                                    <span class="flex items-center gap-2">
                                        <span
                                            class="text-xs rounded px-2 py-0.5 border">{{ strtoupper($localeCode) }}</span>
                                        <span>{{ $properties['native'] ?? strtoupper($localeCode) }}</span>
                                    </span>
                                    @if ($active)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-7.364 7.364a1 1 0 01-1.414 0L3.293 10.435a1 1 0 111.414-1.414l3.222 3.222 6.657-6.657a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="max-w-md w-full space-y-8 p-10 form-card rounded-2xl card-shadow animate-fade-in">
            <div>
                <div class="flex justify-center">
                    <div class="text-center">
                        <h1
                            class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-800 to-green-500 mb-2">
                            {{ __('velorena') }}</h1>
                    </div>
                </div>
                <h2 class="mt-4 text-center text-2xl font-bold text-gray-900 tracking-tight">
                    {{ __('User Registration') }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    {{ __('Join our community and create your account') }}
                </p>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                            class="@error('name') is-invalid @enderror input-focus appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="{{ __('Enter your full name') }}">
                        @error('name')
                            <span class="invalid-feedback text-red-600 text-sm mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
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
                        class="font-medium text-blue-800 hover:text-blue-600 transition-colors">
                        {{ __('Login') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
