<div class="design-selector">
    <!-- Search and Filter Section -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Designs</label>
                <div class="relative">
                    <input type="text" id="search" wire:model.live.debounce.300ms="search"
                        placeholder="Search for designs..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select id="category" wire:model.live="selectedCategory"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sync Button -->
            <div class="flex items-end">
                <button wire:click="syncDesignsFromApi" wire:loading.attr="disabled"
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                    <span wire:loading.remove>Sync from API</span>
                    <span wire:loading>Syncing...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    @if ($isLoading)
        <div class="text-center py-8">
            <div
                class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-blue-500 hover:bg-blue-400 transition ease-in-out duration-150 cursor-not-allowed">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Loading designs...
            </div>
        </div>
    @endif

    <!-- Debug Information (remove in production) -->
    @if (config('app.debug'))
        <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h4 class="font-medium text-yellow-800 mb-2">Debug Info:</h4>
            <div class="text-sm text-yellow-700">
                <p><strong>Total Designs:</strong> {{ $designs->total() }}</p>
                <p><strong>Current Page:</strong> {{ $designs->currentPage() }}</p>
                <p><strong>Designs with Images:</strong> {{ $designs->where('thumbnail_url', '!=', null)->count() }}</p>
                <p><strong>Sample Image URL:</strong> {{ $designs->first()?->thumbnail_url ?? 'No designs found' }}</p>
            </div>
        </div>
    @endif

    <!-- Designs Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @forelse($designs as $design)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                <!-- Design Image -->
                <div class="relative aspect-square bg-gray-100">
                    @if ($design->thumbnail_url)
                        <img src="{{ $design->thumbnail_url }}" alt="{{ $design->title }}"
                            class="w-full h-full object-cover cursor-pointer"
                            wire:click="openDesignModal({{ $design->id }})"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full hidden items-center justify-center text-gray-400 bg-gray-100">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-xs text-gray-500">Image not available</p>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-xs text-gray-500">No image</p>
                            </div>
                        </div>
                    @endif

                    <!-- Selection Checkbox -->
                    <div class="absolute top-2 right-2">
                        <input type="checkbox" id="design_{{ $design->id }}"
                            wire:click="toggleDesignSelection({{ $design->id }})"
                            @if (in_array($design->id, $selectedDesigns)) checked @endif
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    </div>
                </div>

                <!-- Design Info -->
                <div class="p-3">
                    <h3 class="font-medium text-sm text-gray-900 truncate" title="{{ $design->title }}">
                        {{ $design->title }}
                    </h3>

                    @if ($design->category)
                        <p class="text-xs text-gray-500 mt-1">{{ ucfirst($design->category) }}</p>
                    @endif

                    <!-- Notes Input for Selected Design -->
                    @if (in_array($design->id, $selectedDesigns))
                        <div class="mt-2">
                            <textarea wire:model.live="designNotes.{{ $design->id }}" placeholder="Add notes..."
                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                rows="2"></textarea>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No designs found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or category filter.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($designs->hasPages())
        <div class="mt-6">
            {{ $designs->links() }}
        </div>
    @endif

    <!-- Design Modal -->
    @if ($showDesignModal && $selectedDesignForModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            wire:click="closeDesignModal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
                wire:click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $selectedDesignForModal->title }}</h3>
                    <button wire:click="closeDesignModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Design Image -->
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                        @if ($selectedDesignForModal->image_url)
                            <img src="{{ $selectedDesignForModal->image_url }}"
                                alt="{{ $selectedDesignForModal->title }}" class="w-full h-full object-cover">
                        @endif
                    </div>

                    <!-- Design Details -->
                    <div>
                        @if ($selectedDesignForModal->description)
                            <p class="text-gray-600 mb-4">{{ $selectedDesignForModal->description }}</p>
                        @endif

                        @if ($selectedDesignForModal->category)
                            <p class="text-sm text-gray-500 mb-2">
                                <span class="font-medium">Category:</span>
                                {{ ucfirst($selectedDesignForModal->category) }}
                            </p>
                        @endif

                        @if ($selectedDesignForModal->tags)
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 mb-2"><span class="font-medium">Tags:</span></p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($selectedDesignForModal->tags_array as $tag)
                                        <span
                                            class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Selection Controls -->
                        <div class="space-y-3">
                            @if (in_array($selectedDesignForModal->id, $selectedDesigns))
                                <button wire:click="removeDesign({{ $selectedDesignForModal->id }})"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    Remove from Selection
                                </button>
                            @else
                                <button wire:click="toggleDesignSelection({{ $selectedDesignForModal->id }})"
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Add to Selection
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
