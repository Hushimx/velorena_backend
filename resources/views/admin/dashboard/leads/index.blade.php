@extends('admin.layouts.app')

@section('pageTitle', __('admin.leads_management'))
@section('title', __('admin.leads_management'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.leads_management') }}</h1>
                <p class="text-gray-600">{{ __('admin.manage_leads_platform') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.leads.bulk-upload') }}" class="btn btn-secondary">
                    <i class="fas fa-upload"></i>
                    <span>{{ __('admin.bulk_upload') }}</span>
                </a>
                <a href="{{ route('admin.leads.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>{{ __('admin.add_new_lead') }}</span>
                </a>
            </div>
        </div>

        <!-- Leads Table -->
        @livewire('leads-table')
    </div>
@endsection
