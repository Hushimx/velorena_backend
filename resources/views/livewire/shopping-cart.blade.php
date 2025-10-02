<div class="shopping-cart-wrapper">
    <div class="shopping-cart-container">
        <!-- Cart Header -->
        <div class="cart-header-card">
            <div class="cart-header-content">
                <div class="cart-header-info">
                    <div class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="cart-header-text">
                        <h2 class="cart-header-title">{{ trans('cart.shopping_cart') }}</h2>
                        <p class="cart-header-subtitle">
                            <span class="item-count">{{ $itemCount }}</span>
                            {{ trans('cart.items_in_cart') }}
                        </p>
                    </div>
                </div>
                @if ($itemCount > 0)
                    <button wire:click="clearCart"
                        wire:confirm="{{ trans('cart.confirm_clear_cart', ['default' => 'Are you sure you want to clear all items from your cart? This action cannot be undone.']) }}"
                        class="clear-cart-btn">
                        <i class="fas fa-trash"></i>
                        <span>{{ trans('cart.clear_cart') }}</span>
                    </button>
                @endif
            </div>
        </div>

        @if ($itemCount == 0)
            <!-- Empty Cart -->
            <div class="empty-cart-card">
                <div class="empty-cart-content">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="empty-cart-title">{{ trans('cart.empty_cart') }}</h3>
                    <p class="empty-cart-message">{{ trans('cart.empty_cart_message') }}</p>
                    <a href="{{ route('user.products.index') }}" class="continue-shopping-btn">
                        <i class="fas fa-shopping-bag"></i>
                        <span>{{ trans('cart.continue_shopping') }}</span>
                    </a>
                </div>
            </div>
        @else
            <!-- Cart Items -->
            <div class="cart-items-container">
                @foreach ($cartItems as $index => $rawItem)
                    @php
                        // Handle mixed structure - some items might be wrapped in arrays
                        $item = is_array($rawItem) && isset($rawItem[0]) && is_array($rawItem[0]) ? $rawItem[0] : $rawItem;
                    @endphp
                    <div class="cart-item-card" wire:key="cart-item-{{ $item['id'] ?? $item['cart_key'] ?? $index }}">
                        <div class="cart-item-content">
                            <!-- Product Image -->
                            <div class="cart-item-image">
                                @php
                                    $productImage = null;
                                    if (isset($item['product_id'])) {
                                        $product = \App\Models\Product::find($item['product_id']);
                                        if ($product) {
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
                                                    } elseif ($product->image && file_exists(public_path($product->image))) {
                                                        $productImage = asset($product->image);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                @if ($productImage)
                                    <img src="{{ $productImage }}" alt="{{ $item['product_name'] ?? 'Product' }}">
                                @else
                                    <div class="cart-item-placeholder">
                                        <i class="fas fa-box"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="cart-item-details">
                                <h3 class="cart-item-title">{{ $item['product_name'] ?? 'Unknown Product' }}</h3>

                                <!-- Selected Options -->
                                @if (!empty($item['selected_options']))
                                    <div class="cart-item-options">
                                        <h4 class="options-title">{{ trans('cart.selected_options') }}:</h4>
                                        <div class="options-list">
                                            @foreach ($item['selected_options'] as $optionName => $optionValue)
                                                <div class="option-item">
                                                    <span class="option-name">{{ $optionName }}:</span>
                                                    <span class="option-value">{{ $optionValue['value'] }}</span>
                                                    @if ($optionValue['price_adjustment'] != 0)
                                                        <span
                                                            class="price-adjustment {{ $optionValue['price_adjustment'] > 0 ? 'positive' : 'negative' }}">
                                                            ({{ $optionValue['price_adjustment'] > 0 ? '+' : '' }}{{ number_format($optionValue['price_adjustment'], 2) }} {{ trans('products.currency') }})
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Selected Designs -->
                                @if (!empty($item['designs']) && count($item['designs']) > 0)
                                    <div class="cart-item-designs">
                                        <h4 class="designs-title">{{ trans('cart.selected_designs') }}:</h4>
                                        <div class="designs-list">
                                            @foreach ($item['designs'] as $design)
                                                <div class="design-item">
                                                    <div class="design-thumbnail">
                                                        <img src="{{ $design['thumbnail_url'] ?? $design['image_url'] }}"
                                                            alt="{{ $design['title'] }}" class="design-thumb">
                                                    </div>
                                                    <div class="design-info">
                                                        <span class="design-title">{{ $design['title'] }}</span>
                                                        @if (!empty($design['notes']))
                                                            <span class="design-notes">({{ $design['notes'] }})</span>
                                                        @endif
                                                    </div>
                                                    <button
                                                        wire:click="removeDesignFromProduct({{ $item['product_id'] }}, {{ $design['id'] }})"
                                                        class="remove-design-btn"
                                                        wire:confirm="{{ trans('cart.confirm_remove_design') }}"
                                                        wire:loading.attr="disabled"
                                                        wire:target="removeDesignFromProduct({{ $item['product_id'] }}, {{ $design['id'] }})"
                                                        title="{{ trans('cart.remove_this_design') }}">
                                                        <span wire:loading.remove wire:target="removeDesignFromProduct({{ $item['product_id'] }}, {{ $design['id'] }})">
                                                            <i class="fas fa-trash"></i>
                                                        </span>
                                                        <span wire:loading wire:target="removeDesignFromProduct({{ $item['product_id'] }}, {{ $design['id'] }})">
                                                            <i class="fas fa-spinner fa-spin"></i>
                                                        </span>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Notes -->
                                @if (!empty($item['notes']))
                                    <div class="cart-item-notes">
                                        <h4 class="notes-title">{{ trans('cart.notes') }}:</h4>
                                        <p class="notes-content">{{ $item['notes'] }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Quantity Controls -->
                            <div class="cart-item-quantity">
                                <label class="quantity-label">{{ trans('cart.quantity') }}:</label>
                                <div class="quantity-controls"
                                    wire:key="quantity-controls-{{ $item['product_id'] ?? 0 }}-{{ $item['quantity'] ?? 1 }}">
                                    <button
                                        wire:click="updateQuantity({{ $item['id'] ?? $item['cart_key'] ?? 0 }}, {{ max(1, ($item['quantity'] ?? 1) - 1) }})"
                                        class="quantity-btn minus {{ ($item['quantity'] ?? 1) <= 1 ? 'disabled' : '' }}"
                                        {{ ($item['quantity'] ?? 1) <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="quantity-value">{{ $item['quantity'] ?? 1 }}</span>
                                    <button
                                        wire:click="updateQuantity({{ $item['id'] ?? $item['cart_key'] ?? 0 }}, {{ ($item['quantity'] ?? 1) + 1 }})"
                                        class="quantity-btn plus">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="cart-item-actions">
                                <button wire:click="removeItem({{ $item['id'] ?? $item['cart_key'] ?? 0 }})" class="remove-item-btn">
                                    <i class="fas fa-trash"></i>
                                    <span>{{ trans('cart.remove') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Design Tools Section -->
            <div class="design-tools-section">
                <div class="design-tools-header">
                    <div class="tools-header-info">
                        <div class="tools-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <div class="tools-header-text">
                            <h3 class="tools-title">{{ trans('cart.design_tools') }}</h3>
                            <p class="tools-subtitle">{{ trans('cart.design_tools_subtitle') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="design-tools-grid">
                    <a href="{{ route('design.search') }}" class="design-tool-card">
                        <div class="tool-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="tool-info">
                            <h4>{{ trans('cart.search_designs') }}</h4>
                            <p>{{ trans('cart.search_designs_description') }}</p>
                        </div>
                        <div class="tool-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('design.studio') }}" class="design-tool-card">
                        <div class="tool-icon">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <div class="tool-info">
                            <h4>{{ trans('cart.design_studio') }}</h4>
                            <p>{{ trans('cart.design_studio_description') }}</p>
                        </div>
                        <div class="tool-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>
                    
                    <button wire:click="openUploadDesignModal" class="design-tool-card upload-design-card">
                        <div class="tool-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="tool-info">
                            <h4>{{ trans('cart.upload_ready_designs') }}</h4>
                            <p>{{ trans('cart.upload_ready_designs_description') }}</p>
                        </div>
                        <div class="tool-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </button>
                </div>
                
                <!-- Saved Designs -->
                @if (count($cartDesigns) > 0)
                    <div class="saved-designs-section">
                        <div class="saved-designs-header">
                            <h4 class="saved-designs-title">
                                <i class="fas fa-bookmark me-2"></i>
                                {{ trans('cart.saved_designs') }} ({{ count($cartDesigns) }})
                            </h4>
                            <button wire:click="clearAllCartDesigns" 
                                wire:confirm="{{ trans('cart.confirm_clear_all_designs') }}"
                                wire:loading.attr="disabled"
                                wire:target="clearAllCartDesigns"
                                class="clear-all-designs-btn"
                                title="{{ trans('cart.clear_all_designs') }}">
                                <span wire:loading.remove wire:target="clearAllCartDesigns">
                                    <i class="fas fa-trash-alt"></i>
                                    <span>{{ trans('cart.clear_all_designs') }}</span>
                                </span>
                                <span wire:loading wire:target="clearAllCartDesigns">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <span>{{ trans('cart.deleting') }}</span>
                                </span>
                            </button>
                        </div>
                        <div class="saved-designs-grid">
                            @foreach ($cartDesigns as $design)
                                <div class="saved-design-item">
                                    <div class="design-thumbnail">
                                        @if ($design['thumbnail_url'])
                                            <img src="{{ $design['thumbnail_url'] }}" alt="{{ $design['title'] }}" class="design-thumb-img">
                                        @else
                                            <div class="design-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="design-info">
                                        <p class="design-date">{{ \Carbon\Carbon::parse($design['created_at'])->diffForHumans() }}</p>
                                        <button wire:click="deleteCartDesign({{ $design['id'] }})" class="delete-design-btn" 
                                            wire:confirm="{{ trans('cart.confirm_delete_design') }}" 
                                            wire:loading.attr="disabled"
                                            wire:target="deleteCartDesign({{ $design['id'] }})"
                                            title="{{ trans('cart.delete') }}">
                                            <span wire:loading.remove wire:target="deleteCartDesign({{ $design['id'] }})">
                                                <i class="fas fa-trash"></i>
                                                <span>{{ trans('cart.delete') }}</span>
                                            </span>
                                            <span wire:loading wire:target="deleteCartDesign({{ $design['id'] }})">
                                                <i class="fas fa-spinner fa-spin"></i>
                                                <span>{{ trans('cart.deleting') }}</span>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="design-actions">
                                        <button wire:click="deleteCartDesign({{ $design['id'] }})" class="remove-design-btn" 
                                            wire:confirm="{{ trans('cart.confirm_delete_design') }}" 
                                            wire:loading.attr="disabled"
                                            wire:target="deleteCartDesign({{ $design['id'] }})"
                                            title="{{ trans('cart.delete') }}">
                                            <span wire:loading.remove wire:target="deleteCartDesign({{ $design['id'] }})">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span wire:loading wire:target="deleteCartDesign({{ $design['id'] }})">
                                                <i class="fas fa-spinner fa-spin"></i>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            @if(count($cartItems) > 0)
                <div class="cart-actions">
                    <div class="action-buttons">
                        <a href="{{ route('user.products.index') }}" class="continue-shopping-btn">
                            <i class="fas fa-arrow-left"></i>
                            <span>{{ trans('cart.continue_shopping') }}</span>
                        </a>
                        <button wire:click="showCheckout" class="checkout-btn">
                            <i class="fas fa-credit-card"></i>
                            <span>{{ trans('cart.checkout') }}</span>
                        </button>
                        <button wire:click="bookAppointment" class="appointment-btn">
                            <i class="fas fa-calendar-plus"></i>
                            <span>{{ trans('cart.make_appointment') }}</span>
                        </button>
                    </div>
                </div>
            @endif

        @endif

        <!-- Old design modal removed - now using cart-wide design modal at the bottom -->
        
        <!-- Cart Design Modal -->
        @if ($showCartDesignModal)
            <div class="cart-design-modal-overlay" wire:click="closeCartDesignModal">
                <div class="cart-design-modal-fullscreen" wire:click.stop>
                    <div class="modal-header">
                        <h3 class="modal-title">
                            <i class="fas fa-palette me-2"></i>
                            تصميم إبداعي للسلة
                        </h3>
                        <button wire:click="closeCartDesignModal" class="modal-close-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="modal-body-fullscreen">
                        @if ($showDesignSelector)
                            @livewire('design-selector')
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Upload Design Modal -->
        @if ($showUploadDesignModal)
            <div class="upload-design-modal-overlay" wire:click="closeUploadDesignModal">
                <div class="upload-design-modal" wire:click.stop>
                    <div class="modal-header">
                        <h3 class="modal-title">
                            <i class="fas fa-cloud-upload-alt me-2"></i>
                            رفع تصاميم جاهزة
                        </h3>
                        <button wire:click="closeUploadDesignModal" class="modal-close-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="upload-section">
                            <div class="upload-area" id="upload-area">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h4 class="upload-title">اسحب وأفلت الصور هنا أو انقر للاختيار</h4>
                                <p class="upload-subtitle">يمكنك رفع حتى 10 صور في المرة الواحدة</p>
                                <input type="file" id="design-files" multiple accept="image/*" style="display: none;">
                            </div>
                            
                            <div class="upload-actions">
                                <button type="button" class="upload-btn" onclick="document.getElementById('design-files').click()">
                                    <i class="fas fa-folder-open"></i>
                                    <span>اختيار من المعرض</span>
                                </button>
                                <button type="button" class="camera-btn" onclick="openCamera()">
                                    <i class="fas fa-camera"></i>
                                    <span>التقاط صورة</span>
                                </button>
                            </div>
                            
                            <div class="selected-files" id="selected-files" style="display: none;">
                                <h5>الملفات المختارة:</h5>
                                <div class="files-list" id="files-list"></div>
                                <div class="files-actions">
                                    <button type="button" class="clear-files-btn" onclick="clearSelectedFiles()">
                                        <i class="fas fa-trash"></i>
                                        <span>مسح الكل</span>
                                    </button>
                                    <button type="button" class="upload-files-btn" onclick="uploadDesigns()">
                                        <i class="fas fa-upload"></i>
                                        <span>رفع التصاميم</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Login Modal -->
        <x-login-modal 
            :show="$showLoginModal" 
            title="{{ trans('cart.login_required', ['default' => 'Login Required']) }}"
            message="{{ trans('cart.login_required_message', ['default' => 'Please login to continue with your order.']) }}"
        />
        
        <!-- Scripts -->
        <script>
        document.addEventListener('livewire:init', () => {
            document.addEventListener('cartUpdated', function() {
                console.log('Cart updated event received - Database Cart Mode');
            });
        });

        // Upload Design Modal JavaScript
        let selectedFiles = [];

        // File input change handler
        const fileInput = document.getElementById('design-files');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });
        }

        // Drag and drop handlers
        const uploadArea = document.getElementById('upload-area');
        if (uploadArea) {
            uploadArea.addEventListener('click', () => {
                const fileInput = document.getElementById('design-files');
                if (fileInput) {
                    fileInput.click();
                }
            });

            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                handleFiles(e.dataTransfer.files);
            });
        }

        function handleFiles(files) {
            const maxFiles = 10;
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            
            if (files.length > maxFiles) {
                alert(`يمكنك رفع ${maxFiles} ملفات كحد أقصى`);
                return;
            }

            Array.from(files).forEach(file => {
                if (!allowedTypes.includes(file.type)) {
                    alert(`نوع الملف ${file.name} غير مدعوم. يرجى اختيار صورة.`);
                    return;
                }

                if (file.size > 10 * 1024 * 1024) { // 10MB limit
                    alert(`حجم الملف ${file.name} كبير جداً. الحد الأقصى 10 ميجابايت.`);
                    return;
                }

                selectedFiles.push(file);
            });

            displaySelectedFiles();
        }

        function displaySelectedFiles() {
            const filesList = document.getElementById('files-list');
            const selectedFilesDiv = document.getElementById('selected-files');
            
            if (selectedFiles.length === 0) {
                selectedFilesDiv.style.display = 'none';
                return;
            }

            selectedFilesDiv.style.display = 'block';
            filesList.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    fileItem.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}" class="file-preview">
                        <div class="file-name">${file.name}</div>
                        <button type="button" class="remove-file-btn" onclick="removeFile(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                };
                reader.readAsDataURL(file);
                
                filesList.appendChild(fileItem);
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            displaySelectedFiles();
        }

        function clearSelectedFiles() {
            selectedFiles = [];
            displaySelectedFiles();
        }

        function openCamera() {
            // For web, we'll use the file input with camera capture
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.capture = 'camera';
            input.onchange = function(e) {
                handleFiles(e.target.files);
            };
            input.click();
        }

        function uploadDesigns() {
            if (selectedFiles.length === 0) {
                alert('يرجى اختيار ملفات للرفع');
                return;
            }

            const formData = new FormData();
            selectedFiles.forEach((file, index) => {
                formData.append('design_files[]', file);
            });

            // Show loading state
            const uploadBtn = document.querySelector('.upload-files-btn');
            const originalText = uploadBtn.innerHTML;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>جاري الرفع...</span>';
            uploadBtn.disabled = true;

            // Upload via fetch
            fetch('/api/designs/upload-ready-design', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(`تم رفع ${data.uploaded_count || selectedFiles.length} تصميم بنجاح!`);
                    // Close modal and refresh cart designs
                    @this.call('closeUploadDesignModal');
                    @this.call('loadCartDesigns');
                    clearSelectedFiles();
                } else {
                    alert('فشل في رفع التصاميم: ' + (data.message || 'خطأ غير معروف'));
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                alert('حدث خطأ أثناء رفع التصاميم');
            })
            .finally(() => {
                // Reset button state
                uploadBtn.innerHTML = originalText;
                uploadBtn.disabled = false;
            });
        }
    </script>

    <style>
        /* Shopping Cart Styles - Based on Product Show Page Design */
        .shopping-cart-container {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }

        /* Cart Header */
        .cart-header-card {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid rgba(42, 30, 30, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .cart-header-content {
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .cart-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(42, 30, 30, 0.2);
            box-shadow: 0 8px 24px rgba(42, 30, 30, 0.2);
        }

        .cart-icon i {
            font-size: 2rem;
            color: var(--brand-brown);
        }

        .cart-header-text {
            flex: 1;
        }

        .cart-header-title {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .cart-header-subtitle {
            color: var(--brand-brown);
            font-size: 1.1rem;
            margin: 0;
            opacity: 0.8;
        }

        .item-count {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .clear-cart-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            border: 2px solid rgba(42, 30, 30, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        .clear-cart-btn:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        /* Empty Cart */
        .empty-cart-card {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid rgba(42, 30, 30, 0.1);
            padding: 4rem 2rem;
            text-align: center;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .empty-cart-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .empty-cart-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(42, 30, 30, 0.3);
        }

        .empty-cart-icon i {
            font-size: 3rem;
            color: white;
            opacity: 0.9;
        }

        .empty-cart-title {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1.75rem;
            margin: 0;
        }

        .empty-cart-message {
            color: var(--brand-brown);
            font-size: 1.1rem;
            margin: 0;
            max-width: 400px;
            opacity: 0.8;
        }

        .continue-shopping-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        .continue-shopping-btn:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        /* Cart Items Container */
        .cart-items-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Cart Item Card */
        .cart-item-card {
            background: linear-gradient(135deg, #ffffff 0%, var(--brand-yellow-light) 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid rgba(42, 30, 30, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .cart-item-card:hover {
            box-shadow: 0 12px 40px rgba(42, 30, 30, 0.15);
            border-color: rgba(42, 30, 30, 0.2);
        }

        .cart-item-content {
            padding: 2rem;
            display: grid;
            grid-template-columns: 120px 1fr auto auto;
            gap: 2rem;
            align-items: start;
        }

        /* Better responsive grid for cart items */
        @media (max-width: 1024px) {
            .cart-item-content {
                grid-template-columns: 100px 1fr auto auto;
                gap: 1.5rem;
                padding: 1.5rem;
            }
        }

        /* Tablet layout */
        @media (max-width: 992px) {
            .cart-item-content {
                grid-template-columns: 80px 1fr auto auto;
                gap: 1.25rem;
                padding: 1.25rem;
            }

            .cart-item-image img {
                width: 80px;
                height: 80px;
            }

            .cart-item-title {
                font-size: 1.1rem;
            }

            .quantity-controls {
                padding: 0.5rem;
            }

            .quantity-btn {
                width: 35px;
                height: 35px;
                padding: 0.5rem;
            }

            .quantity-value {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }

            .add-design-btn,
            .remove-item-btn {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .designs-list {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .design-item {
                max-width: 200px;
                flex: 1 1 200px;
            }

            .design-thumb {
                width: 45px;
                height: 45px;
            }
        }

        /* Cart Item Image */
        .cart-item-image {
            flex-shrink: 0;
        }

        .cart-item-image img {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(255, 244, 230, 0.5);
        }

        .cart-item-image img:hover {
            border-color: var(--brand-yellow);
        }

        .cart-item-placeholder {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 244, 230, 0.5);
        }

        .cart-item-placeholder i {
            font-size: 2rem;
            color: var(--brand-brown);
            opacity: 0.6;
        }

        /* Cart Item Details */
        .cart-item-details {
            flex: 1;
        }

        .cart-item-title {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }


        /* Cart Item Options */
        .cart-item-options {
            margin-bottom: 1rem;
        }

        .options-title {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .options-list {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .option-item {
            color: var(--brand-brown);
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .option-name {
            font-weight: 600;
        }

        .option-value {
            font-style: italic;
        }

        .price-adjustment {
            font-size: 0.8rem;
            font-weight: 600;
        }

        .price-adjustment.positive {
            color: var(--brand-brown);
        }

        .price-adjustment.negative {
            color: var(--brand-brown-light);
        }

        /* Cart Item Notes */
        .cart-item-notes {
            margin-bottom: 1rem;
        }

        .notes-title {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .notes-content {
            color: var(--brand-brown);
            font-size: 0.9rem;
            background: rgba(255, 244, 230, 0.5);
            padding: 0.5rem;
            border-radius: 6px;
            font-style: italic;
            margin: 0;
            border: 1px solid rgba(42, 30, 30, 0.2);
        }

        /* Quantity Controls */
        .cart-item-quantity {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .quantity-label {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 12px;
            border: 2px solid rgba(42, 30, 30, 0.2);
            overflow: hidden;
        }

        .quantity-btn {
            background: transparent;
            border: none;
            padding: 0.75rem;
            cursor: pointer;
            color: var(--brand-brown);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .quantity-btn:hover:not(.disabled) {
            background: rgba(42, 30, 30, 0.1);
        }

        .quantity-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-value {
            padding: 0.75rem 1rem;
            font-weight: 700;
            color: var(--brand-brown);
            min-width: 50px;
            text-align: center;
            border-left: 1px solid rgba(42, 30, 30, 0.2);
            border-right: 1px solid rgba(42, 30, 30, 0.2);
        }

        /* Remove Button */
        .cart-item-actions {
            display: flex;
            align-items: center;
        }

        .remove-item-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            border: 2px solid rgba(42, 30, 30, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        .remove-item-btn:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        /* Add Design Button */
        .add-design-btn {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
            border: 2px solid rgba(42, 30, 30, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .add-design-btn:hover {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-light) 100%);
            color: var(--brand-brown);
            box-shadow: 0 4px 12px rgba(42, 30, 30, 0.2);
        }

        /* Cart Item Actions */
        .cart-item-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        /* Selected Designs */
        .cart-item-designs {
            margin-bottom: 1rem;
        }

        .designs-title {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .designs-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .design-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255, 244, 230, 0.3);
            padding: 0.5rem;
            border-radius: 8px;
            border: 1px solid rgba(42, 30, 30, 0.1);
        }

        .design-thumbnail {
            flex-shrink: 0;
        }

        .design-thumb {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid rgba(42, 30, 30, 0.2);
        }

        .design-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .design-title {
            font-weight: 600;
            color: var(--brand-brown);
            font-size: 0.9rem;
        }

        .design-notes {
            font-style: italic;
            color: var(--brand-brown);
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .remove-design-btn {
            background: linear-gradient(135deg, #fca5a5 0%, #f87171 100%);
            color: white;
            border: 1px solid rgba(248, 113, 113, 0.3);
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(252, 165, 165, 0.3);
        }

        .remove-design-btn:hover {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(252, 165, 165, 0.4);
        }

        .remove-design-btn i {
            font-size: 0.8rem;
        }

        .remove-design-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .clear-all-designs-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Old design modal styles removed - using new cart design modal styles below */
        /* Design Tools Styles */
        .design-tools-section {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, #ffffff 100%);
            border-radius: 20px;
            padding: 2rem;
            margin: 2rem 0;
            border: 2px solid var(--brand-yellow);
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
        }

        .design-tools-header {
            margin-bottom: 2rem;
        }

        .tools-header-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .tools-icon {
            width: 60px;
            height: 60px;
            background: var(--brand-yellow);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-brown);
            font-size: 1.5rem;
        }

        .tools-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--brand-brown);
            margin-bottom: 0.5rem;
        }

        .tools-subtitle {
            color: #6c757d;
            margin: 0;
        }

        .design-tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .design-tool-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            border: 2px solid #f8f9fa;
        }

        .design-tool-card:hover {
            border-color: var(--brand-yellow);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
        }

        .design-tool-card .tool-icon {
            width: 50px;
            height: 50px;
            background: var(--brand-yellow-light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-brown);
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .design-tool-card .tool-info {
            flex: 1;
        }

        .design-tool-card .tool-info h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--brand-brown);
            margin-bottom: 0.3rem;
        }

        .design-tool-card .tool-info p {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
        }

        .design-tool-card .tool-arrow {
            color: var(--brand-yellow);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .upload-design-card {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
            border-color: #28a745;
        }

        .upload-design-card:hover {
            border-color: #20c997;
            background: linear-gradient(135deg, #d4edda 0%, #e8f5e8 100%);
        }

        .upload-design-card .tool-icon {
            background: #28a745;
            color: white;
        }

        /* Upload Design Modal Styles */
        .upload-design-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .upload-design-modal {
            width: 90vw;
            max-width: 600px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .upload-section {
            padding: 2rem;
        }

        .upload-area {
            border: 3px dashed #28a745;
            border-radius: 15px;
            padding: 3rem 2rem;
            text-align: center;
            background: linear-gradient(135deg, #f8fff8 0%, #ffffff 100%);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: #20c997;
            background: linear-gradient(135deg, #f0f8f0 0%, #f8fff8 100%);
        }

        .upload-area.dragover {
            border-color: #20c997;
            background: linear-gradient(135deg, #d4edda 0%, #e8f5e8 100%);
            transform: scale(1.02);
        }

        .upload-icon {
            font-size: 3rem;
            color: #28a745;
            margin-bottom: 1rem;
        }

        .upload-title {
            color: #28a745;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .upload-subtitle {
            color: #6c757d;
            margin: 0;
        }

        .upload-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .upload-btn, .camera-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .upload-btn:hover, .camera-btn:hover {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .camera-btn {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }

        .camera-btn:hover {
            background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        }

        .selected-files {
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }

        .selected-files h5 {
            color: #28a745;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .files-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .file-item {
            position: relative;
            background: white;
            border-radius: 8px;
            padding: 0.5rem;
            border: 1px solid #e9ecef;
            text-align: center;
        }

        .file-preview {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }

        .file-name {
            font-size: 0.8rem;
            color: #6c757d;
            word-break: break-all;
        }

        .remove-file-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.7rem;
        }

        .files-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .clear-files-btn, .upload-files-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .clear-files-btn {
            background: #dc3545;
            color: white;
            border: none;
        }

        .clear-files-btn:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .upload-files-btn {
            background: #28a745;
            color: white;
            border: none;
        }

        .upload-files-btn:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .upload-files-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .saved-designs-section {
            border-top: 2px solid rgba(255, 193, 7, 0.2);
            padding-top: 2rem;
        }

        .saved-designs-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .saved-designs-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--brand-brown);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .clear-all-designs-btn {
            background: linear-gradient(135deg, #fca5a5 0%, #f87171 100%);
            color: white;
            border: 2px solid rgba(248, 113, 113, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(252, 165, 165, 0.3);
            font-size: 0.9rem;
        }

        .clear-all-designs-btn:hover {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(252, 165, 165, 0.4);
        }

        .clear-all-designs-btn i {
            font-size: 0.8rem;
        }

        .saved-designs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .saved-design-item {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            border: 2px solid #f8f9fa;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .saved-design-item:hover {
            border-color: var(--brand-yellow);
            transform: translateY(-3px);
        }

        .saved-design-item .design-thumbnail {
            width: 100%;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 0.75rem;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .saved-design-item .design-thumb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .saved-design-item .design-placeholder {
            color: #6c757d;
            font-size: 2rem;
        }

        .saved-design-item .design-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--brand-brown);
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }

        .saved-design-item .design-date {
            font-size: 0.8rem;
            color: #6c757d;
            margin: 0;
        }

        .saved-design-item .remove-design-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: linear-gradient(135deg, #fca5a5 0%, #f87171 100%);
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0.9;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(252, 165, 165, 0.3);
            z-index: 10;
        }

        .saved-design-item:hover .remove-design-btn {
            opacity: 1;
            transform: scale(1.05);
        }

        .saved-design-item .remove-design-btn:hover {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            transform: scale(1.15);
            box-shadow: 0 4px 12px rgba(252, 165, 165, 0.4);
        }

        .saved-design-item .remove-design-btn i {
            font-size: 0.8rem;
        }

        .delete-design-btn {
            background: linear-gradient(135deg, #fca5a5 0%, #f87171 100%);
            color: white;
            border: 2px solid rgba(248, 113, 113, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(252, 165, 165, 0.3);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            width: 100%;
            justify-content: center;
        }

        .delete-design-btn:hover {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(252, 165, 165, 0.4);
        }

        .delete-design-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .delete-design-btn i {
            font-size: 0.7rem;
        }

        .cart-designs-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .designs-header-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .designs-icon {
            width: 60px;
            height: 60px;
            background: var(--brand-brown);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .designs-title {
            color: var(--brand-brown);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .designs-subtitle {
            color: var(--brand-brown-light);
            font-size: 1rem;
            margin: 0;
        }

        .add-design-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .add-design-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .cart-designs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .cart-design-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .cart-design-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .design-preview {
            position: relative;
            aspect-ratio: 1;
            overflow: hidden;
        }

        .design-thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .design-placeholder {
            width: 100%;
            height: 100%;
            background: var(--brand-yellow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-brown-light);
            font-size: 2rem;
        }

        .design-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .cart-design-item:hover .design-overlay {
            opacity: 1;
        }

        .design-action-btn {
            background: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--brand-brown);
        }

        .edit-btn:hover {
            background: var(--brand-yellow);
            transform: scale(1.1);
        }

        .delete-btn:hover {
            background: #ef4444;
            color: white;
            transform: scale(1.1);
        }

        .design-info {
            padding: 1rem;
        }

        .design-name {
            color: var(--brand-brown);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .design-date {
            color: var(--brand-brown-light);
            font-size: 0.85rem;
            margin: 0;
        }

        .empty-designs {
                text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 15px;
            border: 2px dashed var(--brand-yellow);
        }

        .empty-designs-content i {
            font-size: 3rem;
            color: var(--brand-brown-light);
                margin-bottom: 1rem;
            }

        .empty-designs-content h4 {
            color: var(--brand-brown);
            font-size: 1.2rem;
                margin-bottom: 0.5rem;
            }

        .empty-designs-content p {
            color: var(--brand-brown-light);
            margin: 0;
        }

        /* Cart Design Modal */
        .cart-design-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
                justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .cart-design-modal {
            width: 95vw;
            height: 90vh;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
                flex-direction: column;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
                align-items: center;
                justify-content: space-between;
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
        }

        .modal-title {
                font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .modal-close-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .modal-body {
            flex: 1;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .cart-designs-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .cart-designs-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }

            .cart-design-modal {
                width: 100vw;
                height: 100vh;
                border-radius: 0;
            }
        }
    </style>

    <!-- Cart Design Modal -->
    @if ($showCartDesignModal)
        <div class="cart-design-modal-overlay" wire:click="closeCartDesignModal">
            <div class="cart-design-modal-fullscreen" wire:click.stop>
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fas fa-palette me-2"></i>
                        تصميم إبداعي للسلة
                    </h3>
                    <button wire:click="closeCartDesignModal" class="modal-close-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="modal-body-fullscreen">
                    @if ($showDesignSelector)
                        @livewire('design-selector')
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Upload Design Modal -->
    @if ($showUploadDesignModal)
        <div class="upload-design-modal-overlay" wire:click="closeUploadDesignModal">
            <div class="upload-design-modal" wire:click.stop>
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fas fa-cloud-upload-alt me-2"></i>
                        رفع تصاميم جاهزة
                    </h3>
                    <button wire:click="closeUploadDesignModal" class="modal-close-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="upload-section">
                        <div class="upload-area" id="upload-area">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h4 class="upload-title">اسحب وأفلت الصور هنا أو انقر للاختيار</h4>
                            <p class="upload-subtitle">يمكنك رفع حتى 10 صور في المرة الواحدة</p>
                            <input type="file" id="design-files" multiple accept="image/*" style="display: none;">
                        </div>
                        
                        <div class="upload-actions">
                            <button type="button" class="upload-btn" onclick="document.getElementById('design-files').click()">
                                <i class="fas fa-folder-open"></i>
                                <span>اختيار من المعرض</span>
                            </button>
                            <button type="button" class="camera-btn" onclick="openCamera()">
                                <i class="fas fa-camera"></i>
                                <span>التقاط صورة</span>
                            </button>
                        </div>
                        
                        <div class="selected-files" id="selected-files" style="display: none;">
                            <h5>الملفات المختارة:</h5>
                            <div class="files-list" id="files-list"></div>
                            <div class="files-actions">
                                <button type="button" class="clear-files-btn" onclick="clearSelectedFiles()">
                                    <i class="fas fa-trash"></i>
                                    <span>مسح الكل</span>
                                </button>
                                <button type="button" class="upload-files-btn" onclick="uploadDesigns()">
                                    <i class="fas fa-upload"></i>
                                    <span>رفع التصاميم</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        /* Full Screen Cart Design Modal */
        .cart-design-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .cart-design-modal-fullscreen {
            width: 100vw;
            height: 100vh;
            background: white;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border-radius: 0;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: none;
            z-index: 10;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .modal-close-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .modal-body-fullscreen {
            flex: 1;
            overflow: hidden;
            background: #f8f9fa;
            position: relative;
        }

        /* Order Summary Styles */
        .order-summary-card {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, #ffffff 100%);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid var(--brand-yellow);
            padding: 2rem;
            margin-top: 2rem;
        }

        .order-summary-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .summary-title {
            color: var(--brand-brown);
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            text-align: center;
        }

        .summary-details {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(42, 30, 30, 0.1);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(42, 30, 30, 0.1);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 1rem;
        }

        .summary-value {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1rem;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-top: 2px solid var(--brand-yellow);
            margin-top: 0.5rem;
        }

        .summary-total .summary-label {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .summary-total-value {
            color: var(--brand-brown);
            font-weight: 800;
            font-size: 1.3rem;
            background: var(--brand-yellow);
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .summary-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Cart Actions Styles */
        .cart-actions {
            margin-top: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, #ffffff 100%);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(42, 30, 30, 0.1);
            border: 2px solid var(--brand-yellow);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .checkout-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            border: 2px solid rgba(42, 30, 30, 0.3);
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
            transition: all 0.3s ease;
            flex: 1;
            justify-content: center;
            min-width: 200px;
        }

        .checkout-btn:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        .appointment-btn {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            color: var(--brand-brown);
            border: 2px solid rgba(42, 30, 30, 0.2);
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.2);
            transition: all 0.3s ease;
            flex: 1;
            justify-content: center;
            min-width: 200px;
        }

        .appointment-btn:hover {
            background: linear-gradient(135deg, var(--brand-yellow-dark) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.3);
        }

        /* Make design selector take full screen */
        .modal-body-fullscreen .design-selector {
            height: 100%;
            overflow-y: auto;
        }

        /* Ensure design studio modal takes full screen */
        .modal-body-fullscreen .design-studio-modal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 10000 !important;
        }

        .modal-body-fullscreen .studio-container {
            width: 100vw !important;
            height: 100vh !important;
            border-radius: 0 !important;
        }

        /* Mobile Responsive Improvements */
        @media (max-width: 768px) {
            .cart-item-content {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1rem;
            }

            .cart-item-image {
                justify-self: center;
            }

            .cart-item-image img {
                width: 80px;
                height: 80px;
            }

            .cart-item-quantity {
                flex-direction: row;
                justify-content: center;
                gap: 1rem;
            }

            .cart-item-actions {
                flex-direction: row;
                justify-content: center;
            }

            .summary-actions {
                flex-direction: column;
            }

            .checkout-btn,
            .appointment-btn {
                min-width: auto;
                width: 100%;
            }

            .design-tools-grid {
                grid-template-columns: 1fr;
            }

            .saved-designs-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .saved-designs-header {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }

            .clear-all-designs-btn {
                width: 100%;
                justify-content: center;
            }

            .delete-design-btn {
                font-size: 0.7rem;
                padding: 0.4rem 0.8rem;
            }

            .cart-header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .cart-header-info {
                flex-direction: column;
                gap: 1rem;
            }

            .cart-icon {
                width: 60px;
                height: 60px;
            }

            .cart-icon i {
                font-size: 1.5rem;
            }

            .cart-header-title {
                font-size: 1.5rem;
            }

            .cart-header-subtitle {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .cart-item-content {
                padding: 0.75rem;
            }

            .cart-item-title {
                font-size: 1rem;
            }

            .quantity-controls {
                padding: 0.25rem;
            }

            .quantity-btn {
                width: 30px;
                height: 30px;
                padding: 0.25rem;
            }

            .quantity-value {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }

            .remove-item-btn,
            .add-design-btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }

            .designs-list {
                gap: 0.25rem;
            }

            .design-item {
                padding: 0.25rem;
            }

            .design-thumb {
                width: 30px;
                height: 30px;
            }

            .design-title {
                font-size: 0.8rem;
            }

            .design-notes {
                font-size: 0.7rem;
            }

            .order-summary-card {
                padding: 1rem;
            }

            .summary-title {
                font-size: 1.2rem;
            }

            .summary-details {
                padding: 1rem;
            }

            .summary-label,
            .summary-value {
                font-size: 0.9rem;
            }

            .summary-total .summary-label {
                font-size: 1rem;
            }

            .summary-total-value {
                font-size: 1.1rem;
                padding: 0.4rem 0.8rem;
            }

            .checkout-btn,
            .appointment-btn {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
    </div>

