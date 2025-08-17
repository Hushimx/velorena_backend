<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المقرض</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
            border-radius: 1.5rem 0 0 1.5rem;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 500;
            color: #6b7280;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            position: relative;
        }
        .sidebar-link.active, .sidebar-link:hover {
            background: #059669;
            color: #fff;
            box-shadow: 0 2px 8px 0 rgba(5, 150, 105, 0.10);
        }
        .sidebar-link i {
            font-size: 1.25rem;
            margin-left: 0.5rem;
        }
        .sidebar .logo {
            margin-bottom: 1.5rem;
        }
        .sidebar .logo img {
            border-radius: 50%;
            box-shadow: 0 2px 8px 0 rgba(5, 150, 105, 0.15);
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
            border-radius: 0.75rem;
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
            box-shadow: 0 2px 8px 0 rgba(5, 150, 105, 0.10);
            border: 2px solid #fff;
        }
        .profile-name {
            color: #374151;
            font-weight: 600;
            margin-left: 0.75rem;
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
            border-radius: 1.5rem 0 0 0;
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
            .sidebar.sidebar-open {
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
    </style>
    @livewireStyles
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar w-64 min-h-screen flex flex-col p-0">
            <div class="logo flex flex-col items-center py-8 border-b border-gray-200 relative">
                <img src="https://ui-avatars.com/api/?name=Lender&background=059669&color=fff&rounded=true&size=64" alt="Logo" class="w-16 h-16 mb-2">
                <div class="platform-title">لوحة المقرض</div>
                <button id="closeSidebar" class="lg:hidden text-gray-300 hover:text-white transition-colors absolute top-4 left-4" onclick="toggleSidebar()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="mt-4 flex-1 px-2 space-y-1">
                <a href="{{ route('lender.dashboard') }}" class="sidebar-link {{ request()->routeIs('lender.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>الرئيسية</span>
                </a>
                <a href="{{ route('lender.profile.edit') }}" class="sidebar-link {{ request()->routeIs('lender.profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>الملف الشخصي</span>
                </a>
                <a href="{{ route('lender.listings.index') }}" class="sidebar-link {{ request()->routeIs('lender.listings.*') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>عروضي</span>
                </a>
                <a href="{{ route('lender.orders.index') }}" class="sidebar-link {{ request()->routeIs('lender.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i>
                    <span>الطلبات</span>
                </a>
                <a href="{{ route('lender.chats.index') }}" class="sidebar-link {{ request()->routeIs('lender.chats.*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>الدردشات</span>
                </a>
                <a href="{{ route('lender.reviews.index') }}" class="sidebar-link {{ request()->routeIs('lender.reviews.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>التقييمات</span>
                </a>
                <a href="{{ route('lender.coupons.index') }}" class="sidebar-link {{ request()->routeIs('lender.coupons.*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>الكوبونات</span>
                </a>
            </nav>
            <div class="mt-auto mb-4 px-2">
                <form action="{{ route('lender.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link logout-btn w-full flex items-center justify-center gap-3">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>تسجيل الخروج</span>
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
                    <button id="menuButton" class="lg:hidden text-gray-600 hover:text-gray-800 transition-colors" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">@yield('title', 'الرئيسية')</h1>
                    <div class="flex items-center relative">
                        <span class="hidden sm:block profile-name">{{ Auth::guard('lender')->user()->display_name }}</span>
                        <div class="relative ml-3">
                            @if(Auth::guard('lender')->user()->image)
                                <img class="profile-img" src="{{ Storage::url(Auth::guard('lender')->user()->image) }}" alt="الصورة الشخصية">
                            @else
                                <img class="profile-img" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('lender')->user()->name) }}&background=random" alt="الصورة الشخصية">
                            @endif
                            <span class="profile-status"></span>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
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
                if (!sidebar.contains(event.target) && !menuButton.contains(event.target) && sidebar.classList.contains('sidebar-open')) {
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
        @if(session('success'))
            Swal.fire({
                title: 'نجاح!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'حسناً'
            });
        @endif
        @if(session('error'))
            Swal.fire({
                title: 'خطأ!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'حسناً'
            });
        @endif
    </script>
</body>
</html>

