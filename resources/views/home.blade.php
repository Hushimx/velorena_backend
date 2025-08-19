@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">{{ __('User Dashboard') }}</h1>
                        <p class="text-muted">{{ __('Welcome back! Here\'s what\'s happening with your account.') }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-cog me-2"></i>{{ __('Settings') }}
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>{{ __('New Activity') }}
                        </button>
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
                                            {{ __('Notifications') }}
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-bell fa-2x text-gray-300"></i>
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
                                        <a href="#" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-user-edit me-2"></i>{{ __('Update Profile') }}
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="#" class="btn btn-outline-success w-100">
                                            <i class="fas fa-key me-2"></i>{{ __('Change Password') }}
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="#" class="btn btn-outline-info w-100">
                                            <i class="fas fa-cog me-2"></i>{{ __('Settings') }}
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="#" class="btn btn-outline-warning w-100">
                                            <i class="fas fa-question-circle me-2"></i>{{ __('Help') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">{{ __('Recent Activity') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Activity') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ __('Account Login') }}</td>
                                                <td>{{ now()->format('M d, Y H:i') }}</td>
                                                <td><span class="badge bg-success">{{ __('Success') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('Profile Updated') }}</td>
                                                <td>{{ Auth::user()->updated_at->format('M d, Y H:i') }}</td>
                                                <td><span class="badge bg-info">{{ __('Completed') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('Account Created') }}</td>
                                                <td>{{ Auth::user()->created_at->format('M d, Y H:i') }}</td>
                                                <td><span class="badge bg-primary">{{ __('Completed') }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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
