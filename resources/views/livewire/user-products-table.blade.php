<div>
    <div class="products-page-layout">
        <div class="container-fluid">
            <div class="row">
            <!-- Main Content Area -->
            <div class="col-lg-8 col-md-7">
                    <!-- Top Bar with Sort and Results Count -->
                    <div class="products-top-bar">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                        <!-- Results Count and Filter Toggle -->
                        <div class="results-controls w-100">
                            <h2 class="results-title">
                                {{ trans('products.available_products') }}
                                <span class="results-count">{{ $products->total() }}</span>
                            </h2>
                            <button class="filter-toggle-btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#filtersOffcanvas">
                                {{ trans('products.filter') }}: {{ trans('products.show_more') }}
                            </button>
                        </div>
                    </div>
    </div>

    <!-- Products Grid -->
                    <div class="products-grid">
        @if ($products->total() > 0)
                <div class="row">
                    @forelse ($products as $product)
                                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4" wire:key="product-card-{{ $product->id }}">
                            <a href="{{ route('user.products.show', $product) }}" class="product-link" wire:ignore>
                                <div class="product-card">
                                    <div class="product-image">
                                        @php
                                            $productImage = null;
                                            // Try to get main product image first (image_url)
                                            if ($product->image_url && file_exists(public_path($product->image_url))) {
                                                $productImage = asset($product->image_url);
                                            } else {
                                                // Fallback to first additional image
                                                $primaryImage = $product->images()->orderBy('sort_order')->first();
                                                if ($primaryImage && file_exists(public_path($primaryImage->image_path))) {
                                                    $productImage = asset($primaryImage->image_path);
                                                } else {
                                                    // Fallback to first image
                                                    $firstImage = $product->images()->first();
                                                    if ($firstImage && file_exists(public_path($firstImage->image_path))) {
                                                        $productImage = asset($firstImage->image_path);
                                                    } elseif (
                                                        $product->image &&
                                                        file_exists(public_path($product->image))
                                                    ) {
                                                        $productImage = asset($product->image);
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if ($productImage)
                                                        <img src="{{ $productImage }}" alt="{{ $product->name_ar ?: $product->name }}" class="img-fluid"
                                                onload="this.classList.add('loaded')"
                                                onerror="this.src='https://placehold.co/300x200/f8f9fa/6c757d?text=No+Image'; this.classList.add('loaded')"
                                                loading="lazy">
                                        @else
                                            <img src="https://placehold.co/300x200/f8f9fa/6c757d?text=No+Image"
                                                            alt="{{ $product->name_ar ?: $product->name }}" class="img-fluid"
                                                onload="this.classList.add('loaded')" loading="lazy">
                                        @endif
                                    </div>
                                                <div class="product-info">
                                                    <h5 class="product-name">{{ $product->name_ar ?: $product->name }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="no-products-section">
                                            <div class="text-center">
                                            <div class="no-products-icon">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                            <h3 class="no-products-title">
                                                    {{ trans('products.no_products_found') }}
                                            </h3>
                                            <p class="no-products-description">
                                                    {{ trans('products.no_products_found_description') }}
                                            </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
            </div>

                            <!-- Pagination (Bottom) -->
            @if ($products->hasPages())
                                <div class="pagination-bottom mt-4">
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="no-products-section">
                                <div class="text-center">
                            <div class="no-products-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h3 class="no-products-title">
                                {{ trans('products.no_products_found') }}
                            </h3>
                            <p class="no-products-description">
                                {{ trans('products.no_products_found_description') }}
                            </p>
                        </div>
                            </div>
                        @endif
                    </div>
                </div>

            <!-- Sidebar Filters -->
            <div class="col-lg-4 col-md-5 d-none d-md-block">
                    <div class="filters-sidebar">
                        <div class="filters-header">
                            <h4>{{ trans('products.filters') }}</h4>
                            @if($search || $selectedCategories)
                                <button wire:click="clearFilters" class="clear-filters-btn" type="button">
                                    <i class="fas fa-times"></i>
                                    {{ trans('products.clear_filters') }}
                                </button>
                            @endif
                        </div>

                        <!-- Search Filter -->
                        <div class="filter-section">
                            <div class="filter-group">
                                <label for="search" class="filter-label">
                                    <i class="fas fa-search"></i>
                                    {{ trans('products.search') }}
                                </label>
                                <input type="text" wire:model.live.debounce.500ms="search" wire:key="search-input"
                                    placeholder="{{ trans('products.search_products_placeholder') }}" class="search-input">
                            </div>
                        </div>

                        <!-- Categories Filter -->
                        <div class="filter-section">
                            <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#categoriesFilter" aria-expanded="true">
                                <h5>{{ trans('products.categories') }}</h5>
                                <i class="fas fa-chevron-up"></i>
                            </div>
                            <div class="collapse show" id="categoriesFilter">
                                <div class="filter-content">
                                    @foreach ($categories as $category)
                                        <div class="filter-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                    wire:model.live="selectedCategories" 
                                                    value="{{ $category->id }}" 
                                                    id="category-{{ $category->id }}">
                                                <label class="form-check-label" for="category-{{ $category->id }}">
                                                    {{ $category->name_ar ?: $category->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Show More Button for Categories -->
                        @if($categories->count() > 5)
                            <div class="show-more-section">
                                <button class="show-more-btn" type="button">
                                    {{ trans('products.show_more') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Offcanvas Filters -->
        <div class="offcanvas offcanvas-end d-md-none" tabindex="-1" id="filtersOffcanvas">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">{{ trans('products.filters') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Mobile Search Filter -->
                <div class="filter-section">
                    <div class="filter-group">
                        <label for="search-mobile" class="filter-label">
                            <i class="fas fa-search"></i>
                            {{ trans('products.search') }}
                        </label>
                        <input type="text" wire:model.live.debounce.500ms="search" wire:key="search-input-mobile"
                            placeholder="{{ trans('products.search_products_placeholder') }}" class="search-input">
                    </div>
                </div>

                <!-- Mobile Categories Filter -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#categoriesFilterMobile" aria-expanded="true">
                        <h5>{{ trans('products.categories') }}</h5>
                        <i class="fas fa-chevron-up"></i>
                    </div>
                    <div class="collapse show" id="categoriesFilterMobile">
                        <div class="filter-content">
                            @foreach ($categories as $category)
                                <div class="filter-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                            wire:model.live="selectedCategories" 
                                            value="{{ $category->id }}" 
                                            id="category-mobile-{{ $category->id }}">
                                        <label class="form-check-label" for="category-mobile-{{ $category->id }}">
                                            {{ $category->name_ar ?: $category->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Mobile Clear Filters -->
                @if($search || $selectedCategories)
                    <div class="filter-actions-mobile">
                        <button wire:click="clearFilters" class="clear-filters-btn" type="button">
                            <i class="fas fa-times"></i>
                            {{ trans('products.clear_filters') }}
                        </button>
            </div>
        @endif
            </div>
        </div>
    </div>

    <style>
        /* CSS Variables for Brand Colors */
        :root {
            --brand-yellow: #ffde9f;
            --brand-yellow-light: #fff4e6;
            --brand-yellow-dark: #f5d182;
            --brand-yellow-hover: #f0d4a0;
            --brand-brown: #2a1e1e;
            --brand-brown-light: #3a2e2e;
            --brand-brown-dark: #1a1414;
            --brand-brown-hover: #4a3e3e;
        }

        /* Main Layout */
        .products-page-layout {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, var(--brand-yellow-light) 0%, #FFFFFF 100%);
            min-height: 100vh;
            direction: rtl;
            padding: 2rem 0;
        }

        /* Top Bar */
        .products-top-bar {
            background: transparent;
            padding: 1rem 0;
            margin-bottom: 1rem;
        }

        .sort-btn {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            color: var(--brand-brown);
            border: 2px solid var(--brand-yellow-dark);
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-family: 'Cairo', cursive;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
            position: relative;
            z-index: 1;
        }

        .sort-btn:hover {
            background: linear-gradient(135deg, var(--brand-yellow-hover) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 222, 159, 0.4);
        }

        .sort-btn:focus {
            box-shadow: 0 0 0 3px rgba(255, 222, 159, 0.3);
        }

        .dropdown-menu {
            background: white;
            border: 2px solid var(--brand-yellow-light);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.15);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 10px;
            margin: 0.25rem 0;
            padding: 0.75rem 1rem;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            color: var(--brand-brown);
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: var(--brand-yellow-light);
            color: var(--brand-brown);
        }

        .results-title {
            font-family: 'Cairo', cursive;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--brand-brown);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .results-count {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            color: var(--brand-brown);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 700;
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.4);
        }

        .filter-toggle-btn {
            background: transparent;
            border: 2px solid var(--brand-yellow);
            color: var(--brand-yellow);
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .filter-toggle-btn:hover {
            background: var(--brand-yellow);
            color: var(--brand-brown);
        }

        /* Products Grid */
        .products-grid {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            margin: 1rem 0;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid var(--brand-yellow-light);
        }

        .product-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }

        .product-link:hover {
            text-decoration: none;
            color: inherit;
        }

        .product-card {
            background: white;
            border: 2px solid var(--brand-yellow-light);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.08);
            position: relative;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255, 222, 159, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .product-card:hover {
            border-color: var(--brand-yellow);
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(42, 30, 30, 0.15);
        }

        .product-card:hover::before {
            opacity: 1;
        }

        .product-image {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, #FFFFFF 100%);
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
            margin: 0;
            padding: 0;
            display: block;
        }

        .product-card:hover .product-image img {
            transform: scale(1.08);
        }

        .product-info {
            padding: 1.5rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--brand-brown);
            margin: 0;
            font-family: 'Cairo', cursive;
            line-height: 1.4;
            transition: color 0.3s ease;
        }

        .product-card:hover .product-name {
            color: var(--brand-brown-light);
        }

        /* Sidebar Filters */
        .filters-sidebar {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin: 1rem 0;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid var(--brand-yellow-light);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid var(--brand-yellow-light);
        }

        .filters-header h4 {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: var(--brand-brown);
            margin: 0;
            font-size: 1.5rem;
            position: relative;
        }

        .filters-header h4::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            border-radius: 2px;
        }

        .clear-filters-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
            font-family: 'Cairo', sans-serif;
        }

        .clear-filters-btn:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        .clear-filters-btn i {
            font-size: 0.8rem;
        }

        /* Filter Sections */
        .filter-section {
            margin-bottom: 2rem;
            background: var(--brand-yellow-light);
            border-radius: 15px;
            padding: 1.5rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .filter-section:hover {
            border-color: var(--brand-yellow);
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.2);
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            padding: 0.5rem 0;
            margin-bottom: 1rem;
        }

        .filter-header h5 {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: var(--brand-brown);
            margin: 0;
            font-size: 1.1rem;
        }

        .filter-header i {
            color: var(--brand-brown-light);
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .filter-header[aria-expanded="false"] i {
            transform: rotate(180deg);
        }

        .filter-content {
            max-height: 300px;
            overflow-y: auto;
        }

        .filter-item {
            margin-bottom: 1rem;
            background: white;
            border-radius: 10px;
            padding: 0.75rem;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .filter-item:hover {
            border-color: var(--brand-yellow);
            box-shadow: 0 2px 8px rgba(255, 222, 159, 0.2);
        }

        .form-check-input {
            margin-top: 0.1rem;
            width: 1.2rem;
            height: 1.2rem;
            border: 2px solid var(--brand-yellow-dark);
            border-radius: 4px;
        }

        .form-check-input:checked {
            background-color: var(--brand-yellow);
            border-color: var(--brand-yellow-dark);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(255, 222, 159, 0.3);
        }

        .form-check-label {
            font-family: 'Cairo', sans-serif;
            font-size: 0.95rem;
            color: var(--brand-brown);
            cursor: pointer;
            font-weight: 600;
            margin-right: 0.5rem;
        }

        .search-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--brand-yellow-light);
            border-radius: 15px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.95rem;
            background: white;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--brand-yellow);
            box-shadow: 0 0 0 3px rgba(255, 222, 159, 0.2);
            outline: none;
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: var(--brand-brown);
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .filter-label i {
            color: var(--brand-brown-light);
            font-size: 1.1rem;
        }

        .show-more-btn {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            border: none;
            color: var(--brand-brown);
            font-size: 0.9rem;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border-radius: 20px;
            margin-top: 1rem;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
        }

        .show-more-btn:hover {
            background: linear-gradient(135deg, var(--brand-yellow-hover) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 222, 159, 0.4);
        }

        /* Pagination */
        .pagination-top,
        .pagination-bottom {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin: 2rem 0;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid var(--brand-yellow-light);
        }

        .pagination {
            margin: 0;
            font-family: 'Cairo', sans-serif;
        }

        .pagination .page-item .page-link {
            background: white;
            border: 2px solid var(--brand-yellow-light);
            color: var(--brand-brown);
            padding: 0.75rem 1.25rem;
            margin: 0 0.25rem;
            border-radius: 15px;
            font-weight: 700;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
        }

        .pagination .page-item .page-link:hover {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            border-color: var(--brand-yellow-dark);
            color: var(--brand-brown);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            border-color: var(--brand-yellow-dark);
            color: var(--brand-brown);
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.4);
        }

        .pagination .page-item.disabled .page-link {
            background: var(--brand-yellow-light);
            border-color: var(--brand-yellow-light);
            color: var(--brand-brown-light);
        }

        /* No Products */
        .no-products-section {
            background: white;
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid var(--brand-yellow-light);
        }

        .no-products-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: var(--brand-brown);
            font-size: 2.5rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(255, 222, 159, 0.3);
        }

        .no-products-title {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: var(--brand-brown);
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .no-products-description {
            color: var(--brand-brown-light);
            font-size: 1.1rem;
            line-height: 1.6;
            font-family: 'Cairo', sans-serif;
        }

        /* Mobile Offcanvas */
        .offcanvas {
            background: linear-gradient(180deg, var(--brand-yellow-light) 0%, #FFFFFF 100%);
        }

        .offcanvas-header {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: var(--brand-yellow);
            border-bottom: 2px solid var(--brand-yellow);
        }

        .offcanvas-title {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: var(--brand-yellow);
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .offcanvas-body {
            padding: 2rem;
        }

        .filter-actions-mobile {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 3px solid var(--brand-yellow-light);
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .results-title {
                font-size: 2rem;
            }

            .results-count {
                width: 45px;
                height: 45px;
                font-size: 1.2rem;
            }

            .products-grid {
                padding: 2rem;
            }

            .filters-sidebar {
                padding: 2rem;
            }
        }

        @media (max-width: 767.98px) {
            .products-page-layout {
                padding: 1rem 0;
            }

            .products-top-bar {
                padding: 1.5rem 0;
                margin-bottom: 1.5rem;
            }

            .products-grid {
                padding: 1.5rem;
            }

            .product-image {
                height: 180px;
            }

            .product-name {
                font-size: 1rem;
            }

            .results-title {
                font-size: 1.8rem;
            }

            .filters-sidebar {
                padding: 1.5rem;
            }

            .sort-btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 575.98px) {
            .products-top-bar {
                padding: 1rem 0;
            }

            .results-title {
                font-size: 1.5rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .results-count {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .product-image {
                height: 150px;
            }

            .product-name {
                font-size: 0.95rem;
            }

            .products-grid {
                padding: 1rem;
            }

            .filters-sidebar {
                padding: 1rem;
            }
        }

        /* Custom scrollbar for filter content */
        .filter-content::-webkit-scrollbar {
            width: 6px;
        }

        .filter-content::-webkit-scrollbar-track {
            background: var(--brand-yellow-light);
            border-radius: 3px;
        }

        .filter-content::-webkit-scrollbar-thumb {
            background: var(--brand-yellow-dark);
            border-radius: 3px;
        }

        .filter-content::-webkit-scrollbar-thumb:hover {
            background: var(--brand-yellow);
        }

        /* Loading animation for images */
        .product-image img {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-image img.loaded {
            opacity: 1;
        }

        /* Subtle animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .product-card:nth-child(1) { animation-delay: 0.1s; }
        .product-card:nth-child(2) { animation-delay: 0.2s; }
        .product-card:nth-child(3) { animation-delay: 0.3s; }
        .product-card:nth-child(4) { animation-delay: 0.4s; }
        .product-card:nth-child(5) { animation-delay: 0.5s; }
        .product-card:nth-child(6) { animation-delay: 0.6s; }
    </style>
</div>
