<div>
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-100 text-green-700 rounded-md flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="px-2">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Search -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ trans('admin.search') }}</label>
                <input type="text" wire:model.live.debounce.500ms="search" wire:key="search-input"
                    placeholder="{{ trans('admin.search') }}..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ trans('admin.page_title') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ trans('admin.page_type') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ trans('admin.access_level') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ trans('admin.page_slug') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ trans('admin.status') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ trans('admin.page_created_at') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ trans('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pages as $page)
                        <tr class="hover:bg-gray-50" wire:key="page-row-{{ $page->id }}">
                            <td class="p-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                            <i class="fas fa-file-alt text-white text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="mr-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $page->localized_title }}</div>
                                        @if($page->title_ar && $page->title)
                                            <div class="text-sm text-gray-500">
                                                @if(app()->getLocale() === 'ar') {{ $page->title }} @else {{ $page->title_ar }} @endif
                                            </div>
                                        @endif
                                        <div class="text-xs text-gray-400 mt-1">{{ Str::limit(strip_tags($page->localized_content), 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($page->type === 'page') bg-blue-100 text-blue-800
                                    @elseif($page->type === 'section') bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    @if($page->type === 'page') <i class="fas fa-file-alt mr-1"></i> {{ trans('admin.page_type_page') }}
                                    @elseif($page->type === 'section') <i class="fas fa-th-large mr-1"></i> {{ trans('admin.page_type_section') }}
                                    @else <i class="fas fa-window-restore mr-1"></i> {{ trans('admin.page_type_modal') }} @endif
                                </span>
                            </td>
                            <td class="p-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($page->access_level === 'public') bg-green-100 text-green-800
                                    @elseif($page->access_level === 'authenticated') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($page->access_level === 'public') <i class="fas fa-globe mr-1"></i> {{ trans('admin.access_level_public') }}
                                    @elseif($page->access_level === 'authenticated') <i class="fas fa-user mr-1"></i> {{ trans('admin.access_level_authenticated') }}
                                    @else <i class="fas fa-shield-alt mr-1"></i> {{ trans('admin.access_level_admin') }} @endif
                                </span>
                            </td>
                            <td class="p-3 whitespace-nowrap text-center text-sm text-gray-900">
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $page->slug }}</code>
                            </td>
                            <td class="p-3 whitespace-nowrap text-center">
                                @if ($page->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> {{ trans('admin.page_status_active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> {{ trans('admin.page_status_inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 whitespace-nowrap text-center text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <span>{{ $page->created_at->format('Y-m-d') }}</span>
                                    <span class="text-xs text-gray-400">{{ $page->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.pages.show', $page) }}"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 transition duration-150 ease-in-out">
                                        <i class="fas fa-eye"></i> <span>{{ trans('admin.view') }}</span>
                                    </a>
                                    <a href="{{ route('admin.pages.edit', $page) }}"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition duration-150 ease-in-out">
                                        <i class="fas fa-pen"></i> <span>{{ trans('admin.edit') }}</span>
                                    </a>
                                    <button wire:click="confirmDelete({{ $page->id }})" wire:key="delete-{{ $page->id }}"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition duration-150 ease-in-out">
                                        <i class="fas fa-trash"></i> <span>{{ trans('admin.delete') }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-file-alt text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ trans('admin.no_pages_found') }}</h3>
                                    <p class="text-gray-500 mb-4">{{ trans('admin.create_your_first_page') }}</p>
                                    <a href="{{ route('admin.pages.create') }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <i class="fas fa-plus mr-2"></i> {{ trans('admin.add_new_page') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200">{{ $pages->links() }}</div>
    </div>

    <!-- Delete Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">{{ trans('admin.confirm_delete_page') }}</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">{{ trans('admin.confirm_delete_page') }}</p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button wire:click="deletePage" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 disabled:opacity-50">
                            <span wire:loading.remove wire:target="deletePage">{{ trans('admin.delete') }}</span>
                            <span wire:loading wire:target="deletePage" class="inline-flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                        <button wire:click="cancelDelete" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 disabled:opacity-50">
                            {{ trans('admin.cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('modal-closed', () => {
                console.log('Modal closed event received');
            });
        });
    </script>
</div>

