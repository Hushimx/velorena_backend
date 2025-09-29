<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.admin_login') }} - {{ __('Qaads') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        /* Brand Colors */
        :root {
            --brand-yellow: #ffde9f;
            --brand-yellow-dark: #f5d182;
            --brand-brown: #2a1e1e;
            --brand-brown-light: #3a2e2e;
            --brand-yellow-light: #fff4e6;
        }

        .brand-btn {
            background: var(--brand-brown);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .brand-btn:hover {
            background: var(--brand-brown-light);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(42, 30, 30, 0.15);
        }

        .brand-btn:active {
            transform: translateY(0);
        }

        .input-focus {
            transition: all 0.3s ease;
            border: 1.5px solid #e5e7eb;
        }

        .input-focus:focus {
            border-color: var(--brand-yellow-dark);
            box-shadow: 0 0 0 3px rgba(255, 222, 159, 0.1);
            transform: translateY(-1px);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .language-btn {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .language-btn:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <!-- Language Switcher -->
    <div class="absolute top-6 {{ app()->getLocale() === 'ar' ? 'left-6' : 'right-6' }} z-10">
        @php
            use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
            $current = LaravelLocalization::getCurrentLocale();
        @endphp

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" type="button"
                class="language-btn inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium text-gray-700 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <span class="text-xs rounded-full px-2 py-1 bg-white/60 font-semibold">{{ strtoupper($current) }}</span>
                <span class="hidden sm:inline font-medium">{{ $current === 'ar' ? 'العربية' : 'English' }}</span>
                <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>

            <div x-cloak x-show="open" @click.outside="open = false"
                class="absolute z-50 mt-2 w-40 rounded-xl border border-white/20 bg-white/95 backdrop-blur-sm shadow-xl"
                :class="{ '{{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }}': true }">
                <ul class="py-2">
                    @php
                        $supportedLocales = [
                            'ar' => ['name' => 'Arabic', 'native' => 'العربية'],
                            'en' => ['name' => 'English', 'native' => 'English']
                        ];
                        $current = app()->getLocale();
                    @endphp
                    @foreach ($supportedLocales as $localeCode => $properties)
                        @php
                            $url = route('lang.switch', ['locale' => $localeCode]);
                            $active = $localeCode === $current;
                        @endphp
                        <li>
                            <a href="{{ $url }}" hreflang="{{ $localeCode }}"
                                class="flex items-center justify-between px-4 py-2 text-sm hover:bg-yellow-50 transition-colors {{ $active ? 'font-semibold text-yellow-800 bg-yellow-50' : 'text-gray-700' }}">
                                <span class="flex items-center gap-2">
                                    <span class="text-xs rounded px-2 py-0.5 border font-medium">{{ strtoupper($localeCode) }}</span>
                                    <span>{{ $properties['native'] ?? strtoupper($localeCode) }}</span>
                                </span>
                                @if ($active)
                                    <i class="fas fa-check text-xs text-yellow-600"></i>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <!-- Logo and Header -->
            <div class="text-center mb-10">
                <div class="w-24 h-24 mx-auto mb-6 icon-glow rounded-2xl flex items-center justify-center  p-2">
                    <img src="{{ asset('assets/imgs/logo.png') }}" alt="{{ __('Qaads') }}" class="w-full h-full object-contain">
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ __('auth.admin_panel') }}</h2>
                <p class="text-gray-600 font-medium">{{ __('auth.enter_credentials_admin_dashboard') }}</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white/80 backdrop-blur-sm border border-white/20 rounded-3xl p-8 shadow-xl card-hover">
                <form class="space-y-6" method="POST" action="{{ route('admin.login') }}">
                    @csrf

                    @if($errors->any() || session('error'))
                        <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-4 py-4 rounded-2xl">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                <div>
                                    <p class="font-semibold mb-1">{{ __('auth.login_error') }}</p>
                                    @if($errors->any())
                                        <ul class="space-y-1">
                                            @foreach($errors->all() as $error)
                                                <li>{{ is_string($error) ? $error : (is_array($error) ? implode(', ', $error) : '') }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if(session('error'))
                                        <p>{{ session('error') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} text-gray-500"></i>
                            {{ __('auth.email_address') }}
                        </label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none text-sm font-medium placeholder-gray-400 bg-white/50"
                            placeholder="{{ __('auth.enter_your_email_address') }}" value="{{ old('email') }}">
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} text-gray-500"></i>
                            {{ __('auth.password') }}
                        </label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none text-sm font-medium placeholder-gray-400 bg-white/50"
                            placeholder="{{ __('auth.enter_your_password') }}">
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                        <label for="remember" class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} block text-sm font-medium text-gray-700">
                            {{ __('auth.remember_me') }}
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="brand-btn w-full text-white py-3 px-6 rounded-xl font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 shadow-lg">
                        <i class="fas fa-sign-in-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('auth.sign_in') }}
                    </button>
                </form>

                <!-- Additional Links -->
                <div class="mt-8 text-center">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white/80 text-gray-500 font-medium">{{ __('auth.other_portals') }}</span>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('designer.login') }}" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white/50 hover:bg-white/80 transition-all duration-200 hover:shadow-md">
                            <i class="fas fa-palette {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} text-purple-500"></i>
                            {{ __('auth.designer') }}
                        </a>
                        <a href="{{ route('marketer.login') }}" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white/50 hover:bg-white/80 transition-all duration-200 hover:shadow-md">
                            <i class="fas fa-chart-line {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} text-orange-500"></i>
                            {{ __('auth.marketer') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-gray-600 text-sm font-medium">
                    {{ __('auth.copyright', ['year' => date('Y')]) }}
                </p>
            </div>
        </div>
    </div>
</body>

</html>