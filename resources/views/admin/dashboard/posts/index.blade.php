@extends('admin.layouts.app')

@section('pageTitle', trans('posts.posts_management'))
@section('title', trans('posts.posts_management'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ trans('posts.posts_management') }}</h1>
                <p class="text-gray-600">{{ trans('posts.manage_posts_platform') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>{{ trans('posts.add_new_post') }}</span>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-newspaper text-blue-600"></i>
                    </div>
                    <div class="mx-4">
                        <p class="text-sm font-medium text-gray-600">{{ trans('posts.total_posts') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $posts->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="mx-4">
                        <p class="text-sm font-medium text-gray-600">{{ trans('posts.published_posts') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $posts->where('status', 'published')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <i class="fas fa-edit text-yellow-600"></i>
                    </div>
                    <div class="mx-4">
                        <p class="text-sm font-medium text-gray-600">{{ trans('posts.draft_posts') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $posts->where('status', 'draft')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <i class="fas fa-star text-purple-600"></i>
                    </div>
                    <div class="mx-4">
                        <p class="text-sm font-medium text-gray-600">{{ trans('posts.featured_posts') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $posts->where('is_featured', true)->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ trans('posts.all_posts') }}</h3>
            </div>

            @if ($posts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('posts.title') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('posts.status') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('posts.author') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('posts.created_at') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('posts.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($posts as $post)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if ($post->featured_image)
                                                <img class="h-10 w-10 rounded-lg object-cover"
                                                    src="{{ asset('storage/' . $post->featured_image) }}"
                                                    alt="{{ $post->title }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                                    <i class="fas fa-newspaper text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($post->title, 50) }}
                                                </div>
                                                @if ($post->is_featured)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                        <i class="fas fa-star"></i>
                                                        {{ trans('posts.featured') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($post->status === 'published')
                                            <span
                                                class="inline-flex items-center gap-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle"></i>
                                                {{ trans('posts.published') }}
                                            </span>
                                        @elseif($post->status === 'draft')
                                            <span
                                                class="inline-flex items-center gap-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-edit"></i>
                                                {{ trans('posts.draft') }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-archive"></i>
                                                {{ trans('posts.archived') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $post->admin->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $post->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.posts.show', $post) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                title="{{ trans('posts.view_post') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.posts.edit', $post) }}"
                                                class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                                                title="{{ trans('posts.edit_post') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('{{ trans('posts.confirm_delete_post') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                    title="{{ trans('posts.delete_post') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <i class="fas fa-newspaper text-4xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ trans('posts.no_posts_exist') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ trans('posts.no_posts_description') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.posts.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i>
                            {{ trans('posts.add_new_post') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
