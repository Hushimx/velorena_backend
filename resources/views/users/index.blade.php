@extends('layouts.app')

@section('pageTitle', trans('dashboard.dashboard'))
@section('title', trans('dashboard.dashboard'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ trans('dashboard.dashboard') }}</h4>
                    </div>
                    <div class="card-body">
                        <p>{{ trans('dashboard.welcome_back') }}, {{ Auth::user()->name }}!</p>
                        <p>{{ trans('dashboard.whats_happening') }}</p>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="{{ route('appointments.index') }}" class="btn btn-primary btn-lg w-100 mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    {{ trans('dashboard.view_all_appointments') }}
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('appointments.create') }}" class="btn btn-success btn-lg w-100 mb-3">
                                    <i class="fas fa-calendar-plus me-2"></i>
                                    {{ trans('dashboard.book_new_appointment') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
