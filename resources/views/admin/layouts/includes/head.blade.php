<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('pageTitle', 'Default Title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox@3.0.0/dist/css/glightbox.min.css">
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
            background: #059669;
            color: #fff;
            box-shadow: 0 2px 8px 0 rgba(5, 150, 105, 0.10);
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
    </style>
</head>

<body>
