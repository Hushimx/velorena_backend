@extends('lender.layouts.app')

@section('title', 'الطلبات')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">الطلبات</h1>
            <p class="text-gray-600">إدارة طلبات التأجير الخاصة بك</p>
        </div>
    </div>

    @livewire('lender-orders-table')
</div>
@endsection









