@extends('designer.layouts.app')

@section('pageTitle', trans('dashboard.appointments_dashboard'))
@section('title', trans('dashboard.appointments_dashboard'))

@section('content')
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <!-- Main Content -->
                            <div class="col-lg-8 p-4 p-lg-5">
                                <div class="d-flex align-items-center mb-0 gap-3">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-user-tie text-success fa-2x"></i>
                                    </div>
                                    <div>
                                        <h1 class="h3 mb-1 fw-bold text-dark">
                                            {{ trans('dashboard.welcome_back') }},
                                            {{ Auth::guard('designer')->user()->name }}!
                                        </h1>
                                        <p class="text-muted mb-0">{{ trans('dashboard.whats_happening') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Visual Element -->
                            <div class="col-lg-4 bg-gradient d-none d-lg-block"
                                style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                                <div class="d-flex flex-column justify-content-center align-items-center h-100 p-4">
                                    <div class="text-center text-white">
                                        <i class="fas fa-calendar-check fa-5x mb-3 opacity-75"></i>
                                        <h5 class="fw-bold mb-2">Appointment Dashboard</h5>
                                        <p class="opacity-90 mb-0">Manage your schedule efficiently</p>
                                    </div>
                                </div>
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
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2 fs-5"></i>
                        <div class="fw-medium">{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                        <div class="fw-medium">{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="spinner-grow spinner-grow-sm text-success me-2" role="status"
                                        aria-hidden="true"></div>
                                    <span class="text-muted fw-medium">{{ trans('dashboard.live_updates_active') }}</span>
                                    <span class="badge bg-success ms-2">{{ trans('dashboard.real_time') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <a href="{{ route('designer.appointments.index') }}" class="btn btn-primary">
                                    <i class="fas fa-list me-2"></i>{{ trans('dashboard.view_all_appointments') }}
                                </a>
                                <button class="btn btn-outline-secondary ms-2" onclick="location.reload()">
                                    <i class="fas fa-sync-alt me-2"></i>{{ trans('dashboard.refresh') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Livewire Component -->
        <div class="row">
            <div class="col-12">
                @livewire('designer-live-appointments')
            </div>
        </div>
    </div>
@endsection
