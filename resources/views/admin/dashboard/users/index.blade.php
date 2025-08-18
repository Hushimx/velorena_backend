@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Users')
@section('title', trans('users.userslist'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('users.userslist') }}</h1>
                <p class="text-gray-600">{{ trans('users.manage_users') }}</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-plus pl-2"></i>
                <span>{{ trans('users.add new user') }}</span>
            </a>
        </div>

        <!-- Listings Table -->
        @livewire('users-table')
    </div>
@endsection
