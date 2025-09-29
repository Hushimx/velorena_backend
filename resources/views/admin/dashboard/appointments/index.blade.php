@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Appointments')
@section('title', trans('appointments.appointments_list'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('appointments.appointments_list') }}</h1>
                <p class="text-gray-600">{{ trans('appointments.manage_appointments') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.appointments.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>{{ trans('appointments.add_appointment') }}</span>
                </a>
            </div>
        </div>

        <!-- Appointments Table -->
        @livewire('appointments-table')
    </div>
@endsection