@extends('lender.layouts.app')

@section('title', 'عروضي')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">عروضي</h1>
            <p class="text-gray-600">إدارة جميع عروض الإيجار الخاصة بك</p>
        </div>
        <a href="{{ route('lender.listings.create') }}" 
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
            <i class="fas fa-plus"></i>
            <span>إضافة عرض جديد</span>
        </a>
    </div>

    <!-- Listings Table -->
    @livewire('lender-listings-table')
</div>
@endsection

