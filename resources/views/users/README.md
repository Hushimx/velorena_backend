# User Views Structure

This folder contains all views related to authenticated users after login.

## Folder Structure

```
resources/views/users/
├── README.md                    # This documentation file
├── index.blade.php              # Simple user index page
├── dashboard/                   # User dashboard views
│   └── main.blade.php          # Main user dashboard
└── appointments/                # User appointment views
    ├── index.blade.php         # List user's appointments
    ├── create.blade.php        # Create new appointment
    └── show.blade.php          # Show appointment details
```

## Views Description

### Dashboard Views

-   **`dashboard/main.blade.php`** - Main user dashboard with statistics, profile info, and quick actions

### Appointment Views

-   **`appointments/index.blade.php`** - List all user's appointments with pagination
-   **`appointments/create.blade.php`** - Form to book a new appointment
-   **`appointments/show.blade.php`** - Detailed view of a specific appointment

### General Views

-   **`index.blade.php`** - Simple user index page with navigation options

## Features

-   **Bootstrap 5 Styling** - All views use modern Bootstrap components
-   **Internationalization** - Full support for English and Arabic languages
-   **Responsive Design** - Works on all device sizes
-   **Livewire Integration** - Dynamic appointment booking with Livewire components
-   **User Authentication** - All views require user authentication

## Routes

The following routes are associated with these views:

-   `GET /home` → `users.dashboard.main` (HomeController)
-   `GET /appointments` → `users.appointments.index` (AppointmentController)
-   `GET /appointments/create` → `users.appointments.create` (AppointmentController)
-   `GET /appointments/{id}` → `users.appointments.show` (AppointmentController)

## Layout

All user views extend the main user layout:

```blade
@extends('layouts.app')
```

This provides:

-   Sidebar navigation
-   Top header with language switcher
-   User profile information
-   Responsive design
-   Bootstrap styling
