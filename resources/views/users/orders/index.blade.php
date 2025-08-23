@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ trans('orders.my_orders') }}</h1>
                    <p class="text-gray-600">{{ trans('orders.view_your_orders') }}</p>
                </div>
            </div>

            <!-- Orders Table -->
            @livewire('user-orders-table')
        </div>
    </div>
@endsection
