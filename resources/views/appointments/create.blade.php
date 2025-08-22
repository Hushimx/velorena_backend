@extends('layouts.app')

@section('pageTitle', 'Book Appointment')
@section('title', 'Book Appointment')

@section('content')
    <div class="container">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-success text-white border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="card-title mb-1">Book an Appointment</h2>
                                <p class="card-text opacity-75 mb-0">Schedule a consultation with one of our expert designers
                                </p>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="fas fa-calendar-plus fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Choose your preferred date and time for a 15-minute consultation</small>
                    </div>
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Appointments
                    </a>
                </div>
            </div>
        </div>

        <!-- Livewire Component -->
        @livewire('book-appointment')
    </div>
@endsection
