@extends('lender.layouts.app')

@section('title', 'المحادثات')

@push('styles')
<style>
/* Remove padding from main content for full height chat */
main {
    padding: 0 !important;
    height: calc(100vh - 4rem); /* Account for header height */
    overflow: hidden;
}
/* Make content take full height */
.animate-fade-in {
    height: 100%;
    margin: 0;
}
</style>
@endpush

@section('content')
<div class="h-full w-full bg-gray-50 flex" dir="rtl">
    <!-- Chat Users Sidebar (Right side) -->
    <div class="w-80 bg-white border-l border-gray-200 flex flex-col">
        @livewire('lender-chats-table')
    </div>
    
    <!-- Main Chat Area (Middle) -->
    <div class="flex-1 bg-white flex flex-col max-h-[calc(100vh-4rem)] p-0">
        <!-- Welcome Message -->
        <div class="flex-1 flex items-center justify-center">
            <div class="text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">مرحباً بك في المحادثات</h3>
                <p class="text-gray-500">اختر محادثة من القائمة لبدء الدردشة</p>
            </div>
        </div>
    </div>
</div>
@endsection