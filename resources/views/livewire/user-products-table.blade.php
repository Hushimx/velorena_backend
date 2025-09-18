<div>
    <!-- Search and Filters -->
    <div class="search-filters-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="search-group">
                        <label for="search" class="search-label">
                            <i class="fas fa-search"></i>
                            {{ trans('products.search') }}
                        </label>
                        <input type="text" wire:model.live.debounce.500ms="search" wire:key="search-input"
                            placeholder="{{ trans('products.search_products_placeholder') }}" class="search-input">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="filter-group">
                        <label for="categoryFilter" class="filter-label">
                            <i class="fas fa-filter"></i>
                            {{ trans('products.category') }}
                        </label>
                        <select wire:model.live="categoryFilter" class="filter-select">
                            <option value="">{{ trans('products.all_categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="products-grid-section">
        @if ($products->count() > 0)
            <div class="products-container">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" wire:key="product-card-{{ $product->id }}">
                            <a href="{{ route('user.products.show', $product) }}" class="product-link" wire:ignore>
                                <div class="product-card">
                                    <div class="product-image">
                                        @php
                                            $productImage = null;
                                            // Try to get primary image first
                                            $primaryImage = $product->images()->where('is_primary', true)->first();
                                            if ($primaryImage && file_exists(public_path($primaryImage->image_path))) {
                                                $productImage = asset($primaryImage->image_path);
                                            } else {
                                                // Fallback to first image
                                                $firstImage = $product->images()->first();
                                                if ($firstImage && file_exists(public_path($firstImage->image_path))) {
                                                    $productImage = asset($firstImage->image_path);
                                                } elseif ($product->image && file_exists(public_path($product->image))) {
                                                    $productImage = asset($product->image);
                                                }
                                            }
                                        @endphp
                                        @if ($productImage)
                                            <img src="{{ $productImage }}" alt="{{ $product->name }}" class="img-fluid" 
                                                 onload="this.classList.add('loaded')"
                                                 onerror="this.src='https://placehold.co/300x200/f8f9fa/6c757d?text=No+Image'; this.classList.add('loaded')"
                                                 loading="lazy">
                                        @else
                                            <img src="https://placehold.co/300x200/f8f9fa/6c757d?text=No+Image" alt="{{ $product->name }}" class="img-fluid" 
                                                 onload="this.classList.add('loaded')"
                                                 loading="lazy">
                                        @endif
                                    </div>
                                    <div class="product-info p-3">
                                        <h5 class="product-name mb-2">{{ $product->name }}</h5>
                                        <p class="product-price fw-bold mb-3">{{ number_format($product->base_price, 2) }} ر.س</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-section">
                <div class="d-flex justify-content-center">
                    <nav aria-label="Products pagination">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        @else
            <div class="no-products-section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center">
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
            </div>
        @endif
    </div>

    <style>
        /* CSS Variables for Brand Colors */
        :root {
            --brand-yellow: #ffde9f;
            --brand-yellow-light: #fff4e6;
            --brand-yellow-dark: #f5d182;
            --brand-brown: #2a1e1e;
        }

        /* Livewire Component Styles - Welcome Page Design */
        .search-filters-section {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            padding: 2rem;
        }

        .search-filters-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #f5d182;
        }

        .search-group,
        .filter-group {
            margin-bottom: 1rem;
        }

        .search-label,
        .filter-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Cairo', cursive;
            font-weight: 600;
            font-size: 1.1rem;
            color: #2a1e1e;
            margin-bottom: 0.5rem;
        }

        .search-label i,
        .filter-label i {
            color: #f5d182;
        }

        .search-input,
        .filter-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
            background: #fff;
        }

        .search-input:focus,
        .filter-select:focus {
            border-color: #f5d182;
            box-shadow: 0 0 0 3px rgba(245, 209, 130, 0.1);
            outline: none;
        }

        /* Products Grid */
        .products-grid-section {
            background: transparent;
        }

        .products-container {
            padding: 1rem 0;
        }

        /* Product Cards - Using Slider Design */
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
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border: 2px solid transparent;
            will-change: transform;
            backface-visibility: hidden;
            transform: translateZ(0);
            width: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: var(--brand-yellow-dark, #f5d182);
        }

        .product-image {
            position: relative;
            height: 200px;
            overflow: hidden;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .product-image img {
            width: 100% !important;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease, opacity 0.3s ease;
            display: block;
            border-radius: 0;
            background-color: #f8f9fa;
            opacity: 0;
            animation: fadeInImage 0.5s ease forwards;
            max-width: none;
            min-width: 100%;
        }

        .product-image img.loaded {
            opacity: 1;
        }

        @keyframes fadeInImage {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            text-align: center;
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--brand-brown, #2a1e1e);
            min-height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cairo', sans-serif;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--brand-brown, #2a1e1e);
            margin: 1rem 0;
        }

        /* Pagination */
        .pagination-section {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-top: 2rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .pagination-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #f5d182;
        }

        /* Bootstrap Pagination Customization */
        .pagination {
            margin: 0;
            font-family: 'Cairo', sans-serif;
        }

        .pagination .page-item .page-link {
            background: #fff;
            border: 2px solid #e5e7eb;
            color: #2a1e1e;
            padding: 0.75rem 1rem;
            margin: 0 0.25rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .pagination .page-item .page-link:hover {
            background: #ffde9f;
            border-color: #f5d182;
            color: #2a1e1e;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(245, 209, 130, 0.2);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #ffde9f 0%, #f5d182 100%);
            border-color: #f5d182;
            color: #2a1e1e;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(245, 209, 130, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            background: #f8f9fa;
            border-color: #e5e7eb;
            color: #6c757d;
            cursor: not-allowed;
        }

        .pagination .page-item.disabled .page-link:hover {
            background: #f8f9fa;
            border-color: #e5e7eb;
            color: #6c757d;
            transform: none;
            box-shadow: none;
        }

        /* Pagination icons */
        .pagination .page-link i {
            font-size: 0.9rem;
        }

        /* No Products */
        .no-products-section {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
        }

        .no-products-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ffde9f 0%, #f5d182 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #2a1e1e;
            font-size: 2rem;
        }

        .no-products-title {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: #2a1e1e;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .no-products-description {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-filters-section {
                padding: 1.5rem;
            }

            .product-card {
                margin-bottom: 1.5rem;
            }

            .product-image {
                height: 150px;
            }
            
            .product-name {
                font-size: 1rem;
            }
            
            .product-price {
                font-size: 1.1rem;
            }

            .product-info {
                padding: 1rem;
            }

            .pagination .page-item .page-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .search-filters-section {
                padding: 1rem;
            }

            .product-name {
                font-size: 0.9rem;
            }

            .product-price {
                font-size: 1rem;
            }

            .pagination .page-item .page-link {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
                margin: 0 0.1rem;
            }

            .pagination-section {
                padding: 1rem;
            }
        }
    </style>
</div>
