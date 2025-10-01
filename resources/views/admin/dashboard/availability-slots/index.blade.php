@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Availability Slots')
@section('title', trans('availability.slots_list'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('availability.slots_list') }}</h1>
                <p class="text-gray-600">{{ trans('availability.manage_slots') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.availability-slots.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>{{ trans('availability.add_slot') }}</span>
                </a>
            </div>
        </div>

        <!-- Availability Slots Table -->
        @livewire('availability-slots-table')
    </div>
@endsection
