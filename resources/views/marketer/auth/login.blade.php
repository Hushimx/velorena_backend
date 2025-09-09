<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('marketer.marketer_login') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-3xl flex items-center justify-center floating shadow-2xl">
                <i class="fas fa-chart-line text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold gradient-text mb-2">{{ __('marketer.marketer_panel') }}</h1>
            <p class="text-gray-600 text-lg">{{ __('marketer.manage_your_assigned_leads') }}</p>
        </div>
        
        <!-- Login Form -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('marketer.sign_in') }}</h2>
                <p class="text-gray-600">{{ __('marketer.manage_your_assigned_leads') }}</p>
            </div>
            
            <form class="space-y-6" method="POST" action="{{ route('marketer.login') }}">
                @csrf
                
                @if($errors->any())
                    <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-4 py-4 rounded-2xl">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 ml-3"></i>
                            <div>
                                <p class="font-semibold">{{ __('marketer.login_error') }}</p>
                                <ul class="list-disc list-inside mt-1 text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope ml-2"></i>
                        {{ __('marketer.email') }}
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="w-full px-4 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-lg"
                        placeholder="{{ __('marketer.enter_your_email') }}" value="{{ old('email') }}">
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock ml-2"></i>
                        {{ __('marketer.password') }}
                    </label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="w-full px-4 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-lg"
                        placeholder="{{ __('marketer.enter_your_password') }}">
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded-lg">
                        <label for="remember" class="mr-3 block text-sm font-medium text-gray-700">
                            {{ __('marketer.remember_me') }}
                        </label>
                    </div>

                    <a href="{{ route('marketer.password.request') }}" 
                        class="text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors duration-300">
                        {{ __('marketer.forgot_password') }}
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-2xl font-semibold text-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-sign-in-alt ml-2"></i>
                    {{ __('marketer.sign_in') }}
                </button>
            </form>

            <!-- Admin Login Link -->
            <div class="mt-8 text-center">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">{{ __('marketer.or') }}</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.login') }}" 
                        class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-2xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300 hover:shadow-lg">
                        <i class="fas fa-user-shield ml-2"></i>
                        {{ __('marketer.admin_login') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white/80 text-sm">
                {{ __('marketer.copyright') }}
            </p>
        </div>
    </div>

    <!-- Background Decorative Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white/5 rounded-full floating"></div>
        <div class="absolute bottom-1/4 right-1/4 w-48 h-48 bg-white/5 rounded-full floating" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 right-1/3 w-32 h-32 bg-white/5 rounded-full floating" style="animation-delay: 2s;"></div>
    </div>
</body>

</html>
