@extends('admin.layouts.app')

@section('pageTitle', __('admin.pages_management'))
@section('title', __('admin.pages_management'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.pages_management') }}</h1>
                <p class="text-gray-600">{{ __('admin.manage_pages_platform') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.site-settings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-cog"></i>
                    <span>{{ __('admin.settings') }}</span>
                </a>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>{{ __('admin.add_new_page') }}</span>
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-100 text-green-700 rounded-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="px-2">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Pages Table -->
        @livewire('pages-table')
    </div>
@endsection
