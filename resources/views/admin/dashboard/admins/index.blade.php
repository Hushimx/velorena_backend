@extends('admin.layouts.app')

@section('pageTitle', __('admin.admin_management'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.admin_management') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('admin.manage_admin_accounts') }}</p>
        </div>
        <a href="{{ route('admin.admins.create') }}" class="bg-white text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 hover:shadow-md" style="background-color: #2a1e1e;">
            <i class="fas fa-plus"></i>
            <span>{{ __('admin.add_new_admin') }}</span>
        </a>
    </div>

    <!-- Admins Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.admin_name') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.admin_email') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.admin_created_at') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('admin.admin_actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($admins as $admin)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold" style="background-color: #2a1e1e;">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                    <div class="mr-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                                        @if($admin->id === auth()->guard('admin')->id())
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                                                {{ __('admin.current_account') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $admin->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $admin->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.admins.show', $admin) }}" class="text-gray-600 hover:text-gray-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.admins.edit', $admin) }}" class="text-gray-600 hover:text-gray-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($admin->id !== auth()->guard('admin')->id())
                                        <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المدير؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-4" style="color: #ffde9f;"></i>
                                <p>{{ __('admin.no_data') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($admins->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
