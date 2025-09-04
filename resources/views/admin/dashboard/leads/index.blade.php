@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Leads')
@section('title', 'قائمة الـ Leads')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">قائمة الـ Leads</h1>
                <p class="text-gray-600">إدارة الـ leads في المنصة</p>
            </div>
            <a href="{{ route('admin.leads.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-plus pl-2"></i>
                <span>إضافة lead جديد</span>
            </a>
        </div>

        <!-- Leads Table -->
        @livewire('leads-table')
    </div>
@endsection
