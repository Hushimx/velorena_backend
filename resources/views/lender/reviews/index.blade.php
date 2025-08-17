@extends('lender.layouts.app')

@section('title', 'التقييمات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">التقييمات</h1>
            <p class="text-gray-600">عرض والرد على تقييمات العملاء</p>
        </div>
    </div>

    <!-- Reviews Table -->
    @livewire('lender-reviews-table')
</div>
@endsection








