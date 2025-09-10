@extends('admin.layouts.app')

@section('pageTitle', __('admin.marketers_management'))
@section('title', __('admin.marketers_management'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.marketers_management') }}</h1>
                <p class="text-gray-600">{{ __('admin.manage_marketers_platform') }}</p>
            </div>
            <a href="{{ route('admin.marketers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                <span>{{ __('admin.add_new_marketer') }}</span>
            </a>
        </div>

        <!-- Marketers Table -->
        @livewire('marketers-table')
    </div>
@endsection
