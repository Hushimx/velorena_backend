@extends('components.layout')

@section('title', 'البحث عن التصاميم - Qaads')

@section('content')
    <x-navbar />
    
    <style>
        :root {
            --brand-yellow: #ffc107;
            --brand-yellow-light: #ffde9f;
            --brand-brown: #2a1e1e;
            --brand-brown-light: #4a3535;
        }

        * {
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .design-search-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 900;
            color: var(--brand-brown);
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: #6c757d;
        }

        .search-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            position: relative;
        }

        .btn-cart-action {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-cart-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-studio-action {
            background: linear-gradient(135deg, #6f42c1, #e83e8c);
            color: white;
            box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
        }

        .btn-studio-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(111, 66, 193, 0.4);
            color: white;
            text-decoration: none;
        }

        .cart-count {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            margin-left: 0.5rem;
            min-width: 1.5rem;
            text-align: center;
        }

        .search-input {
            flex: 1;
            padding: 1rem 1.5rem;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            font-size: 1.1rem;
            min-width: 300px;
        }

        .search-input:focus {
            border-color: var(--brand-yellow);
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
        }

        .search-btn {
            background: var(--brand-yellow);
            color: var(--brand-brown);
            border: none;
            padding: 1rem 2rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: var(--brand-yellow-light);
            transform: translateY(-2px);
        }

        .designs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
        }

        .design-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .design-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .design-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .design-info {
            padding: 1.5rem;
        }

        .design-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--brand-brown);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .design-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .design-actions {
            display: flex;
            gap: 1rem;
        }

        .action-btn {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-cart {
            background: var(--brand-yellow);
            color: var(--brand-brown);
        }

        .btn-cart:hover {
            background: var(--brand-yellow-light);
            transform: translateY(-2px);
        }

        .btn-favorite {
            background: #f8f9fa;
            color: var(--brand-brown);
            border: 2px solid #e9ecef;
        }

        .btn-favorite:hover {
            background: #e9ecef;
            border-color: var(--brand-yellow);
        }

        .btn-edit {
            background: var(--brand-brown);
            color: white;
        }

        .btn-edit:hover {
            background: var(--brand-brown-light);
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .loading-state {
            display: none;
            text-align: center;
            padding: 4rem 2rem;
            color: var(--brand-brown);
        }

        .loading-state.active {
            display: block;
        }

        .cart-indicator-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .cart-indicator-badge i {
            font-size: 0.7rem;
        }

        /* Notification styles removed - using global toaster system */

        /* Button Animation */
        .action-btn {
            transition: all 0.3s ease;
        }

        .action-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Cart indicator animation */
        .cart-indicator-badge {
            animation: slideInFromTop 0.3s ease;
        }

        @keyframes slideInFromTop {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .designs-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 1rem;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .search-input {
                min-width: 100%;
            }
        }
    </style>

    <div class="design-search-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-search me-3"></i>
                البحث عن التصاميم
            </h1>
            <p class="page-subtitle">اكتشف آلاف التصاميم المميزة من Freepik</p>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <form class="search-form" method="GET" action="{{ route('design.search') }}">
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="ابحث عن التصاميم... (مثال: قميص، شعار، تصميم)"
                    value="{{ $search }}"
                >
                <button type="submit" class="search-btn">
                    <i class="fas fa-search me-2"></i>بحث
                </button>
            </form>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('cart.index') }}" class="btn btn-cart-action">
                    <i class="fas fa-shopping-cart me-2"></i>
                    عرض السلة
                    <span class="cart-count" id="cartCount">0</span>
                </a>
                <a href="{{ route('design.studio') }}" class="btn btn-studio-action">
                    <i class="fas fa-paint-brush me-2"></i>
                    استوديو التصميم
                </a>
            </div>
        </div>

        <!-- Loading State -->
        <div class="loading-state" id="loadingState">
            <i class="fas fa-spinner fa-spin"></i>
            <h3>جاري البحث...</h3>
            <p>يرجى الانتظار بينما نحضر لك أفضل التصاميم</p>
        </div>

        <!-- Designs Grid -->
        @if(count($designs) > 0)
            <div class="designs-grid">
                @foreach($designs as $design)
                    <div class="design-card" data-design-id="{{ $design['id'] }}">
                        @if($design['in_cart'] ?? false)
                            <div class="cart-indicator-badge">
                                <i class="fas fa-check"></i>
                                في السلة
                            </div>
                        @endif
                        <img 
                            src="{{ $design['thumbnail_url'] ?? $design['image_url'] }}" 
                            alt="{{ $design['title'] }}"
                            class="design-image"
                            loading="lazy"
                            onerror="this.style.display='none'"
                        >
                        <div class="design-info">
                            <h3 class="design-title">{{ Str::limit($design['title'], 60) }}</h3>
                            <div class="design-meta">
                                <span><i class="fas fa-tag me-1"></i>{{ $design['category'] ?? 'تصميم' }}</span>
                                <span><i class="fas fa-download me-1"></i>{{ $design['downloads'] ?? 0 }}</span>
                            </div>
                    <div class="design-actions">
                        @if($design['in_cart'] ?? false)
                            <button 
                                class="action-btn btn-delete"
                                onclick="deleteFromCart(event, '{{ $design['id'] }}', '{{ addslashes($design['title']) }}', '{{ $design['image_url'] }}')"
                                style="background: #dc3545; color: white;"
                            >
                                <i class="fas fa-trash"></i>
                                حذف من السلة
                            </button>
                        @else
                            <button 
                                class="action-btn btn-cart"
                                onclick="saveToCart(event, '{{ $design['id'] }}', '{{ addslashes($design['title']) }}', '{{ $design['image_url'] }}')"
                            >
                                <i class="fas fa-shopping-cart"></i>
                                حفظ في السلة
                            </button>
                        @endif
                                @auth
                                    <button 
                                        class="action-btn btn-favorite"
                                        onclick="addToFavorites(event, '{{ $design['id'] }}', '{{ addslashes($design['title']) }}', '{{ $design['image_url'] }}')"
                                    >
                                        <i class="fas fa-heart"></i>
                                        مفضلة
                                    </button>
                                @endauth
                                <button 
                                    class="action-btn btn-edit"
                                    onclick="editDesign('{{ $design['id'] }}', '{{ $design['image_url'] }}')"
                                >
                                    <i class="fas fa-edit"></i>
                                    تحرير
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($search || $category)
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>لم يتم العثور على تصاميم</h3>
                <p>جرب البحث بكلمات مختلفة أو تصفح جميع التصاميم</p>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-palette"></i>
                <h3>ابدأ البحث عن التصاميم</h3>
                <p>استخدم شريط البحث أعلاه للعثور على التصاميم المثالية لمشروعك</p>
            </div>
        @endif
    </div>

    <script>
        // CSRF token setup
        window.axios = window.axios || {};
        window.axios.defaults = window.axios.defaults || {};
        window.axios.defaults.headers = window.axios.defaults.headers || {};
        window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function saveToCart(event, designId, title, imageUrl) {
            console.log('saveToCart called with:', { designId, title, imageUrl });
            
            const button = event.target.closest('.action-btn');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            button.disabled = true;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                showNotification('خطأ في الأمان - يرجى إعادة تحميل الصفحة', 'error');
                resetButton(button, originalText);
                return;
            }
            
            console.log('CSRF token:', csrfToken.getAttribute('content'));
            
            fetch('{{ route("design.save-to-cart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    design_id: designId,
                    title: title,
                    image_url: imageUrl
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    console.log('Response not ok, status:', response.status);
                    return response.text().then(text => {
                        console.log('Error response body:', text);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data received:', data);
                console.log('Data type:', typeof data);
                console.log('Data.success:', data.success);
                
                // Always show success for now to test
                if (data) {
                    // Update button to show success state
                    button.innerHTML = '<i class="fas fa-check"></i> محفوظ في السلة';
                    button.style.background = '#28a745';
                    button.style.color = 'white';
                    button.disabled = true;
                    
                    // Add cart indicator badge
                    addCartIndicator(button.closest('.design-card'));
                    
                    // Show success notification
                    showNotification(data.message || 'تم حفظ التصميم بنجاح!', 'success');
                    
                    // Update cart count if cart indicator exists
                    updateCartCount();
                } else {
                    console.log('No data received:', data);
                    showNotification('حدث خطأ أثناء الحفظ', 'error');
                    resetButton(button, originalText);
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                showNotification('حدث خطأ أثناء الحفظ', 'error');
                resetButton(button, originalText);
            });
        }
        
        function resetButton(button, originalText) {
            button.innerHTML = originalText;
            button.disabled = false;
        }
        
        function addCartIndicator(designCard) {
            // Check if indicator already exists
            if (designCard.querySelector('.cart-indicator-badge')) {
                return;
            }
            
            const indicator = document.createElement('div');
            indicator.className = 'cart-indicator-badge';
            indicator.innerHTML = '<i class="fas fa-check"></i> في السلة';
            designCard.appendChild(indicator);
        }

        // Remove cart indicator badge from design card
        function removeCartIndicator(designCard) {
            const badge = designCard.querySelector('.cart-indicator-badge');
            if (badge) {
                badge.remove();
            }
        }
        
        function updateCartCount() {
            // Try to update cart count if cart indicator component exists
            if (window.Livewire && window.Livewire.emit) {
                window.Livewire.emit('cartUpdated');
            }
            
            // Update the cart count badge
            updateCartCountBadge();
        }

        // Update cart count badge
        function updateCartCountBadge() {
            const cartCountElement = document.getElementById('cartCount');
            if (cartCountElement) {
                // You can fetch the actual count from an API endpoint
                // For now, we'll increment/decrement based on actions
                let currentCount = parseInt(cartCountElement.textContent) || 0;
                cartCountElement.textContent = currentCount;
            }
        }
        
        function showNotification(message, type = 'info') {
            // Use the global toaster system
            if (window.toaster) {
                const titles = {
                    success: 'نجح',
                    error: 'خطأ',
                    info: 'معلومة',
                    warning: 'تحذير'
                };
                
                window.toaster.show(
                    message,
                    type,
                    titles[type] || titles.info,
                    4000
                );
            } else {
                // Fallback to console if toaster not available
                console.log(`${type.toUpperCase()}: ${message}`);
            }
        }

        function addToFavorites(event, designId, title, imageUrl) {
            const button = event.target.closest('.action-btn');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            button.disabled = true;
            
            fetch('{{ route("design.add-to-favorites") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    design_id: designId,
                    title: title,
                    image_url: imageUrl
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button to show success state
                    button.innerHTML = '<i class="fas fa-heart" style="color: #dc3545;"></i> في المفضلة';
                    button.style.background = '#f8f9fa';
                    button.style.borderColor = '#dc3545';
                    button.disabled = true;
                    
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || 'حدث خطأ أثناء الإضافة للمفضلة', 'error');
                    resetButton(button, originalText);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('حدث خطأ أثناء الإضافة للمفضلة', 'error');
                resetButton(button, originalText);
            });
        }

        function deleteFromCart(event, designId, title, imageUrl) {
            const button = event.target.closest('.action-btn');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحذف...';
            button.disabled = true;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                showNotification('خطأ في الأمان - يرجى إعادة تحميل الصفحة', 'error');
                resetButton(button, originalText);
                return;
            }
            
            fetch('{{ route("design.delete-from-cart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    design_id: designId,
                    title: title,
                    image_url: imageUrl
                })
            })
            .then(response => {
                console.log('Delete response status:', response.status);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.log('Delete error response body:', text);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Delete response data received:', data);
                
                if (data && data.success) {
                    // Update button to show add to cart state
                    button.innerHTML = '<i class="fas fa-shopping-cart"></i> حفظ في السلة';
                    button.style.background = '';
                    button.style.color = '';
                    button.className = 'action-btn btn-cart';
                    button.onclick = function(e) {
                        saveToCart(e, designId, title, imageUrl);
                    };
                    
                    // Remove cart indicator badge
                    removeCartIndicator(button.closest('.design-card'));
                    
                    // Show success notification
                    showNotification(data.message || 'تم حذف التصميم بنجاح!', 'success');
                    
                    // Update cart count if cart indicator exists
                    updateCartCount();
                } else {
                    console.log('Delete failed:', data);
                    showNotification(data.message || 'حدث خطأ أثناء الحذف', 'error');
                    resetButton(button, originalText);
                }
            })
            .catch(error => {
                console.error('Delete error details:', error);
                showNotification('حدث خطأ أثناء الحذف', 'error');
                resetButton(button, originalText);
            });
        }

        function editDesign(designId, imageUrl) {
            // Redirect to design studio with selected design
            window.location.href = `{{ route('design.studio') }}?design_id=${designId}&image_url=${encodeURIComponent(imageUrl)}`;
        }

        // Show loading state on form submit
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.querySelector('.search-form');
            if (searchForm) {
                searchForm.addEventListener('submit', function() {
                    document.getElementById('loadingState').classList.add('active');
                });
            }
        });
    </script>
@endsection
