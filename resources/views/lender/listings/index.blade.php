@extends('lender.layouts.app')

@section('title', 'عروضي')

@section('content')
<div class="space-y-6">
    <!-- Profile Completion Notice -->
    <x-profile-completion-notice />

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">عروضي</h1>
            <p class="text-gray-600">إدارة جميع عروض الإيجار الخاصة بك</p>
        </div>
        @if(isset($profileIncomplete) && $profileIncomplete)
            <button disabled 
                class="bg-gray-400 cursor-not-allowed text-white px-4 py-2 rounded-lg flex items-center space-x-2 opacity-50"
                title="يجب إكمال الملف الشخصي أولاً">
                <i class="fas fa-plus"></i>
                <span>إضافة إعلان جديد</span>
            </button>
        @elseif(!isset($profileIncomplete) || !$profileIncomplete)
            <a href="{{ route('lender.listings.create') }}" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-plus"></i>
                <span>إضافة إعلان جديد</span>
            </a>
        @endif
    </div>

    <!-- Listings Table -->
    @livewire('lender-listings-table')
</div>
@endsection

