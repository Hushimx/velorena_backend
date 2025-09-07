@extends('layouts.app')

@section('pageTitle', 'Select Designs for ' . $product->name)
@section('title', 'Select Designs for ' . $product->name)

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-palette text-purple-600 mr-3"></i>
                        Select Designs for {{ $product->name }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Choose designs that inspire your vision for this product
                    </p>
                </div>
                <a href="{{ route('cart.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg inline-flex items-center transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Cart
                </a>
            </div>
        </div>

        <!-- Product Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center space-x-4">
                <img src="{{ $product->image ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2NjY2NjYyIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjEyIiBmaWxsPSIjNjY2NjY2IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+UHJvZHVjdDwvdGV4dD48L3N2Zz4=' }}"
                    alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                    <p class="text-gray-600">{{ $product->description }}</p>
                    <p class="text-sm text-gray-500 mt-1">Base Price: ${{ number_format($product->base_price, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Selected Designs Summary -->
        @if ($selectedDesigns->count() > 0)
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-purple-800 mb-4 flex items-center">
                    <i class="fas fa-check-circle text-purple-600 mr-2"></i>
                    Selected Designs ({{ $selectedDesigns->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($selectedDesigns as $productDesign)
                        <div class="bg-white rounded-lg p-4 border border-purple-200">
                            @if ($productDesign->design)
                                <div class="flex items-start space-x-3">
                                    <img src="{{ $productDesign->design->thumbnail_url ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjYwIiBoZWlnaHQ9IjYwIiBmaWxsPSIjY2NjY2NjIi8+PHRleHQgeD0iMzAiIHk9IjMwIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTAiIGZpbGw9IiM2NjY2NjYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5EZXNpZ248L3RleHQ+PC9zdmc+' }}"
                                        alt="{{ $productDesign->design->title }}" class="w-12 h-12 object-cover rounded">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 text-sm truncate">
                                            {{ $productDesign->design->title }}</h4>
                                        <p class="text-xs text-gray-500">Priority: {{ $productDesign->priority }}</p>
                                        @if ($productDesign->notes)
                                            <p class="text-xs text-gray-600 mt-1">
                                                {{ Str::limit($productDesign->notes, 50) }}
                                            </p>
                                        @endif
                                    </div>
                                    <form
                                        action="{{ route('user.product.designs.destroy', ['product' => $product->id, 'design' => $productDesign->design->id]) }}"
                                        method="POST" class="ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Design Selection Form -->
        <form action="{{ route('user.product.designs.store', $product) }}" method="POST" id="designForm">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <!-- Search and Filter -->
                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search Input -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search
                                Designs</label>
                            <div class="relative">
                                <input type="text" id="search" name="search" placeholder="Search for designs..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select id="category" name="category"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sync Button -->
                        <div class="flex items-end">
                            <button type="button" id="syncDesigns"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md inline-flex items-center justify-center transition-colors duration-200">
                                <i class="fas fa-sync mr-2"></i>
                                Sync from API
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Designs Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 max-h-96 overflow-y-auto" id="designsGrid">
                    @foreach ($designs as $design)
                        <div class="design-item border rounded-lg overflow-hidden hover:shadow-md transition-shadow cursor-pointer border-gray-200 bg-white"
                            data-design-id="{{ $design->id }}">

                            <!-- Design Image -->
                            <div class="aspect-square bg-gray-100 relative">
                                @if ($design->thumbnail_url)
                                    <img src="{{ $design->thumbnail_url }}" alt="{{ $design->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    </div>
                                @endif

                                <!-- Selection Checkbox -->
                                <div class="absolute top-2 right-2">
                                    <input type="checkbox" name="selected_designs[]" value="{{ $design->id }}"
                                        class="design-checkbox w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                </div>
                            </div>

                            <!-- Design Info -->
                            <div class="p-2">
                                <h5 class="font-medium text-xs text-gray-900 truncate" title="{{ $design->title }}">
                                    {{ $design->title }}
                                </h5>
                                @if ($design->category)
                                    <p class="text-xs text-gray-500 mt-1">{{ ucfirst($design->category) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Selected Designs Notes -->
                <div id="selectedDesignsNotes" class="mt-6 hidden">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Add Notes for Selected Designs</h4>
                    <div id="notesContainer" class="space-y-4">
                        <!-- Notes will be added here dynamically -->
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 text-center">
                    <button type="submit" id="submitBtn" disabled
                        class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-8 py-3 rounded-lg inline-flex items-center transition-colors duration-200 text-lg font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Save Design Selections
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.design-checkbox');
            const submitBtn = document.getElementById('submitBtn');
            const selectedDesignsNotes = document.getElementById('selectedDesignsNotes');
            const notesContainer = document.getElementById('notesContainer');
            const form = document.getElementById('designForm');

            // Handle checkbox changes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedDesigns();
                });
            });

            function updateSelectedDesigns() {
                const selected = Array.from(checkboxes).filter(cb => cb.checked);

                // Update submit button
                submitBtn.disabled = selected.length === 0;

                // Show/hide notes section
                if (selected.length > 0) {
                    selectedDesignsNotes.classList.remove('hidden');
                    generateNotesInputs(selected);
                } else {
                    selectedDesignsNotes.classList.add('hidden');
                }
            }

            function generateNotesInputs(selected) {
                notesContainer.innerHTML = '';

                selected.forEach((checkbox, index) => {
                    const designItem = checkbox.closest('.design-item');
                    const designTitle = designItem.querySelector('h5').textContent;

                    const noteDiv = document.createElement('div');
                    noteDiv.className = 'bg-gray-50 rounded-lg p-4';
                    noteDiv.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-full text-sm font-medium">
                            ${index + 1}
                        </span>
                    </div>
                    <div class="flex-1">
                        <h5 class="font-medium text-gray-900 mb-2">${designTitle}</h5>
                        <textarea name="designs[${index}][notes]"
                            placeholder="Add notes for this design..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            rows="2"></textarea>
                        <input type="hidden" name="designs[${index}][design_id]" value="${checkbox.value}">
                        <input type="hidden" name="designs[${index}][priority]" value="${index + 1}">
                    </div>
                </div>
            `;
                    notesContainer.appendChild(noteDiv);
                });
            }

            // Sync designs from API
            document.getElementById('syncDesigns').addEventListener('click', function() {
                const search = document.getElementById('search').value;
                const category = document.getElementById('category').value;

                fetch('{{ route('user.product.designs', $product) }}/sync', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            search,
                            category
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Failed to sync designs: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while syncing designs');
                    });
            });
        });
    </script>
@endsection
