<div class="design-selector">
    <!-- Brand Enhanced Search Section -->
    <div class="mb-4 design-search-section">
        <div class="search-header">
            <h4 class="search-title">
                <i class="fas fa-palette me-2"></i>Design Studio
            </h4>
            <p class="search-subtitle">Find and customize the perfect design for your project</p>
        </div>
        
        <div class="search-controls">
            <div class="row g-3">
                <!-- Enhanced Search Input -->
                <div class="col-md-5 mb-3">
                    <label for="search" class="form-label">Search Designs</label>
                    <div class="search-input-group">
                        <div class="search-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" id="search" wire:model="search"
                            placeholder="Search for designs, mockups, templates..." class="search-input">
                        <button wire:click="clearSearch" class="clear-search-btn" type="button" title="Clear search">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Enhanced Category Filter -->
                <div class="col-md-3 mb-3">
                    <label for="category" class="form-label">Category</label>
                    <div class="category-select-wrapper">
                        <select id="category" wire:model="selectedCategory" class="category-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down category-arrow"></i>
                    </div>
                </div>

                <!-- Enhanced Search Button -->
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button wire:click="performSearch" wire:loading.attr="disabled" class="search-btn w-100">
                        <span wire:loading.remove class="btn-content">
                            <i class="fas fa-search me-2"></i>
                            <span>Search Designs</span>
                        </span>
                        <span wire:loading class="btn-content">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            <span>Searching...</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Loading State -->
    @if ($isLoading)
        <div class="loading-section">
            <div class="loading-content">
                <div class="loading-spinner">
                    <i class="fas fa-palette fa-spin"></i>
                </div>
                <h5 class="loading-text">Finding amazing designs...</h5>
                <p class="loading-subtext">Please wait while we search through thousands of designs</p>
            </div>
        </div>
    @endif

    <!-- Enhanced Designs Grid -->
    <div class="designs-grid">
        @forelse($designs as $design)
            <div class="design-item">
                <div class="design-card">
                    <!-- Design Image Container -->
                    <div class="design-image-wrapper">
                        @php
                            $designId = $useApiDesigns ? $design['id'] : $design->id;
                            $designTitle = $useApiDesigns ? $design['title'] : $design->title;
                            $designThumbnail = $useApiDesigns ? $design['thumbnail_url'] : $design->thumbnail_url;
                            $designCategory = $useApiDesigns ? $design['category'] : $design->category;
                        @endphp
                        
                        @if ($designThumbnail)
                            <img src="{{ $designThumbnail }}" alt="{{ $designTitle }}"
                                class="design-image" wire:click="openDesignModal({{ $designId }})"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="design-placeholder design-placeholder-hidden">
                                <div class="placeholder-content">
                                    <i class="fas fa-image"></i>
                                    <span>Image not available</span>
                                </div>
                            </div>
                        @else
                            <div class="design-placeholder">
                                <div class="placeholder-content">
                                    <i class="fas fa-image"></i>
                                    <span>No image</span>
                                </div>
                            </div>
                        @endif

                        <!-- Enhanced Selection Checkbox -->
                        <div class="design-selection">
                            <label class="design-checkbox">
                                <input type="checkbox" wire:click="toggleDesignSelection({{ $designId }})"
                                    @if (in_array($designId, $selectedDesigns)) checked @endif>
                                <span class="checkmark">
                                    <i class="fas fa-check"></i>
                                </span>
                            </label>
                        </div>

                        <!-- Hover Actions -->
                        <div class="design-actions">
                            <button class="action-btn edit-btn" wire:click="openDesignModal({{ $designId }})">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </button>
                            <button class="action-btn preview-btn" wire:click="openDesignModal({{ $designId }})">
                                <i class="fas fa-eye"></i>
                                <span>Preview</span>
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Design Info -->
                    <div class="design-info">
                        <h6 class="design-title" title="{{ $designTitle }}">
                            {{ $designTitle }}
                        </h6>

                        @if ($designCategory)
                            <div class="design-category">
                                <i class="fas fa-tag"></i>
                                <span>{{ ucfirst($designCategory) }}</span>
                            </div>
                        @endif

                        <!-- Enhanced Notes Input for Selected Design -->
                        @if (in_array($designId, $selectedDesigns))
                            <div class="design-notes">
                                <textarea wire:model.live="designNotes.{{ $designId }}" 
                                    placeholder="Add your notes here..."
                                    class="notes-input" rows="2"></textarea>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-content">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h5 class="empty-title">No designs found</h5>
                    <p class="empty-text">Try adjusting your search terms or browse different categories</p>
                    <button wire:click="clearSearch" class="empty-action-btn">
                        <i class="fas fa-refresh me-2"></i>Reset Search
                    </button>
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

    <!-- Enhanced Design Studio Modal -->
    @if ($showDesignModal && $selectedDesignForModal)
        <div class="design-studio-modal" wire:click="closeDesignModal">
            <div class="studio-container" wire:click.stop>
                @php
                    $modalImageUrl = $useApiDesigns ? $selectedDesignForModal['image_url'] : $selectedDesignForModal->image_url;
                    $modalTitle = $useApiDesigns ? $selectedDesignForModal['title'] : $selectedDesignForModal->title;
                    $modalDescription = $useApiDesigns ? $selectedDesignForModal['description'] : $selectedDesignForModal->description;
                    $modalCategory = $useApiDesigns ? $selectedDesignForModal['category'] : $selectedDesignForModal->category;
                    $modalTags = $useApiDesigns ? $selectedDesignForModal['tags'] : $selectedDesignForModal->tags;
                    $modalId = $useApiDesigns ? $selectedDesignForModal['id'] : $selectedDesignForModal->id;
                @endphp
                
                <!-- Studio Header -->
                <div class="studio-header">
                    <div class="header-left">
                        <div class="studio-logo">
                            <i class="fas fa-palette"></i>
                        </div>
                        <div class="header-info">
                            <h4 class="studio-title">Design Studio</h4>
                            <p class="studio-subtitle">{{ $modalTitle }}</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="header-btn save-btn">
                            <i class="fas fa-save"></i>
                            <span>Save</span>
                        </button>
                        <button class="header-btn download-btn">
                            <i class="fas fa-download"></i>
                            <span>Download</span>
                        </button>
                        <button class="header-btn close-btn" wire:click="closeDesignModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Studio Content -->
                <div class="studio-content">
                    <!-- Left Panel - Tools -->
                    <div class="studio-sidebar">
                        <div class="tools-section">
                            <h5 class="section-title">
                                <i class="fas fa-tools"></i>
                                Tools
                            </h5>
                            
                            <!-- Design Tools -->
                            <div class="tool-group">
                                <button class="tool-btn active" data-tool="select">
                                    <i class="fas fa-mouse-pointer"></i>
                                    <span>Select</span>
                                </button>
                                <button class="tool-btn" data-tool="text">
                                    <i class="fas fa-font"></i>
                                    <span>Text</span>
                                </button>
                                <button class="tool-btn" data-tool="shapes">
                                    <i class="fas fa-shapes"></i>
                                    <span>Shapes</span>
                                </button>
                                <button class="tool-btn" data-tool="logo">
                                    <i class="fas fa-crown"></i>
                                    <span>Logo</span>
                                </button>
                                <button class="tool-btn" data-tool="images">
                                    <i class="fas fa-images"></i>
                                    <span>Images</span>
                                </button>
                                <button class="tool-btn" data-tool="filters">
                                    <i class="fas fa-magic"></i>
                                    <span>Filters</span>
                                </button>
                            </div>
                        </div>

                        <!-- Properties Panel -->
                        <div class="properties-section">
                            <h5 class="section-title">
                                <i class="fas fa-sliders-h"></i>
                                Properties
                            </h5>
                            
                            <div class="property-group">
                                <label class="property-label">Opacity</label>
                                <input type="range" class="property-slider" min="0" max="100" value="100">
                                <span class="property-value">100%</span>
                            </div>
                            
                            <div class="property-group">
                                <label class="property-label">Size</label>
                                <div class="size-controls">
                                    <input type="number" class="size-input" placeholder="W" value="800">
                                    <span class="size-separator">×</span>
                                    <input type="number" class="size-input" placeholder="H" value="600">
                                </div>
                            </div>
                            
                            <div class="property-group">
                                <label class="property-label">Colors</label>
                                <div class="color-palette">
                                    <div class="color-item" style="background: var(--brand-yellow);" data-color="brand-yellow"></div>
                                    <div class="color-item" style="background: var(--brand-brown);" data-color="brand-brown"></div>
                                    <div class="color-item" style="background: #ffffff;" data-color="white"></div>
                                    <div class="color-item" style="background: #000000;" data-color="black"></div>
                                    <div class="color-item" style="background: #ff6b6b;" data-color="red"></div>
                                    <div class="color-item" style="background: #4ecdc4;" data-color="teal"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Logo Upload Section -->
                        <div class="logo-section">
                            <h5 class="section-title">
                                <i class="fas fa-upload"></i>
                                Your Logo
                            </h5>
                            <div class="logo-upload">
                                <div class="upload-area">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Drop your logo here or click to browse</p>
                                    <input type="file" class="logo-input" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Center Panel - Canvas -->
                    <div class="studio-canvas">
                        <div class="canvas-container">
                            <div class="canvas-toolbar">
                                <div class="zoom-controls">
                                    <button class="zoom-btn" data-zoom="out">
                                        <i class="fas fa-search-minus"></i>
                                    </button>
                                    <span class="zoom-level">100%</span>
                                    <button class="zoom-btn" data-zoom="in">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                </div>
                                <div class="canvas-actions">
                                    <button class="canvas-btn" data-action="undo">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button class="canvas-btn" data-action="redo">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button class="canvas-btn" data-action="reset">
                                        <i class="fas fa-refresh"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="design-canvas" id="designCanvas">
                                @if ($modalImageUrl)
                                    <img src="{{ $modalImageUrl }}" alt="{{ $modalTitle }}" class="canvas-image" id="canvasImage">
                                @endif
                                
                                <!-- Overlay elements will be added here via JavaScript -->
                                <div class="canvas-overlays" id="canvasOverlays"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Layers & Templates -->
                    <div class="studio-rightbar">
                        <div class="layers-section">
                            <h5 class="section-title">
                                <i class="fas fa-layer-group"></i>
                                Layers
                            </h5>
                            <div class="layers-list">
                                <div class="layer-item active">
                                    <i class="fas fa-image"></i>
                                    <span>Background</span>
                                    <div class="layer-actions">
                                        <button class="layer-action">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="layer-action">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="templates-section">
                            <h5 class="section-title">
                                <i class="fas fa-magic"></i>
                                Quick Actions
                            </h5>
                            <div class="quick-actions">
                                <button class="quick-action-btn">
                                    <i class="fas fa-font"></i>
                                    <span>Add Text</span>
                                </button>
                                <button class="quick-action-btn">
                                    <i class="fas fa-crown"></i>
                                    <span>Add Logo</span>
                                </button>
                                <button class="quick-action-btn">
                                    <i class="fas fa-square"></i>
                                    <span>Add Shape</span>
                                </button>
                                <button class="quick-action-btn">
                                    <i class="fas fa-palette"></i>
                                    <span>Change Colors</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Studio Footer -->
                <div class="studio-footer">
                    <div class="footer-left">
                        <div class="design-info">
                            @if ($modalCategory)
                                <span class="info-badge">
                                    <i class="fas fa-tag"></i>
                                    {{ ucfirst($modalCategory) }}
                                </span>
                            @endif
                            @if ($modalTags && $useApiDesigns)
                                @foreach (array_slice($modalTags, 0, 3) as $tag)
                                    <span class="info-badge">{{ $tag }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="footer-actions">
                        <button wire:click="addToCart({{ $modalId }})" class="footer-btn add-cart-btn">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </button>
                        <button class="footer-btn primary-btn" wire:click="closeDesignModal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
