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
                            <div class="product-card">
                                <!-- Product Image -->
                                <div class="product-image-container">
                                    <a href="{{ route('user.products.show', $product) }}" wire:ignore>
                                        @if ($product->image && file_exists(public_path($product->image)))
                                            <img class="product-image" src="{{ asset($product->image) }}"
                                                alt="{{ $product->name }}">
                                        @else
                                            <div class="product-placeholder">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        @endif
                                    </a>
                                    <div class="product-overlay">
                                        <a href="{{ route('user.products.show', $product) }}" class="view-btn" wire:ignore>
                                            <i class="fas fa-eye"></i>
                                            {{ trans('products.view_details') }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="product-info">
                                    <div class="product-category">
                                        {{ $product->category->name ?? trans('products.no_category') }}
                                    </div>

                                    <h3 class="product-title">
                                        <a href="{{ route('user.products.show', $product) }}" wire:ignore>
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <p class="product-description">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>

                                    <div class="product-footer">
                                        <span class="product-price">
                                            {{ number_format($product->base_price, 2) }}
                                            {{ trans('products.currency') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
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
            border-color: #c4a700;
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
            color: #2C2C2C;
            margin-bottom: 0.5rem;
        }

        .search-label i,
        .filter-label i {
            color: #c4a700;
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
            border-color: #c4a700;
            box-shadow: 0 0 0 3px rgba(196, 167, 0, 0.1);
            outline: none;
        }

        /* Products Grid */
        .products-grid-section {
            background: transparent;
        }

        .products-container {
            padding: 1rem 0;
        }

        /* Product Cards */
        .product-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #c4a700;
        }

        /* Product Image */
        .product-image-container {
            position: relative;
            overflow: hidden;
            height: 200px;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #FFEBC6 0%, #FFD700 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2C2C2C;
            font-size: 3rem;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(44, 44, 44, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .view-btn {
            background: #FFEBC6;
            color: #2C2C2C;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Cairo', cursive;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(255, 235, 198, 0.3);
        }

        .view-btn:hover {
            background: #FFD700;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 235, 198, 0.4);
            color: #2C2C2C;
        }

        /* Product Info */
        .product-info {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            background: linear-gradient(135deg, #FFEBC6 0%, #FFD700 100%);
            color: #2C2C2C;
            border: 1px solid #c4a700;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 1rem;
            width: fit-content;
        }

        .product-title {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: #2C2C2C;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .product-title a {
            color: #2C2C2C;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .product-title a:hover {
            color: #c4a700;
        }

        .product-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 1rem;
            flex: 1;
        }

        .product-footer {
            margin-top: auto;
        }

        .product-price {
            color: #c4a700;
            font-family: 'Cairo', cursive;
            font-size: 1.3rem;
            font-weight: 700;
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
            border-color: #c4a700;
        }

        /* Bootstrap Pagination Customization */
        .pagination {
            margin: 0;
            font-family: 'Cairo', sans-serif;
        }

        .pagination .page-item .page-link {
            background: #fff;
            border: 2px solid #e5e7eb;
            color: #2C2C2C;
            padding: 0.75rem 1rem;
            margin: 0 0.25rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .pagination .page-item .page-link:hover {
            background: #FFEBC6;
            border-color: #c4a700;
            color: #2C2C2C;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(196, 167, 0, 0.2);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #FFEBC6 0%, #FFD700 100%);
            border-color: #c4a700;
            color: #2C2C2C;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(196, 167, 0, 0.3);
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
            background: linear-gradient(135deg, #FFEBC6 0%, #FFD700 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #2C2C2C;
            font-size: 2rem;
        }

        .no-products-title {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: #2C2C2C;
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

            .product-image-container {
                height: 180px;
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

            .product-title {
                font-size: 1.1rem;
            }

            .product-price {
                font-size: 1.1rem;
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
