@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Orders')
@section('title', trans('orders.orders_list'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('orders.orders_list') }}</h1>
                <p class="text-gray-600">{{ trans('orders.manage_orders') }}</p>
            </div>
        </div>

        <!-- Orders Table -->
        @livewire('orders-table')
    </div>
@endsection
