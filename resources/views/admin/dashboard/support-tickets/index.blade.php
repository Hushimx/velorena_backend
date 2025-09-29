@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Support Tickets')
@section('title', trans('support.tickets_list'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('support.tickets_list') }}</h1>
                <p class="text-gray-600">{{ trans('support.manage_tickets') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.support-tickets.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>{{ trans('support.add_ticket') }}</span>
                </a>
            </div>
        </div>

        <!-- Support Tickets Table -->
        @livewire('support-tickets-table')
    </div>
@endsection