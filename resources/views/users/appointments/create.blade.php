@extends('layouts.app')

@section('pageTitle', trans('dashboard.book_new_appointment'))
@section('title', trans('dashboard.book_new_appointment'))

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-blue-500 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">{{ trans('dashboard.book_new_appointment') }}</h1>
                    <p class="text-blue-100 mt-1">
                        {{ trans('dashboard.book_appointment_description', ['default' => 'Schedule a consultation with one of our expert designers']) }}
                    </p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-calendar-plus text-4xl text-blue-200"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex justify-between items-center">
            <div>
                <small class="text-gray-500">
                    {{ trans('dashboard.choose_preferred_time', ['default' => 'Choose your preferred date and time for a 15-minute consultation']) }}
                </small>
            </div>
            <a href="{{ route('appointments.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg inline-flex items-center transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ trans('dashboard.back_to_appointments') }}
            </a>
        </div>

        <!-- Livewire Component -->
        @livewire('book-appointment')
    </div>
@endsection
