<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pageTitle', trans('dashboard.designer_dashboard'))</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    @livewireStyles

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            background: #ffffff;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border-right: 1px solid #dee2e6;
            min-height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-content {
            padding: 1.5rem;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 0.5rem;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: #198754;
            color: #fff;
            box-shadow: 0 2px 8px 0 rgba(25, 135, 84, 0.15);
            text-decoration: none;
        }

        .sidebar-link i {
            font-size: 1.25rem;
            width: 20px;
            text-align: center;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .sidebar .logo img {
            border-radius: 50%;
            box-shadow: 0 2px 8px 0 rgba(25, 135, 84, 0.15);
            margin-bottom: 0.5rem;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #198754, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.2);
            color: white;
        }

        .sidebar .platform-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #212529;
            letter-spacing: 1px;
            margin-bottom: 0.25rem;
        }

        .sidebar .logout-btn {
            background: linear-gradient(90deg, #dc3545 0%, #b02a37 100%);
            color: #fff;
            font-weight: bold;
            transition: background 0.2s;
            border: none;
            margin-top: auto;
        }

        .sidebar .logout-btn:hover {
            background: linear-gradient(90deg, #bb2d3b 0%, #9c1f2a 100%);
            color: #fff;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background: #f8f9fa;
        }

        /* Header Styles */
        .main-header {
            background: #fff;
            box-shadow: 0 2px 8px 0 rgba(31, 38, 135, 0.07);
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 0;
        }

        .profile-img {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 2px 8px 0 rgba(25, 135, 84, 0.10);
            border: 2px solid #fff;
            background: #f8f9fa;
        }



        /* Language Switcher */
        .language-switcher .dropdown-toggle {
            border-color: #dee2e6;
            background: #fff;
            color: #6c757d;
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        .language-switcher .dropdown-toggle:hover {
            border-color: #198754;
            color: #198754;
        }

        .language-switcher .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }

        .language-switcher .dropdown-item {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .language-switcher .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .language-switcher .dropdown-item.active {
            background-color: #198754;
            color: #fff;
        }

        .profile-name {
            font-weight: 600;
            color: #212529;
        }

        .profile-role {
            font-size: 0.875rem;
            color: #6c757d;
        }

        /* Card Enhancements */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: box-shadow 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        /* Custom Button Styles */
        .btn-primary {
            background: #198754;
            border-color: #198754;
        }

        .btn-primary:hover {
            background: #157347;
            border-color: #157347;
        }

        .btn-success {
            background: #198754;
            border-color: #198754;
        }

        .btn-success:hover {
            background: #157347;
            border-color: #157347;
        }

        /* Badge Enhancements */
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Alert Enhancements */
        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        /* Form Controls */
        .form-control:focus,
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        /* Loading Spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Navigation */
        .nav-section {
            flex-grow: 1;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block !important;
            }
        }

        @media (min-width: 769px) {
            .sidebar-toggle {
                display: none !important;
            }
        }

        /* RTL Specific Styles */
        [dir="rtl"] {
            .sidebar {
                border-right: none;
                border-left: 1px solid #dee2e6;
                right: 0;
                left: auto;
            }

            .main-content {
                margin-left: 0;
                margin-right: 280px;
            }

            .profile-name {
                text-align: right;
            }

            .language-switcher .dropdown-toggle {
                text-align: right;
            }



            @media (max-width: 768px) {
                .main-content {
                    margin-right: 0;
                }
            }
        }

        /* Mobile Toggle Button */
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6c757d;
            padding: 0.5rem;
        }

        .sidebar-toggle:hover {
            color: #198754;
        }

        .toast-body {
            width: 88%;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <!-- Logo and Title -->
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-palette fa-2x text-success"></i>
                </div>
                <div class="platform-title">Velorena</div>
                <small class="text-muted">Designer Platform</small>
            </div>



            <!-- Navigation Links -->
            <nav class="nav-section">
                <a href="{{ route('designer.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('designer.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>{{ trans('dashboard.dashboard') }}</span>
                </a>

                <a href="{{ route('designer.appointments.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('designer.appointments.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>{{ trans('dashboard.appointments') }}</span>
                </a>

                <a href="{{ route('designer.appointments.index') }}"
                    class="sidebar-link {{ request()->routeIs('designer.appointments.index') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>{{ trans('dashboard.all_appointments') }}</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('designer.logout') }}" class="mt-auto">
                @csrf
                <button type="submit" class="sidebar-link logout-btn w-100">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>{{ trans('dashboard.logout') }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="main-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <button class="sidebar-toggle" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="h4 mb-0 ms-3 d-inline">@yield('title', trans('dashboard.designer_dashboard'))</h1>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex align-items-center">
                            <!-- Language Switcher -->
                            @php
                                use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
                                $current = LaravelLocalization::getCurrentLocale();
                            @endphp
                            <div class="dropdown me-3 language-switcher">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <span
                                        class="text-xs rounded px-2 py-0.5 border me-1">{{ strtoupper($current) }}</span>
                                    <span class="d-none d-sm-inline">
                                        {{ $current === 'ar' ? 'العربية' : 'English' }}
                                    </span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        @php
                                            $url = LaravelLocalization::getLocalizedURL($localeCode, null, [], true);
                                            $active = $localeCode === $current;
                                        @endphp
                                        <li>
                                            <a href="{{ $url }}" hreflang="{{ $localeCode }}"
                                                rel="alternate"
                                                class="dropdown-item d-flex justify-content-between align-items-center @if ($active) active @endif">
                                                <span class="d-flex align-items-center">
                                                    <span
                                                        class="text-xs rounded px-2 py-0.5 border me-2">{{ strtoupper($localeCode) }}</span>
                                                    <span>{{ $properties['native'] ?? strtoupper($localeCode) }}</span>
                                                </span>
                                                @if ($active)
                                                    <i class="fas fa-check text-success"></i>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Designer Profile -->
                            <div class="d-flex align-items-center">
                                <div class="me-3 text-end">
                                    <div class="profile-name">{{ Auth::guard('designer')->user()->name }}</div>
                                    <div class="profile-role">Designer</div>
                                </div>
                                <div class="position-relative">
                                    @php
                                        $designer = Auth::guard('designer')->user();
                                        $avatarUrl =
                                            $designer->avatar ??
                                            'https://ui-avatars.com/api/?name=' .
                                                urlencode($designer->name) .
                                                '&background=198754&color=fff&size=100&rounded=true';
                                    @endphp
                                    <img src="{{ $avatarUrl }}" alt="Profile" class="profile-img"
                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('designer')->user()->name) }}&background=198754&color=fff&size=100&rounded=true'">
                                    <span
                                        class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle"
                                        style="width: 12px; height: 12px;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('appointment-accepted', (appointmentId) => {
                showNotification('{{ trans('dashboard.appointment_accepted_success') }}', 'success');
            });

            Livewire.on('appointment-rejected', (appointmentId) => {
                showNotification('{{ trans('dashboard.appointment_rejected_success') }}', 'success');
            });

            Livewire.on('appointment-completed', (appointmentId) => {
                showNotification('{{ trans('dashboard.appointment_completed_success') }}', 'success');
            });

            Livewire.on('appointment-created', (appointmentId) => {
                showNotification('{{ trans('dashboard.new_appointment_available') }}', 'info');
            });
        });

        function showNotification(message, type = 'info') {
            // Create Bootstrap toast
            const toastHtml = `
                <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'primary'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

            // Create toast container if it doesn't exist
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '1055';
                document.body.appendChild(toastContainer);
            }

            // Add toast
            const toastElement = document.createElement('div');
            toastElement.innerHTML = toastHtml;
            toastContainer.appendChild(toastElement.firstElementChild);

            // Initialize and show toast
            const toast = new bootstrap.Toast(toastContainer.lastElementChild);
            toast.show();

            // Remove toast element after it's hidden
            toastContainer.lastElementChild.addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        }
    </script>

    <style>
        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }
    </style>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    </script>
</body>

</html>
