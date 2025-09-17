@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Designers')
@section('title', trans('designers.designerslist'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ trans('designers.designerslist') }}</h1>
                <p class="text-gray-600">{{ trans('designers.manage_designers') }}</p>
            </div>
            <a href="{{ route('admin.designers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                <span>{{ trans('designers.add_new_designer') }}</span>
            </a>
        </div>

        <!-- Designers Table -->
        @livewire('designers-table')
    </div>
@endsection
