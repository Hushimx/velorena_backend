@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Designers')
@section('title', trans('designers.designerslist'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('designers.designerslist') }}</h1>
                <p class="text-gray-600">{{ trans('designers.manage_designers') }}</p>
            </div>
            <a href="{{ route('admin.designers.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-plus pl-2"></i>
                <span>{{ trans('designers.add_new_designer') }}</span>
            </a>
        </div>

        <!-- Designers Table -->
        @livewire('designers-table')
    </div>
@endsection
