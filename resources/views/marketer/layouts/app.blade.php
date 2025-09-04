<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pageTitle', __('marketer.marketer_panel'))</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    @livewireStyles

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        
        [x-cloak] { display: none !important; }
        
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        
        .hover-lift {
            transition: transform 0.2s ease-in-out;
        }
        
        .hover-lift:hover {
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-white shadow-xl border-r border-gray-100 sidebar-transition">
            <!-- Logo Section -->
            <div class="p-8 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ __('marketer.marketer_panel') }}</h2>
                        <p class="text-sm text-gray-500">{{ __('marketer.manage_your_assigned_leads') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-8 px-6">
                <div class="space-y-3">
                    <a href="{{ route('marketer.dashboard') }}" 
                        class="group flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('marketer.dashboard') ? 'bg-blue-100 text-blue-700 shadow-sm' : '' }}">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center ml-3 transition-colors duration-200 {{ request()->routeIs('marketer.dashboard') ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-blue-500 group-hover:text-white' }}">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div>
                            <span class="font-medium">{{ __('marketer.dashboard') }}</span>
                            <p class="text-xs text-gray-500 group-hover:text-blue-500">{{ __('marketer.dashboard') }}</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('marketer.leads.index') }}" 
                        class="group flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('marketer.leads.*') ? 'bg-blue-100 text-blue-700 shadow-sm' : '' }}">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center ml-3 transition-colors duration-200 {{ request()->routeIs('marketer.leads.*') ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-blue-500 group-hover:text-white' }}">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <span class="font-medium">{{ __('marketer.leads') }}</span>
                            <p class="text-xs text-gray-500 group-hover:text-blue-500">{{ __('marketer.manage_your_assigned_leads') }}</p>
                        </div>
                    </a>
                </div>
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 w-72 p-6 border-t border-gray-100 bg-gray-50">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                        {{ substr(auth()->guard('marketer')->user()->name, 0, 1) }}
                    </div>
                    <div class="mr-3">
                        <p class="font-medium text-gray-900">{{ auth()->guard('marketer')->user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ __('marketer.marketer') }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('marketer.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                        <i class="fas fa-sign-out-alt ml-2"></i>
                        {{ __('marketer.logout') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">@yield('title', __('marketer.marketer_panel'))</h1>
                            <p class="text-gray-600 mt-1">{{ __('marketer.manage_your_assigned_leads') }}</p>
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            <!-- Language Switcher -->
                            @include('marketer.layouts.includes.lang-switcher')
                            
                            <!-- Date & Time -->
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ __('marketer.date_and_time') }}</p>
                                <p class="font-semibold text-gray-800">{{ now()->format('Y-m-d H:i') }}</p>
                            </div>
                            
                            <!-- Profile Icon -->
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 rounded-xl shadow-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 ml-3"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 rounded-xl shadow-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 ml-3"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>
