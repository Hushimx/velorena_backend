@extends('designer.layouts.app')

@section('title', __('dashboard.portfolio'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('dashboard.portfolio') }}</h1>
                <p class="mt-1" style="color: #ffde9f;">{{ __('dashboard.manage_your_design_portfolio') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <button class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span class="hidden sm:block">{{ __('dashboard.add_work') }}</span>
                </button>
                <div class="hidden md:block">
                    <i class="fas fa-images text-4xl" style="color: #ffde9f;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-images text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.total_works') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #f5d182; color: #2a1e1e;">
                    <i class="fas fa-eye text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.total_views') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #3a2e2e; color: #ffde9f;">
                    <i class="fas fa-heart text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.total_likes') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Works -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.your_works') }}</h3>
            <div class="flex items-center gap-3">
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:border-2a1e1e">
                    <option value="all">{{ __('dashboard.all_categories') }}</option>
                    <option value="logo">{{ __('dashboard.logo_design') }}</option>
                    <option value="web">{{ __('dashboard.web_design') }}</option>
                    <option value="print">{{ __('dashboard.print_design') }}</option>
                </select>
                <button class="text-sm border border-gray-300 rounded-lg px-3 py-1 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-filter mr-1"></i>
                    {{ __('dashboard.filter') }}
                </button>
            </div>
        </div>

        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-images text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('dashboard.no_works_yet') }}</h3>
            <p class="text-gray-500 mb-6">{{ __('dashboard.start_building_your_portfolio') }}</p>
            <button class="px-6 py-3 text-white rounded-lg font-medium transition-all duration-200 hover:shadow-lg"
                    style="background-color: #2a1e1e;">
                <i class="fas fa-plus mr-2"></i>
                {{ __('dashboard.add_first_work') }}
            </button>
        </div>

        <!-- Portfolio Grid (Hidden when empty) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" style="display: none;">
            <!-- Portfolio Item Example -->
            <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                </div>
                <div class="p-4">
                    <h4 class="font-medium text-gray-900 mb-2">{{ __('dashboard.project_title') }}</h4>
                    <p class="text-sm text-gray-500 mb-3">{{ __('dashboard.project_description') }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">{{ __('dashboard.category') }}</span>
                        <div class="flex items-center gap-2">
                            <button class="text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="text-gray-400 hover:text-blue-500 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
