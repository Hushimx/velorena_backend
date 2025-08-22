@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">{{ __('User Dashboard') }}</h1>
                        <p class="text-muted">{{ __('Welcome back! Here\'s what\'s happening with your account.') }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('appointments.create') }}" class="btn btn-success">
                            <i class="fas fa-calendar-plus me-2"></i>{{ __('Book Appointment') }}
                        </a>
                        <a href="{{ route('appointments.index') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-alt me-2"></i>{{ __('My Appointments') }}
                        </a>
                    </div>
                </div>

                <!-- Welcome Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-gradient-primary text-white">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h4 class="card-title mb-2">{{ __('Welcome,') }} {{ Auth::user()->name }}!</h4>
                                        <p class="card-text mb-0">
                                            {{ __('Here\'s an overview of your account activity and recent updates.') }}</p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <i class="fas fa-user-circle fa-3x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            {{ __('Profile Status') }}
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __('Complete') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            {{ __('Account Status') }}
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __('Active') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            {{ __('Last Login') }}
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __('Today') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            {{ __('My Appointments') }}
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <a href="{{ route('appointments.index') }}" class="text-decoration-none">
                                                {{ \App\Models\Appointment::where('user_id', Auth::id())->count() }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{ route('appointments.index') }}" class="text-decoration-none">
                                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Row -->
                <div class="row">
                    <!-- Profile Information -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">{{ __('Profile Information') }}</h6>
                                <a href="#" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i>{{ __('Edit') }}
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>{{ __('Name:') }}</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ Auth::user()->name }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>{{ __('Email:') }}</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ Auth::user()->email }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>{{ __('Member Since:') }}</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ Auth::user()->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>{{ __('Last Updated:') }}</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ Auth::user()->updated_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">{{ __('Quick Actions') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('appointments.index') }}" class="btn btn-primary w-100">
                                            <i class="fas fa-calendar-alt me-2"></i>{{ __('My Appointments') }}
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('appointments.create') }}" class="btn btn-success w-100">
                                            <i class="fas fa-calendar-plus me-2"></i>{{ __('Book Appointment') }}
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="#" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-user-edit me-2"></i>{{ __('Update Profile') }}
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="#" class="btn btn-outline-info w-100">
                                            <i class="fas fa-cog me-2"></i>{{ __('Settings') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Appointments -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">{{ __('Recent Appointments') }}</h6>
                                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i>{{ __('View All') }}
                                </a>
                            </div>
                            <div class="card-body">
                                @php
                                    $recentAppointments = \App\Models\Appointment::where('user_id', Auth::id())
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();
                                @endphp

                                @if ($recentAppointments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Date & Time') }}</th>
                                                    <th>{{ __('Designer') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($recentAppointments as $appointment)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $appointment->formatted_date }}</strong><br>
                                                            <small
                                                                class="text-muted">{{ $appointment->formatted_time }}</small>
                                                        </td>
                                                        <td>
                                                            @if ($appointment->designer)
                                                                {{ $appointment->designer->name }}
                                                            @else
                                                                <span
                                                                    class="text-warning">{{ __('Pending Assignment') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge
                                                                @if ($appointment->status === 'pending') bg-warning
                                                                @elseif($appointment->status === 'accepted') bg-success
                                                                @elseif($appointment->status === 'completed') bg-info
                                                                @elseif($appointment->status === 'cancelled') bg-danger
                                                                @elseif($appointment->status === 'rejected') bg-danger
                                                                @else bg-secondary @endif">
                                                                {{ ucfirst($appointment->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('appointments.show', $appointment) }}"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">{{ __('No appointments yet') }}</h5>
                                        <p class="text-muted">{{ __('You haven\'t booked any appointments yet.') }}</p>
                                        <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                            <i
                                                class="fas fa-calendar-plus me-2"></i>{{ __('Book Your First Appointment') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
