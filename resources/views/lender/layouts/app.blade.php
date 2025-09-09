<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المقرض</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
            background: rgba(5, 150, 105, 0.1);
            color: #059669;
        }
        
        .tree-children {
            max-height: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(-10px);
        }
        
        .tree-children.expanded {
            max-height: 600px;
            opacity: 1;
            transform: translateY(0);
        }
        
        .tree-child {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2.5rem 0.75rem 1.25rem;
            margin: 0.25rem 1rem 0.25rem 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            position: relative;
            border-left: 3px solid transparent;
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
            background: rgba(5, 150, 105, 0.05);
            color: #059669;
            border-left-color: rgba(5, 150, 105, 0.3);
            transform: translateX(2px);
        }
        
        .tree-child:hover::before {
            background: #059669;
            transform: translateY(-50%) scale(1.2);
        }
        
        .tree-child.active {
            background: rgba(5, 150, 105, 0.1);
            color: #059669;
            border-left-color: #059669;
            font-weight: 600;
        }
        
        .tree-child.active::before {
            background: #059669;
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
        }
        
        
        /* Simplified tree children styling */
        .tree-children {
            margin-top: 0.25rem;
        }
        
        
        /* Improve the overall sidebar appearance */
        .sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-right: 1px solid rgba(229, 231, 235, 0.5);
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
        
        
        /* Select2 Custom Styling - Minimal & Clean */
        .select2-container {
            width: 100% !important;
        }
        
        /* Main selection box */
        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0;
            background: #ffffff;
            transition: all 0.2s ease;
        }
        
        /* Selected text */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            padding-right: 40px;
            padding-left: 12px;
            color: #374151;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
        }
        
        /* Placeholder text */
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af;
            font-size: 14px;
        }
        
        /* Dropdown arrow */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 8px;
            width: 20px;
        }
        
        /* Clear button (X) - Fixed positioning */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            line-height: 16px;
            text-align: center;
            color: #9ca3af;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s ease;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #ef4444;
        }
        
        /* Focus state */
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        
        /* Hover state */
        .select2-container--default .select2-selection--single:hover {
            border-color: #d1d5db;
        }
        
        /* Dropdown */
        .select2-dropdown {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-top: 4px;
        }
        
        /* Dropdown options */
        .select2-container--default .select2-results__option {
            padding: 10px 12px;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            color: #374151;
            transition: background-color 0.15s ease;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #f3f4f6;
            color: #1f2937;
        }
        
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #3b82f6;
            color: white;
        }
        
        /* Search input in dropdown */
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px 12px;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            margin: 8px;
            width: calc(100% - 16px);
        }
        
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        
        /* No results message */
        .select2-container--default .select2-results__message {
            padding: 10px 12px;
            color: #9ca3af;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
        }
        
        /* Loading message */
        .select2-container--default .select2-results__option--loading {
            color: #6b7280;
            font-style: italic;
        }
    </style>
    @livewireStyles
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('lender.layouts.sidebar')
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
                        <div class="relative ml-3">
                            <button id="profileDropdown" class="flex items-center focus:outline-none" onclick="toggleProfileDropdown()">
                                <i class="fas fa-chevron-down text-gray-400 text-xs ml-3"></i>

                                @if(Auth::guard('lender')->user()->image)
                                    <img class="profile-img" src="{{ Storage::url(Auth::guard('lender')->user()->image) }}" alt="الصورة الشخصية">
                                @else
                                    <img class="profile-img" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('lender')->user()->name) }}&background=random" alt="الصورة الشخصية">
                                @endif
                                <span class="hidden sm:block profile-name mr-2">{{ Auth::guard('lender')->user()->display_name }}</span>
                            </button>
                            
                            <!-- Profile Dropdown -->
                            <div id="profileDropdownMenu" class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden">
                                <a href="{{ route('lender.profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-user mr-3 text-gray-400"></i>
                                    <span>الملف الشخصي</span>
                                    @if(Auth::guard('lender')->user()->verification_status !== 'verified')
                                        <span class="mr-auto flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                        </span>
                                    @endif
                                </a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <form action="{{ route('lender.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
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
        
        // Auto-expand tree if current page is a child
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we're on a listings page with status filter
            if (window.location.pathname.includes('/listings') && new URLSearchParams(window.location.search).get('status')) {
                const listingsTree = document.getElementById('listings-tree');
                const listingsToggle = document.getElementById('listings-toggle');
                if (listingsTree && listingsToggle) {
                    listingsTree.classList.add('expanded');
                    listingsToggle.classList.add('expanded');
                }
            }
            
            // Check if we're on an orders page with status filter
            if (window.location.pathname.includes('/orders') && new URLSearchParams(window.location.search).get('status')) {
                const ordersTree = document.getElementById('orders-tree');
                const ordersToggle = document.getElementById('orders-toggle');
                if (ordersTree && ordersToggle) {
                    ordersTree.classList.add('expanded');
                    ordersToggle.classList.add('expanded');
                }
            }
        });
        
        // Initialize Select2 for all city selects
        $(document).ready(function() {
            $('.city-select').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        placeholder: $(this).data('placeholder') || 'ابحث واختر المدينة',
                        allowClear: true,
                        minimumResultsForSearch: 0, // Always show search
                        language: {
                            noResults: function() {
                                return "لا توجد نتائج";
                            },
                            searching: function() {
                                return "جاري البحث...";
                            },
                            inputTooShort: function() {
                                return "يرجى إدخال حرف واحد على الأقل";
                            },
                            inputTooLong: function() {
                                return "يرجى حذف حرف";
                            },
                            loadingMore: function() {
                                return "جاري تحميل المزيد...";
                            },
                            maximumSelected: function() {
                                return "يمكنك اختيار عنصر واحد فقط";
                            },
                            removeAllItems: function() {
                                return "حذف جميع العناصر";
                            },
                            removeItem: function() {
                                return "حذف العنصر";
                            },
                            search: function() {
                                return "بحث";
                            }
                        },
                        width: '100%',
                        dir: 'rtl',
                        theme: 'bootstrap-5',
                        dropdownCssClass: 'select2-dropdown-custom',
                        selectionCssClass: 'select2-selection-custom'
                    });
                }
            });
        });
    </script>
</body>
</html>

