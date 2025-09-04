@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Marketers')
@section('title', 'قائمة المسوقيين')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">قائمة المسوقيين</h1>
                <p class="text-gray-600">إدارة المسوقيين في المنصة</p>
            </div>
            <a href="{{ route('admin.marketers.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-plus pl-2"></i>
                <span>إضافة مسوق جديد</span>
            </a>
        </div>

        <!-- Marketers Table -->
        @livewire('marketers-table')
    </div>
@endsection
