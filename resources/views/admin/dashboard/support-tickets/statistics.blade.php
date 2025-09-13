@extends('admin.layouts.app')

@section('pageTitle', __('admin.ticket_statistics'))
@section('title', __('admin.ticket_statistics'))

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-6 border border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2" style="color: var(--brand-brown);">
                    <i class="fas fa-chart-line mr-3"></i>{{ __('admin.ticket_statistics') }}
                </h1>
                <p class="text-gray-600 text-lg">{{ __('admin.support_ticket_analytics') }}</p>
                <div class="flex items-center mt-2 text-sm text-gray-500">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <span>{{ __('admin.last_updated') }}: {{ now()->format('M d, Y H:i') }}</span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.support-tickets.index') }}" class="btn btn-secondary shadow-lg hover:shadow-xl transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>{{ __('admin.back_to_tickets') }}</span>
                </a>
                <button onclick="window.print()" class="btn btn-info shadow-lg hover:shadow-xl transition-all duration-300">
                    <i class="fas fa-print mr-2"></i>
                    <span>{{ __('admin.print_report') }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="group">
            <div class="card bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 text-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-4xl font-bold mb-1">{{ $stats['total'] }}</h3>
                            <p class="text-blue-100 text-lg font-medium">{{ __('admin.total_tickets') }}</p>
                            <div class="mt-2 flex items-center text-blue-200">
                                <i class="fas fa-arrow-up text-sm mr-1"></i>
                                <span class="text-sm">+12% this month</span>
                            </div>
                        </div>
                        <div class="text-blue-200 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-ticket-alt text-5xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="group">
            <div class="card bg-gradient-to-br from-green-500 via-green-600 to-green-700 text-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-4xl font-bold mb-1">{{ $stats['open'] }}</h3>
                            <p class="text-green-100 text-lg font-medium">{{ __('admin.open_tickets') }}</p>
                            <div class="mt-2 flex items-center text-green-200">
                                <i class="fas fa-clock text-sm mr-1"></i>
                                <span class="text-sm">Active support</span>
                            </div>
                        </div>
                        <div class="text-green-200 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-folder-open text-5xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="group">
            <div class="card bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 text-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-4xl font-bold mb-1">{{ $stats['closed'] }}</h3>
                            <p class="text-purple-100 text-lg font-medium">{{ __('admin.closed_tickets') }}</p>
                            <div class="mt-2 flex items-center text-purple-200">
                                <i class="fas fa-check-circle text-sm mr-1"></i>
                                <span class="text-sm">Resolved</span>
                            </div>
                        </div>
                        <div class="text-purple-200 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-check-circle text-5xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="group">
            <div class="card bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 text-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-4xl font-bold mb-1">{{ $stats['assigned_to_me'] }}</h3>
                            <p class="text-orange-100 text-lg font-medium">{{ __('admin.assigned_to_me') }}</p>
                            <div class="mt-2 flex items-center text-orange-200">
                                <i class="fas fa-user-check text-sm mr-1"></i>
                                <span class="text-sm">Your workload</span>
                            </div>
                        </div>
                        <div class="text-orange-200 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-user text-5xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Tickets by Priority -->
        <div class="card shadow-xl hover:shadow-2xl transition-all duration-300">
            <div class="card-header bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--brand-brown);">
                            <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>{{ __('admin.tickets_by_priority') }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">{{ __('admin.priority_distribution_overview') }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-2">
                        <i class="fas fa-chart-pie text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="card-body p-6">
                <div class="relative">
                    <canvas id="priorityChart" height="350"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $stats['by_priority']['urgent'] ?? 0 }}</div>
                        <div class="text-sm text-red-500">{{ __('admin.urgent') }}</div>
                    </div>
                    <div class="text-center p-3 bg-orange-50 rounded-lg">
                        <div class="text-2xl font-bold text-orange-600">{{ $stats['by_priority']['high'] ?? 0 }}</div>
                        <div class="text-sm text-orange-500">{{ __('admin.high') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets by Category -->
        <div class="card shadow-xl hover:shadow-2xl transition-all duration-300">
            <div class="card-header bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--brand-brown);">
                            <i class="fas fa-tags mr-2 text-green-500"></i>{{ __('admin.tickets_by_category') }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">{{ __('admin.category_breakdown') }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-2">
                        <i class="fas fa-chart-doughnut text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="card-body p-6">
                <div class="relative">
                    <canvas id="categoryChart" height="350"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-2">
                    <div class="text-center p-2 bg-blue-50 rounded">
                        <div class="text-lg font-bold text-blue-600">{{ $stats['by_category']['technical'] ?? 0 }}</div>
                        <div class="text-xs text-blue-500">{{ __('admin.technical') }}</div>
                    </div>
                    <div class="text-center p-2 bg-purple-50 rounded">
                        <div class="text-lg font-bold text-purple-600">{{ $stats['by_category']['billing'] ?? 0 }}</div>
                        <div class="text-xs text-purple-500">{{ __('admin.billing') }}</div>
                    </div>
                    <div class="text-center p-2 bg-gray-50 rounded">
                        <div class="text-lg font-bold text-gray-600">{{ $stats['by_category']['general'] ?? 0 }}</div>
                        <div class="text-xs text-gray-500">{{ __('admin.general') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Status Chart and Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Tickets by Status -->
        <div class="card shadow-xl hover:shadow-2xl transition-all duration-300">
            <div class="card-header bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--brand-brown);">
                            <i class="fas fa-chart-bar mr-2 text-purple-500"></i>{{ __('admin.tickets_by_status') }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">{{ __('admin.status_distribution') }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-2">
                        <i class="fas fa-chart-bar text-purple-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="card-body p-6">
                <div class="relative">
                    <canvas id="statusChart" height="350"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="text-center p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['by_status']['open'] ?? 0 }}</div>
                        <div class="text-sm text-green-500">{{ __('admin.open') }}</div>
                    </div>
                    <div class="text-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['by_status']['in_progress'] ?? 0 }}</div>
                        <div class="text-sm text-blue-500">{{ __('admin.in_progress') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Quick Stats -->
        <div class="card shadow-xl hover:shadow-2xl transition-all duration-300">
            <div class="card-header bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--brand-brown);">
                            <i class="fas fa-tachometer-alt mr-2 text-indigo-500"></i>{{ __('admin.quick_stats') }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">{{ __('admin.performance_metrics') }}</p>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-2">
                        <i class="fas fa-chart-line text-indigo-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="card-body p-6">
                <div class="space-y-6">
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-green-800">{{ __('admin.resolution_rate') }}</span>
                                <div class="text-xs text-green-600 mt-1">{{ __('admin.tickets_resolved') }}</div>
                            </div>
                            <div class="text-right">
                                <span class="text-3xl font-bold text-green-600">
                                    {{ $stats['total'] > 0 ? round(($stats['closed'] / $stats['total']) * 100, 1) : 0 }}%
                                </span>
                                <div class="w-16 bg-green-200 rounded-full h-2 mt-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? ($stats['closed'] / $stats['total']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-blue-800">{{ __('admin.open_rate') }}</span>
                                <div class="text-xs text-blue-600 mt-1">{{ __('admin.active_tickets') }}</div>
                            </div>
                            <div class="text-right">
                                <span class="text-3xl font-bold text-blue-600">
                                    {{ $stats['total'] > 0 ? round(($stats['open'] / $stats['total']) * 100, 1) : 0 }}%
                                </span>
                                <div class="w-16 bg-blue-200 rounded-full h-2 mt-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? ($stats['open'] / $stats['total']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-orange-800">{{ __('admin.assigned_rate') }}</span>
                                <div class="text-xs text-orange-600 mt-1">{{ __('admin.your_workload') }}</div>
                            </div>
                            <div class="text-right">
                                <span class="text-3xl font-bold text-orange-600">
                                    {{ $stats['total'] > 0 ? round(($stats['assigned_to_me'] / $stats['total']) * 100, 1) : 0 }}%
                                </span>
                                <div class="w-16 bg-orange-200 rounded-full h-2 mt-2">
                                    <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? ($stats['assigned_to_me'] / $stats['total']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Detailed Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Priority Distribution -->
        <div class="card shadow-xl hover:shadow-2xl transition-all duration-300">
            <div class="card-header bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--brand-brown);">
                            <i class="fas fa-list-ol mr-2 text-red-500"></i>{{ __('admin.priority_distribution') }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">{{ __('admin.detailed_priority_breakdown') }}</p>
                    </div>
                    <div class="bg-red-100 rounded-full p-2">
                        <i class="fas fa-sort-amount-down text-red-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="card-body p-6">
                <div class="overflow-x-auto">
                    <table class="table table-hover">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="font-semibold text-gray-700">{{ __('admin.priority') }}</th>
                                <th class="font-semibold text-gray-700 text-center">{{ __('admin.count') }}</th>
                                <th class="font-semibold text-gray-700 text-center">{{ __('admin.percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['by_priority'] as $priority => $count)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td>
                                        <div class="flex items-center">
                                            <span class="badge badge-{{ $priority === 'urgent' ? 'danger' : ($priority === 'high' ? 'warning' : ($priority === 'medium' ? 'info' : 'secondary')) }} px-3 py-1">
                                                {{ ucfirst($priority) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="font-bold text-lg text-center">{{ $count }}</td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center">
                                            <span class="font-semibold text-gray-700">{{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0 }}%</span>
                                            <div class="ml-2 w-12 bg-gray-200 rounded-full h-2">
                                                <div class="bg-{{ $priority === 'urgent' ? 'red' : ($priority === 'high' ? 'yellow' : ($priority === 'medium' ? 'blue' : 'gray')) }}-500 h-2 rounded-full" 
                                                     style="width: {{ $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="card shadow-xl hover:shadow-2xl transition-all duration-300">
            <div class="card-header bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--brand-brown);">
                            <i class="fas fa-layer-group mr-2 text-blue-500"></i>{{ __('admin.category_distribution') }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">{{ __('admin.detailed_category_breakdown') }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-2">
                        <i class="fas fa-chart-pie text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="card-body p-6">
                <div class="overflow-x-auto">
                    <table class="table table-hover">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="font-semibold text-gray-700">{{ __('admin.category') }}</th>
                                <th class="font-semibold text-gray-700 text-center">{{ __('admin.count') }}</th>
                                <th class="font-semibold text-gray-700 text-center">{{ __('admin.percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['by_category'] as $category => $count)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td>
                                        <div class="flex items-center">
                                            <span class="badge badge-{{ $category === 'technical' ? 'danger' : ($category === 'billing' ? 'warning' : 'info') }} px-3 py-1">
                                                {{ ucfirst(str_replace('_', ' ', $category)) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="font-bold text-lg text-center">{{ $count }}</td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center">
                                            <span class="font-semibold text-gray-700">{{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0 }}%</span>
                                            <div class="ml-2 w-12 bg-gray-200 rounded-full h-2">
                                                <div class="bg-{{ $category === 'technical' ? 'red' : ($category === 'billing' ? 'yellow' : 'blue') }}-500 h-2 rounded-full" 
                                                     style="width: {{ $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Enhanced Priority Chart
const priorityCtx = document.getElementById('priorityChart').getContext('2d');
new Chart(priorityCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($stats['by_priority']->toArray())) !!},
        datasets: [{
            data: {!! json_encode(array_values($stats['by_priority']->toArray())) !!},
            backgroundColor: [
                'rgba(220, 53, 69, 0.8)',
                'rgba(253, 126, 20, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(40, 167, 69, 0.8)'
            ],
            borderColor: [
                'rgba(220, 53, 69, 1)',
                'rgba(253, 126, 20, 1)',
                'rgba(255, 193, 7, 1)',
                'rgba(40, 167, 69, 1)'
            ],
            borderWidth: 3,
            hoverBorderWidth: 4,
            hoverBorderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '60%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 25,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#fff',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: true
            }
        },
        animation: {
            animateRotate: true,
            animateScale: true,
            duration: 2000,
            easing: 'easeOutQuart'
        }
    }
});

// Enhanced Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode(array_map(function($cat) { return ucfirst(str_replace('_', ' ', $cat)); }, array_keys($stats['by_category']->toArray()))) !!},
        datasets: [{
            data: {!! json_encode(array_values($stats['by_category']->toArray())) !!},
            backgroundColor: [
                'rgba(0, 123, 255, 0.8)',
                'rgba(111, 66, 193, 0.8)',
                'rgba(32, 201, 151, 0.8)',
                'rgba(253, 126, 20, 0.8)',
                'rgba(232, 62, 140, 0.8)'
            ],
            borderColor: [
                'rgba(0, 123, 255, 1)',
                'rgba(111, 66, 193, 1)',
                'rgba(32, 201, 151, 1)',
                'rgba(253, 126, 20, 1)',
                'rgba(232, 62, 140, 1)'
            ],
            borderWidth: 3,
            hoverBorderWidth: 4,
            hoverBorderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 25,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#fff',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: true
            }
        },
        animation: {
            animateRotate: true,
            animateScale: true,
            duration: 2000,
            easing: 'easeOutQuart'
        }
    }
});

// Enhanced Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($stats['by_status']->toArray())) !!},
        datasets: [{
            label: '{{ __('admin.tickets') }}',
            data: {!! json_encode(array_values($stats['by_status']->toArray())) !!},
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(0, 123, 255, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(23, 162, 184, 0.8)',
                'rgba(108, 117, 125, 0.8)'
            ],
            borderColor: [
                'rgba(40, 167, 69, 1)',
                'rgba(0, 123, 255, 1)',
                'rgba(255, 193, 7, 1)',
                'rgba(23, 162, 184, 1)',
                'rgba(108, 117, 125, 1)'
            ],
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#fff',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: true
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)',
                    lineWidth: 1
                },
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 12,
                        weight: 'bold'
                    },
                    color: '#666'
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 12,
                        weight: 'bold'
                    },
                    color: '#666'
                }
            }
        },
        animation: {
            duration: 2000,
            easing: 'easeOutQuart'
        }
    }
});

// Add smooth scroll animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards
    document.querySelectorAll('.card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endsection