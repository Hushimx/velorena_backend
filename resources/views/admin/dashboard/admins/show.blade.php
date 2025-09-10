@extends('admin.layouts.app')

@section('pageTitle', __('admin.admin_details'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.admin_details') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('admin.admin_information') }}: {{ $admin->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.admins.edit', $admin) }}" class="bg-white text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 hover:shadow-md" style="background-color: #2a1e1e;">
                <i class="fas fa-edit"></i>
                <span>{{ __('admin.edit') }}</span>
            </a>
            <a href="{{ route('admin.admins.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>{{ __('admin.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Admin Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Profile Info -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.admin_information') }}</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-xl font-semibold" style="background-color: #2a1e1e;">
                            {{ substr($admin->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">{{ $admin->name }}</h4>
                            <p class="text-gray-600">{{ $admin->email }}</p>
                            @if($admin->id === auth()->guard('admin')->id())
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full mt-1" style="background-color: #ffde9f; color: #2a1e1e;">
                                    حسابك الحالي
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Details -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.account_details') }}</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">{{ __('admin.admin_created_at') }}:</span>
                        <span class="text-sm text-gray-900">{{ $admin->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">{{ __('admin.admin_updated_at') }}:</span>
                        <span class="text-sm text-gray-900">{{ $admin->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">{{ __('admin.admin_status') }}:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            {{ __('admin.admin_active') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    @if($admin->id !== auth()->guard('admin')->id())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.dangerous_actions') }}</h3>
            <div class="flex items-center gap-4">
                <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المدير؟ هذا الإجراء لا يمكن التراجع عنه.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-trash"></i>
                        <span>{{ __('admin.delete_admin') }}</span>
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
