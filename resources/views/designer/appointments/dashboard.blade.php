@extends('designer.layouts.app')

@section('pageTitle', 'Designer | Appointments Dashboard')
@section('title', 'Appointments Dashboard')

@section('content')
    <div class="container">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-success text-white border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="card-title mb-1">Welcome, {{ Auth::guard('designer')->user()->name }}</h2>
                                <p class="card-text opacity-75 mb-0">Here's an overview of your appointments today</p>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="spinner-grow spinner-grow-sm text-success me-2" role="status" aria-hidden="true"></div>
                        <small class="text-muted fw-medium">Live Updates Active</small>
                    </div>
                    <a href="{{ route('designer.appointments.index') }}" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>View All Appointments
                    </a>
                </div>
            </div>
        </div>

        <!-- Livewire Component -->
        @livewire('designer-live-appointments')
    </div>
@endsection
