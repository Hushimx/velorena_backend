<div class="design-selector">
    <!-- Search and Filter Section -->
    <div class="mb-4 bg-white rounded shadow p-3">
        <div class="row">
            <!-- Search Input -->
            <div class="col-md-4 mb-3">
                <label for="search" class="form-label">Search Designs</label>
                <div class="input-group">
                    <input type="text" id="search" wire:model="search"
                        placeholder="Search for designs..." class="form-control">
                    <button wire:click="clearSearch" class="btn btn-outline-secondary" type="button" title="Clear search">
                        <i class="fas fa-times"></i>
                    </button>
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="col-md-4 mb-3">
                <label for="category" class="form-label">Category</label>
                <select id="category" wire:model="selectedCategory" class="form-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search Button -->
            <div class="col-md-4 mb-3 d-flex align-items-end">
                <button wire:click="performSearch" wire:loading.attr="disabled" class="btn btn-primary w-100">
                    <span wire:loading.remove>
                        <i class="fas fa-search me-2"></i>Search Designs
                    </span>
                    <span wire:loading>
                        <i class="fas fa-spinner fa-spin me-2"></i>Searching...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    @if ($isLoading)
        <div class="text-center py-4">
            <div class="d-inline-flex align-items-center px-3 py-2 bg-primary text-white rounded">
                <i class="fas fa-spinner fa-spin me-2"></i>
                Loading designs...
            </div>
        </div>
    @endif


    <!-- Designs Grid -->
    <div class="row g-3">
        @forelse($designs as $design)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="card h-100 shadow-sm design-card">
                    <!-- Design Image -->
                    <div class="position-relative design-image-container">
                        @php
                            $designId = $useApiDesigns ? $design['id'] : $design->id;
                            $designTitle = $useApiDesigns ? $design['title'] : $design->title;
                            $designThumbnail = $useApiDesigns ? $design['thumbnail_url'] : $design->thumbnail_url;
                            $designCategory = $useApiDesigns ? $design['category'] : $design->category;
                        @endphp
                        
                        @if ($designThumbnail)
                            <img src="{{ $designThumbnail }}" alt="{{ $designTitle }}"
                                class="card-img-top design-image" wire:click="openDesignModal({{ $designId }})"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="design-placeholder d-none align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                    <p class="small text-muted">Image not available</p>
                                </div>
                            </div>
                        @else
                            <div class="design-placeholder d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                    <p class="small text-muted">No image</p>
                                </div>
                            </div>
                        @endif

                        <!-- Selection Checkbox -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <div class="form-check">
                                <input type="checkbox" id="design_{{ $designId }}"
                                    wire:click="toggleDesignSelection({{ $designId }})"
                                    @if (in_array($designId, $selectedDesigns)) checked @endif class="form-check-input">
                            </div>
                        </div>
                    </div>

                    <!-- Design Info -->
                    <div class="card-body p-2">
                        <h6 class="card-title small mb-1 text-truncate" title="{{ $designTitle }}">
                            {{ $designTitle }}
                        </h6>

                        @if ($designCategory)
                            <p class="small text-muted mb-2">{{ ucfirst($designCategory) }}</p>
                        @endif

                        <!-- Notes Input for Selected Design -->
                        @if (in_array($designId, $selectedDesigns))
                            <div class="mt-2">
                                <textarea wire:model.live="designNotes.{{ $designId }}" placeholder="Add notes..."
                                    class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-images fa-3x mb-3"></i>
                    <h5>No designs found</h5>
                    <p>Try adjusting your search or category filter.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($designs->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $designs->links() }}
        </div>
    @endif

    <!-- Design Modal -->
    @if ($showDesignModal && $selectedDesignForModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" wire:click="closeDesignModal">
            <div class="modal-dialog modal-lg modal-dialog-centered" wire:click.stop>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $useApiDesigns ? $selectedDesignForModal['title'] : $selectedDesignForModal->title }}
                        </h5>
                        <button type="button" wire:click="closeDesignModal" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Design Image -->
                            <div class="col-md-6">
                                <div class="ratio ratio-1x1">
                                    @php
                                        $modalImageUrl = $useApiDesigns ? $selectedDesignForModal['image_url'] : $selectedDesignForModal->image_url;
                                        $modalTitle = $useApiDesigns ? $selectedDesignForModal['title'] : $selectedDesignForModal->title;
                                        $modalDescription = $useApiDesigns ? $selectedDesignForModal['description'] : $selectedDesignForModal->description;
                                        $modalCategory = $useApiDesigns ? $selectedDesignForModal['category'] : $selectedDesignForModal->category;
                                        $modalTags = $useApiDesigns ? $selectedDesignForModal['tags'] : $selectedDesignForModal->tags;
                                        $modalId = $useApiDesigns ? $selectedDesignForModal['id'] : $selectedDesignForModal->id;
                                    @endphp
                                    
                                    @if ($modalImageUrl)
                                        <img src="{{ $modalImageUrl }}" alt="{{ $modalTitle }}" class="rounded">
                                    @endif
                                </div>
                            </div>

                            <!-- Design Details -->
                            <div class="col-md-6">
                                @if ($modalDescription)
                                    <p class="text-muted mb-3">{{ $modalDescription }}</p>
                                @endif

                                @if ($modalCategory)
                                    <p class="small mb-2">
                                        <strong>Category:</strong> {{ ucfirst($modalCategory) }}
                                    </p>
                                @endif

                                @if ($modalTags)
                                    <div class="mb-3">
                                        <p class="small mb-2"><strong>Tags:</strong></p>
                                        <div class="d-flex flex-wrap gap-1">
                                            @if ($useApiDesigns)
                                                @foreach ($modalTags as $tag)
                                                    <span class="badge bg-light text-dark">{{ $tag }}</span>
                                                @endforeach
                                            @else
                                                @foreach ($selectedDesignForModal->tags_array as $tag)
                                                    <span class="badge bg-light text-dark">{{ $tag }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Selection Controls -->
                                <div class="mt-4">
                                    @if (in_array($modalId, $selectedDesigns))
                                        <button wire:click="removeDesign({{ $modalId }})" class="btn btn-danger w-100">
                                            <i class="fas fa-times me-2"></i>Remove from Selection
                                        </button>
                                    @else
                                        <button wire:click="toggleDesignSelection({{ $modalId }})" class="btn btn-primary w-100">
                                            <i class="fas fa-plus me-2"></i>Add to Selection
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
