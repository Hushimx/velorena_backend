<div class="design-selector">
    <!-- Search and Filter Section -->
    <div class="mb-4 bg-white rounded shadow p-3">
        <div class="row">
            <!-- Search Input -->
            <div class="col-md-4 mb-3">
                <label for="search" class="form-label">Search Designs</label>
                <div class="input-group">
                    <input type="text" id="search" wire:model.live.debounce.300ms="search"
                        placeholder="Search for designs..." class="form-control">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="col-md-4 mb-3">
                <label for="category" class="form-label">Category</label>
                <select id="category" wire:model.live="selectedCategory" class="form-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sync Button -->
            <div class="col-md-4 mb-3 d-flex align-items-end">
                <button wire:click="syncDesignsFromApi" wire:loading.attr="disabled" class="btn btn-primary w-100">
                    <span wire:loading.remove>
                        <i class="fas fa-sync-alt me-2"></i>Sync from API
                    </span>
                    <span wire:loading>
                        <i class="fas fa-spinner fa-spin me-2"></i>Syncing...
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
                        @if ($design->thumbnail_url)
                            <img src="{{ $design->thumbnail_url }}" alt="{{ $design->title }}"
                                class="card-img-top design-image" wire:click="openDesignModal({{ $design->id }})"
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
                                <input type="checkbox" id="design_{{ $design->id }}"
                                    wire:click="toggleDesignSelection({{ $design->id }})"
                                    @if (in_array($design->id, $selectedDesigns)) checked @endif class="form-check-input">
                            </div>
                        </div>
                    </div>

                    <!-- Design Info -->
                    <div class="card-body p-2">
                        <h6 class="card-title small mb-1 text-truncate" title="{{ $design->title }}">
                            {{ $design->title }}
                        </h6>

                        @if ($design->category)
                            <p class="small text-muted mb-2">{{ ucfirst($design->category) }}</p>
                        @endif

                        <!-- Notes Input for Selected Design -->
                        @if (in_array($design->id, $selectedDesigns))
                            <div class="mt-2">
                                <textarea wire:model.live="designNotes.{{ $design->id }}" placeholder="Add notes..."
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
                        <h5 class="modal-title">{{ $selectedDesignForModal->title }}</h5>
                        <button type="button" wire:click="closeDesignModal" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Design Image -->
                            <div class="col-md-6">
                                <div class="ratio ratio-1x1">
                                    @if ($selectedDesignForModal->image_url)
                                        <img src="{{ $selectedDesignForModal->image_url }}"
                                            alt="{{ $selectedDesignForModal->title }}" class="rounded">
                                    @endif
                                </div>
                            </div>

                            <!-- Design Details -->
                            <div class="col-md-6">
                                @if ($selectedDesignForModal->description)
                                    <p class="text-muted mb-3">{{ $selectedDesignForModal->description }}</p>
                                @endif

                                @if ($selectedDesignForModal->category)
                                    <p class="small mb-2">
                                        <strong>Category:</strong> {{ ucfirst($selectedDesignForModal->category) }}
                                    </p>
                                @endif

                                @if ($selectedDesignForModal->tags)
                                    <div class="mb-3">
                                        <p class="small mb-2"><strong>Tags:</strong></p>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($selectedDesignForModal->tags_array as $tag)
                                                <span class="badge bg-light text-dark">{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Selection Controls -->
                                <div class="mt-4">
                                    @if (in_array($selectedDesignForModal->id, $selectedDesigns))
                                        <button wire:click="removeDesign({{ $selectedDesignForModal->id }})"
                                            class="btn btn-danger w-100">
                                            <i class="fas fa-times me-2"></i>Remove from Selection
                                        </button>
                                    @else
                                        <button wire:click="toggleDesignSelection({{ $selectedDesignForModal->id }})"
                                            class="btn btn-primary w-100">
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
