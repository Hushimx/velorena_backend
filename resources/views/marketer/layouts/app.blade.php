<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المسوق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Admin Theme CSS -->
    <link href="{{ asset('css/admin-theme.css') }}" rel="stylesheet">
    @livewireStyles

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f4f6fb;
        }
        
        /* Flash Message Animations */
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        /* Sidebar Styles */
        .sidebar {
            background: #2A1E20;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            border-radius: 0 0 0 1.5rem;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 500;
            color: #d1d5db;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            position: relative;
        }
        .sidebar-link.active, .sidebar-link:hover {
            background: #ffde9f;
            color: #2a1e1e;
            box-shadow: 0 2px 8px 0 rgba(255, 222, 159, 0.20);
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
            box-shadow: 0 2px 8px 0 rgba(42, 30, 30, 0.15);
        }
        .sidebar .platform-title {
            font-size: 1.15rem;
            font-weight: bold;
            color: #f9fafb;
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
            background: #2A1E20;
            box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid #4A3E40;
        }
        .profile-img {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 2px 8px 0 rgba(42, 30, 30, 0.10);
            border: 2px solid #fff;
        }
        .profile-name {
            color: #f9fafb;
            font-weight: 600;
            margin-left: 0.75rem;
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
        
        /* Tree Structure Styles */
        .tree-item {
            position: relative;
            margin-bottom: 0.5rem;
        }
        
        .tree-toggle {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #9ca3af;
            font-size: 0.75rem;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(156, 163, 175, 0.1);
        }
        
        .tree-toggle:hover {
            background: rgba(156, 163, 175, 0.2);
            color: #6b7280;
        }
        
        .tree-toggle.expanded {
            transform: translateY(-50%) rotate(90deg);
            background: rgba(42, 30, 30, 0.1);
            color: #2a1e1e;
        }
        
        .tree-children {
            max-height: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(-10px);
            width: 100%;
        }
        
        .tree-children.expanded {
            max-height: 600px;
            opacity: 1;
            transform: translateY(0);
            width: 100%;
        }
        
        .tree-child {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            margin: 0.25rem 0.5rem 0.25rem 0.5rem;
            font-size: 0.875rem;
            color: #d1d5db;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            position: relative;
            border-left: 3px solid transparent;
            width: calc(100% - 1rem);
        }
        
        .tree-child::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            background: #d1d5db;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        
        .tree-child:hover {
            background: rgba(255, 222, 159, 0.1);
            color: #ffde9f;
            border-left-color: rgba(255, 222, 159, 0.3);
            transform: translateX(2px);
        }
        
        .tree-child:hover::before {
            background: #ffde9f;
            transform: translateY(-50%) scale(1.2);
        }
        
        .tree-child.active {
            background: rgba(255, 222, 159, 0.2);
            color: #ffde9f;
            border-left-color: #ffde9f;
            font-weight: 600;
        }
        
        .tree-child.active::before {
            background: #ffde9f;
            transform: translateY(-50%) scale(1.3);
        }
        
        .tree-child i {
            font-size: 0.875rem;
            width: 16px;
            text-align: center;
        }
        
        /* Enhanced main tree links */
        .tree-item > .sidebar-link {
            position: relative;
            padding-left: 3rem; /* Add space for the arrow on the left */
            width: 100%;
        }
        
        /* Simplified tree children styling */
        .tree-children {
            margin-top: 0.25rem;
        }
        
        /* Improve the overall sidebar appearance */
        .sidebar {
            background: linear-gradient(180deg, #2A1E20 0%, #3A2E30 100%);
            border-right: 1px solid rgba(74, 62, 64, 0.5);
        }
        
        /* Remove special styling from tree items to match regular sidebar items */
        .tree-item {
            /* Remove special borders and backgrounds */
        }
        
        /* Enhanced hover effects for all sidebar links */
        .sidebar-link {
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .sidebar-link:hover::before {
            left: 100%;
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('marketer.layouts.includes.sidebar')
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="shadow-lg">
                <div class="px-4 lg:px-8 py-4 flex justify-between items-center">
                    <!-- Mobile menu button -->
                    <button id="menuButton" class="lg:hidden text-gray-300 hover:text-white transition-colors" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-bold text-white">@yield('title', __('marketer.marketer_panel'))</h1>
                    <div class="flex items-center relative gap-4">
                        <!-- Language Switcher -->
                        @include('admin.layouts.includes.lang-switcher')
                        <div class="relative ml-3">
                            <button id="profileDropdown" class="flex items-center focus:outline-none" onclick="toggleProfileDropdown()">
                                <i class="fas fa-chevron-down text-gray-300 text-xs ml-3"></i>
                                <img class="profile-img" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('marketer')->user()->name) }}&background=2a1e1e&color=ffde9f&rounded=true&size=64" alt="الصورة الشخصية">
                                <span class="hidden sm:block profile-name mr-2">{{ Auth::guard('marketer')->user()->name }}</span>
                            </button>
                            
                            <!-- Profile Dropdown -->
                            <div id="profileDropdownMenu" class="absolute left-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-lg border border-gray-600 py-2 z-50 hidden">
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-user mr-3 text-gray-400"></i>
                                    <span>الملف الشخصي</span>
                                </a>
                                <div class="border-t border-gray-600 my-1"></div>
                                <form action="{{ route('marketer.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-400 hover:bg-red-900 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        <span>تسجيل الخروج</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-8 pb-24 lg:pb-32">
                <div class="animate-fade-in">
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
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        
        // Tree toggle functionality
        function toggleTree(event, treeId) {
            event.preventDefault();
            const tree = document.getElementById(treeId);
            const toggle = document.getElementById(treeId.replace('-tree', '-toggle'));
            
            if (tree.classList.contains('expanded')) {
                tree.classList.remove('expanded');
                toggle.classList.remove('expanded');
            } else {
                tree.classList.add('expanded');
                toggle.classList.add('expanded');
            }
        }
        
        // Profile dropdown toggle functionality
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdownMenu');
            dropdown.classList.toggle('hidden');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdownMenu');
            const button = document.getElementById('profileDropdown');
            
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
