@extends('layouts.app')

@section('pageTitle', 'Design Selector Demo')
@section('title', 'Design Selector Demo')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-purple-500 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Design Selector Demo</h1>
                    <p class="text-purple-100 mt-1">
                        Test the design selection functionality for appointments
                    </p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-palette text-4xl text-purple-200"></i>
                </div>
            </div>
        </div>

        <!-- Demo Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="font-semibold text-blue-900 mb-2">How to Test:</h3>
            <ul class="text-blue-800 text-sm space-y-1">
                <li>• Browse designs from the external API</li>
                <li>• Search and filter designs by category</li>
                <li>• Select multiple designs for your project</li>
                <li>• Add notes to each selected design</li>
                <li>• See how designs would be attached to appointments</li>
            </ul>
        </div>

        <!-- Design Selector Component -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Design Selection</h2>
                @livewire('design-selector')
            </div>
        </div>

        <!-- Integration Example -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Integration Example</h2>
            <p class="text-gray-600 mb-4">
                This design selector can be integrated into your appointment booking form.
                Users can browse designs, select inspiration, and attach them to their appointments.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-2">In Appointment Form:</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Step 1: Select date & time</li>
                        <li>• Step 2: Choose order to link</li>
                        <li>• <strong>Step 3: Select design inspiration</strong></li>
                        <li>• Step 4: Add project notes</li>
                        <li>• Submit appointment with designs</li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-2">Benefits:</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Visual communication of project vision</li>
                        <li>• Better designer understanding</li>
                        <li>• Streamlined consultation process</li>
                        <li>• Professional design references</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Back to Appointments -->
        <div class="text-center">
            <a href="{{ route('appointments.create') }}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-calendar-plus mr-2"></i>
                Try in Appointment Form
            </a>
        </div>
    </div>
@endsection
