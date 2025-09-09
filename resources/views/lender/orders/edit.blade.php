@extends('lender.layouts.app')

@section('title', 'تعديل الطلب')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تعديل الطلب #{{ $order->id }}</h1>
            <p class="text-gray-600">تعديل تفاصيل الطلب والتسعير</p>
        </div>
        <a href="{{ route('lender.orders.show', $order) }}" 
            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-right ml-2"></i>العودة
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('lender.orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Order Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الطلب</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم الطلب</label>
                            <input type="text" value="#{{ $order->id }}" disabled 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية</label>
                            <input type="text" value="{{ $order->start_date }}" disabled 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ النهاية</label>
                            <input type="text" value="{{ $order->end_date }}" disabled 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مدة الإيجار</label>
                            <input type="text" value="{{ $order->rental_days }} أيام" disabled 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                        </div>
                    </div>
                </div>

                <!-- Pricing Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">تعديل التسعير</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">الكمية</label>
                            <input type="number" min="1" max="10" name="quantity" id="quantity" 
                                value="{{ old('quantity', $order->quantity ?? 1) }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-500 mt-1">عدد القطع المطلوبة</p>
                            @error('quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="item_price" class="block text-sm font-medium text-gray-700 mb-2">سعر القطعة (ر.س)</label>
                            <input type="number" step="0.01" name="item_price" id="item_price" 
                                value="{{ old('item_price', $order->item_price) }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            @error('item_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="deposit_amount" class="block text-sm font-medium text-gray-700 mb-2">مبلغ التأمين (ر.س)</label>
                            <input type="number" step="0.01" name="deposit_amount" id="deposit_amount" 
                                value="{{ old('deposit_amount', $order->deposit_amount) }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-500 mt-1">اتركه فارغاً لإلغاء التأمين</p>
                            @error('deposit_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="delivery_fee" class="block text-sm font-medium text-gray-700 mb-2">رسوم التوصيل (ر.س)</label>
                            <input type="number" step="0.01" name="delivery_fee" id="delivery_fee" 
                                value="{{ old('delivery_fee', $order->delivery_fee) }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-500 mt-1">اتركه فارغاً لإلغاء رسوم التوصيل</p>
                            @error('delivery_fee')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="pickup_fee" class="block text-sm font-medium text-gray-700 mb-2">رسوم الاستلام (ر.س)</label>
                            <input type="number" step="0.01" name="pickup_fee" id="pickup_fee" 
                                value="{{ old('pickup_fee', $order->pickup_fee) }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-500 mt-1">اتركه فارغاً لإلغاء رسوم الاستلام</p>
                            @error('pickup_fee')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Preview -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900 mb-3">معاينة الإجمالي</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">الكمية:</span>
                            <span id="preview-quantity">{{ $order->quantity ?? 1 }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">سعر القطعة:</span>
                            <span id="preview-item-price">{{ number_format($order->item_price, 2) }} ر.س</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">إجمالي سعر الإيجار:</span>
                            <span id="preview-rental-total">{{ number_format($order->item_price * $order->rental_days * ($order->quantity ?? 1), 2) }} ر.س</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">مبلغ التأمين:</span>
                            <span id="preview-deposit">{{ $order->deposit_amount ? number_format($order->deposit_amount, 2) . ' ر.س' : 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">رسوم التوصيل:</span>
                            <span id="preview-delivery">{{ $order->delivery_fee > 0 ? number_format($order->delivery_fee, 2) . ' ر.س' : 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">رسوم الاستلام:</span>
                            <span id="preview-pickup">{{ $order->pickup_fee > 0 ? number_format($order->pickup_fee, 2) . ' ر.س' : 'غير محدد' }}</span>
                        </div>
                    </div>
                    <div class="border-t pt-2">
                        <div class="flex justify-between text-lg font-semibold">
                            <span class="text-gray-900">الإجمالي:</span>
                            <span id="preview-total" class="text-green-600">{{ number_format($order->total, 2) }} ر.س</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('lender.orders.chat', $order) }}" 
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-comments ml-2"></i>محادثة العميل
                </a>
                <a href="{{ route('lender.orders.show', $order) }}" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                    إلغاء
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save ml-2"></i>حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const itemPriceInput = document.getElementById('item_price');
    const depositInput = document.getElementById('deposit_amount');
    const deliveryInput = document.getElementById('delivery_fee');
    const pickupInput = document.getElementById('pickup_fee');
    
    const previewQuantity = document.getElementById('preview-quantity');
    const previewItemPrice = document.getElementById('preview-item-price');
    const previewRentalTotal = document.getElementById('preview-rental-total');
    const previewDeposit = document.getElementById('preview-deposit');
    const previewDelivery = document.getElementById('preview-delivery');
    const previewPickup = document.getElementById('preview-pickup');
    const previewTotal = document.getElementById('preview-total');
    
    function updatePreview() {
        const quantity = parseInt(quantityInput.value) || 1;
        const itemPrice = parseFloat(itemPriceInput.value) || 0;
        const deposit = parseFloat(depositInput.value) || 0;
        const delivery = parseFloat(deliveryInput.value) || 0;
        const pickup = parseFloat(pickupInput.value) || 0;
        const rentalDays = {{ $order->rental_days }};
        
        const rentalTotal = itemPrice * rentalDays * quantity;
        const total = rentalTotal + deposit + delivery + pickup;
        
        previewQuantity.textContent = quantity;
        previewItemPrice.textContent = itemPrice.toFixed(2) + ' ر.س';
        previewRentalTotal.textContent = rentalTotal.toFixed(2) + ' ر.س';
        previewDeposit.textContent = deposit > 0 ? deposit.toFixed(2) + ' ر.س' : 'غير محدد';
        previewDelivery.textContent = delivery > 0 ? delivery.toFixed(2) + ' ر.س' : 'غير محدد';
        previewPickup.textContent = pickup > 0 ? pickup.toFixed(2) + ' ر.س' : 'غير محدد';
        previewTotal.textContent = total.toFixed(2) + ' ر.س';
    }
    
    quantityInput.addEventListener('input', updatePreview);
    itemPriceInput.addEventListener('input', updatePreview);
    depositInput.addEventListener('input', updatePreview);
    deliveryInput.addEventListener('input', updatePreview);
    pickupInput.addEventListener('input', updatePreview);
    
    // Initial update
    updatePreview();
});
</script>
@endsection
