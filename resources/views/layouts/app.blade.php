<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pageTitle', trans('dashboard.dashboard'))</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f4f6fb;
        }

        /* Sidebar Styles */
        .sidebar {
            background: #ffffff;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border-left: 1px solid #eee;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            color: #6b7280;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            position: relative;
        }

        .sidebar-link.active,
        .sidebar-link:hover {
            background: #3b82f6;
            color: #fff;
            box-shadow: 0 2px 8px 0 rgba(59, 130, 246, 0.10);
        }

        .sidebar-link i {
            font-size: 1.25rem;
            margin-left: 0.5rem;
        }

        /* RTL Support */
        [dir="rtl"] .sidebar-link i {
            margin-left: 0;
            margin-right: 0.5rem;
        }

        .sidebar .logo {
            margin-bottom: 1.5rem;
        }

        .sidebar .logo img {
            border-radius: 50%;
            box-shadow: 0 2px 8px 0 rgba(59, 130, 246, 0.15);
        }

        .sidebar .platform-title {
            font-size: 1.15rem;
            font-weight: bold;
            color: #1f2937;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .sidebar .logout-btn {
            background: linear-gradient(90deg, #ef4444 0%, #b91c1c 100%);
            color: #fff;
            font-weight: bold;
            transition: background 0.2s;
        }

        .sidebar .logout-btn:hover {
            background: linear-gradient(90deg, #dc2626 0%, #991b1b 100%);
        }

        /* Header Styles */
        header {
            background: #fff;
            box-shadow: 0 2px 8px 0 rgba(31, 38, 135, 0.07);
            border-bottom: 1px solid #e5e7eb;
        }

        .profile-img {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 2px 8px 0 rgba(59, 130, 246, 0.10);
            border: 2px solid #fff;
        }

        .profile-name {
            color: #374151;
            font-weight: 600;
            margin-left: 0.75rem;
        }

        /* RTL Support for profile name */
        [dir="rtl"] .profile-name {
            margin-left: 0;
            margin-right: 0.75rem;
        }

        .profile-status {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0.75rem;
            height: 0.75rem;
            background: #22c55e;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        /* Main Content */
        main {
            background: #f4f6fb;
            min-height: 100vh;
        }

        /* Responsive Sidebar */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                top: 0;
                right: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(100%);
                transition: transform 0.3s ease-in-out;
            }

            /* LTR Support for sidebar */
            [dir="ltr"] .sidebar {
                right: auto;
                left: 0;
                transform: translateX(-100%);
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            [dir="ltr"] .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease-in-out;
            }

            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }
        }

        @media (max-width: 768px) {
            main {
                border-radius: 0;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Animation */
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Simple Toggle Switch */
        .toggle-switch {
            appearance: none;
            width: 50px;
            height: 24px;
            background-color: #d1d5db;
            border-radius: 12px;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .toggle-switch:checked {
            background-color: #3b82f6;
        }

        .toggle-switch::before {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: white;
            top: 3px;
            left: 3px;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch:checked::before {
            transform: translateX(26px);
        }

        .toggle-switch:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        /* Form Input Focus Effects */
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* File Upload Area Hover Effect */
        .file-upload-area:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar w-64 min-h-screen flex flex-col p-0">
            <div class="logo flex flex-col items-center py-8 border-b border-gray-200 relative">
                <img src="https://ui-avatars.com/api/?name=User&background=3b82f6&color=fff&rounded=true&size=64"
                    alt="Logo" class="w-16 h-16 mb-2">
                <div class="platform-title">{{ trans('sidebar.user_dashboard') }}</div>
                <button id="closeSidebar"
                    class="lg:hidden text-gray-300 hover:text-white transition-colors absolute top-4 left-4"
                    onclick="toggleSidebar()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="mt-4 flex-1 px-2 space-y-1">
                <a href="{{ url('/') }}" class="sidebar-link {{ request()->routeIs('/') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>{{ trans('sidebar.main') }}</span>
                </a>

                <a href="{{ route('appointments.index') }}"
                    class="sidebar-link {{ request()->routeIs('appointments.index') || request()->routeIs('appointments.show') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ trans('sidebar.my_appointments') }}</span>
                </a>

                <a href="{{ route('appointments.create') }}"
                    class="sidebar-link {{ request()->routeIs('appointments.create') ? 'active' : '' }}">
                    <i class="fas fa-calendar-plus"></i>
                    <span>{{ trans('sidebar.book_appointment') }}</span>
                </a>

                <a href="{{ route('user.products.index') }}"
                    class="sidebar-link {{ request()->routeIs('user.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>{{ trans('sidebar.products') }}</span>
                </a>

                <a href="{{ route('user.orders.index') }}"
                    class="sidebar-link {{ request()->routeIs('user.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ trans('orders.my_orders') }}</span>
                </a>
            </nav>
            <div class="mt-auto mb-4 px-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="sidebar-link logout-btn w-full flex items-center justify-center gap-3">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ trans('sidebar.logout') }}</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="shadow-lg">
                <div class="px-4 lg:px-8 py-4 flex justify-between items-center">
                    <!-- Mobile menu button -->
                    <button id="menuButton" class="lg:hidden text-gray-600 hover:text-gray-800 transition-colors"
                        onclick="toggleSidebar()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <h1 class="text-xl font-bold text-gray-800">@yield('title', trans('dashboard.dashboard'))</h1>

                    <div class="flex items-center gap-3">
                        <!-- Quick Orders Access -->
                        <a href="{{ route('user.orders.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-medium shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-shopping-cart text-blue-600"></i>
                            <span class="hidden sm:inline">{{ trans('orders.my_orders') }}</span>
                        </a>

                        <!-- Language Switcher -->
                        @php
                            use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
                            $current = LaravelLocalization::getCurrentLocale();
                        @endphp
                        <div x-data="{ open: false }" class="relative">
                            <!-- Trigger Button -->
                            <button @click="open = !open" type="button"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-medium shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                aria-haspopup="listbox" :aria-expanded="open">
                                <span class="inline-flex items-center gap-2">
                                    <span class="text-xs rounded px-2 py-0.5 border">{{ strtoupper($current) }}</span>
                                    <span class="hidden sm:inline">
                                        {{ $current === 'ar' ? 'العربية' : 'English' }}
                                    </span>
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
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
                                            <a href="{{ $url }}" hreflang="{{ $localeCode }}"
                                                rel="alternate"
                                                class="flex items-center justify-between px-3 py-2 text-sm hover:bg-gray-50 @if ($active) font-semibold @endif">
                                                <span class="flex items-center gap-2">
                                                    <span
                                                        class="text-xs rounded px-2 py-0.5 border">{{ strtoupper($localeCode) }}</span>
                                                    <span>{{ $properties['native'] ?? strtoupper($localeCode) }}</span>
                                                </span>
                                                @if ($active)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        viewBox="0 0 20 20" fill="currentColor">
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

                        <!-- User Profile -->
                        <div x-data="{ open: false }" class="flex items-center relative">
                            <span class="hidden sm:block profile-name">{{ Auth::user()->name }}</span>
                            <div class="relative ml-3">
                                <button @click="open = !open" class="focus:outline-none">
                                    <img class="profile-img"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff"
                                        alt="Profile Picture">
                                    <span class="profile-status"></span>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-cloak x-show="open" @click.outside="open = false"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                    :class="{
                                        'right-0': '{{ LaravelLocalization::getCurrentLocaleDirection() }}'
                                        === 'rtl',
                                        'left-0': '{{ LaravelLocalization::getCurrentLocaleDirection() }}'
                                        === 'ltr'
                                    }">
                                    <a href="{{ route('user.orders.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        {{ trans('orders.my_orders') }}
                                    </a>
                                    <a href="{{ route('appointments.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        {{ trans('sidebar.my_appointments') }}
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>
                                            {{ trans('sidebar.logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-8 pb-24 lg:pb-32">
                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (window.innerWidth <= 1024) {
                sidebar.classList.toggle('sidebar-open');
                overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('sidebar-hidden');
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuButton = document.getElementById('menuButton');
            if (window.innerWidth <= 1024) {
                if (!sidebar.contains(event.target) && !menuButton.contains(event.target) && sidebar.classList
                    .contains('sidebar-open')) {
                    sidebar.classList.remove('sidebar-open');
                    overlay.classList.remove('active');
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('sidebar-open');
                overlay.classList.remove('active');
            } else {
                sidebar.classList.remove('sidebar-hidden');
            }
        });

        // Handle flash messages
        @if (session('success'))
            Swal.fire({
                title: '{{ app()->getLocale() === 'ar' ? 'نجاح!' : 'Success!' }}',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: '{{ app()->getLocale() === 'ar' ? 'حسناً' : 'OK' }}'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: '{{ app()->getLocale() === 'ar' ? 'خطأ!' : 'Error!' }}',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: '{{ app()->getLocale() === 'ar' ? 'حسناً' : 'OK' }}'
            });
        @endif

        @if (session('warning'))
            Swal.fire({
                title: '{{ app()->getLocale() === 'ar' ? 'تحذير!' : 'Warning!' }}',
                text: "{{ session('warning') }}",
                icon: 'warning',
                confirmButtonText: '{{ app()->getLocale() === 'ar' ? 'حسناً' : 'OK' }}'
            });
        @endif

        @if (session('info'))
            Swal.fire({
                title: '{{ app()->getLocale() === 'ar' ? 'معلومات' : 'Information' }}',
                text: "{{ session('info') }}",
                icon: 'info',
                confirmButtonText: '{{ app()->getLocale() === 'ar' ? 'حسناً' : 'OK' }}'
            });
        @endif
    </script>

    <script>
        flatpickr("#flatpickr", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            maxDate: new Date().fp_incr(90),
        });
    </script>

</body>

</html>
