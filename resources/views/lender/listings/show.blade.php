@extends('lender.layouts.app')

@section('title', 'تفاصيل العرض')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header & Actions -->
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center space-x-4 space-x-reverse">
            <a href="{{ route('lender.listings.index') }}" class="text-gray-600 hover:text-gray-900 transition duration-150 transform hover:scale-110">
                <i class="fas fa-arrow-right text-xl"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">تفاصيل العرض</h2>
                <p class="text-sm text-gray-500">عرض وإدارة معلومات العرض</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 space-x-reverse">
            <a href="{{ route('lender.listings.edit', $listing) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-lg hover:from-yellow-500 hover:to-yellow-600 transition duration-150 transform hover:scale-105 shadow-md">
                <i class="fas fa-edit ml-2"></i>
                تعديل
            </a>
            <button type="button" onclick="confirmDelete({{ $listing->id }})" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-trash-alt ml-2"></i>
                حذف
            </button>
        </div>
    </div>

    <!-- Images Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">الصور</h3>
        <div class="flex flex-wrap gap-3">
            @if($listing->images && count($listing->images))
                @foreach($listing->images as $img)
                    <img src="{{ $img }}" class="h-24 w-24 object-cover rounded-lg border shadow cursor-pointer" alt="صورة العرض" onclick="openModal('{{ $img }}')">
                @endforeach
            @else
                <span class="text-gray-500">لا توجد صور</span>
            @endif
        </div>
        <div id="imgModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:1000; align-items:center; justify-content:center;">
            <span onclick="closeModal()" style="position:absolute;top:30px;right:40px;font-size:40px;color:white;cursor:pointer;">&times;</span>
            <img id="modalImg" src="" style="max-width:90vw;max-height:80vh;border-radius:12px;">
        </div>
        <script>
        function openModal(src) {
            document.getElementById('imgModal').style.display = 'flex';
            document.getElementById('modalImg').src = src;
        }
        function closeModal() {
            document.getElementById('imgModal').style.display = 'none';
        }
        </script>
    </div>

    <!-- Main Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Basic Info Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fas fa-info-circle text-green-500 ml-2"></i>معلومات أساسية</h3>
            <dl class="space-y-2">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الاسم:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الوصف:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->description }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">التصنيف:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->category ? $listing->category->name : '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الماركة:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->brand }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الموديل:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->model }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الرقم التسلسلي:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->serial_number }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">سنة الصنع:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->manufacturing_year }}</dd>
                </div>
            </dl>
        </div>
        
        <!-- Pricing Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fas fa-money-bill-wave text-green-500 ml-2"></i>الأسعار</h3>
            <dl class="space-y-2">
                @if($listing->daily_price_active && $listing->base_price)
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">سعر اليوم:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->base_price }} ر.س</dd>
                </div>
                @endif
                @if($listing->weekly_price_active && $listing->weekly_price)
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">سعر الأسبوع:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->weekly_price }} ر.س</dd>
                </div>
                @endif
                @if($listing->monthly_price_active && $listing->monthly_price)
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">سعر الشهر:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->monthly_price }} ر.س</dd>
                </div>
                @endif
                @if($listing->deposit_amount)
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الوديعة:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->deposit_amount }} ر.س</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">رسوم التوصيل:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->delivery_fee ?? 0 }} ر.س</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">رسوم الاستلام:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->pickup_fee ?? 0 }} ر.س</dd>
                </div>
            </dl>
        </div>

        <!-- Location & Stock Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fas fa-map-marker-alt text-red-500 ml-2"></i>الموقع والمخزون</h3>
            <dl class="space-y-2">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الموقع:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->location ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">المدينة:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->city ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الحي:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->neighborhood ?? '-' }}</dd>
                </div>
                @if($listing->latitude && $listing->longitude)
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الإحداثيات:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->latitude }}, {{ $listing->longitude }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">إجمالي المخزون:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->total_stock ?? 1 }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">المخزون المتاح:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->available_stock ?? $listing->total_stock ?? 1 }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Status & Settings Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fas fa-toggle-on text-green-500 ml-2"></i>الحالة والإعدادات</h3>
            <dl class="space-y-2">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">متوفر:</dt>
                    <dd class="text-sm">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $listing->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $listing->is_available ? 'نعم' : 'لا' }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">مميز:</dt>
                    <dd class="text-sm">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $listing->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $listing->is_featured ? 'نعم' : 'لا' }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">نشط:</dt>
                    <dd class="text-sm">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $listing->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $listing->is_active ? 'نعم' : 'لا' }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الحد الأدنى للأيام:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->minimum_rental_days ?? 1 }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">الحد الأقصى للأيام:</dt>
                    <dd class="text-sm text-gray-900">{{ $listing->maximum_rental_days ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        @if($listing->latitude && $listing->longitude)
        <!-- Map Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fas fa-map text-blue-500 ml-2"></i>الموقع على الخريطة</h3>
            <div id="show-map" class="w-full h-48 rounded-lg border"></div>
        </div>
        @endif
    </div>

    <!-- Orders Section -->
    <div class="bg-white rounded-lg shadow p-6 mt-8">
        <h2 class="text-xl font-bold mb-4">الطلبات على هذا العرض</h2>
        @if($listing->orders && $listing->orders->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">المستخدم</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">تاريخ البداية</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الانتهاء</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">عدد الأيام</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">الإجمالي</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($listing->orders as $order)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $order->user ? $order->user->name : '-' }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $order->start_date }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $order->end_date }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $order->rental_days }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $order->total }} ر.س</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full
                                        @if($order->status == 'pending') bg-yellow-200 text-yellow-900 border border-yellow-400
                                        @elseif($order->status == 'accepted') bg-green-200 text-green-900 border border-green-400
                                        @elseif($order->status == 'rejected') bg-red-200 text-red-900 border border-red-400
                                        @elseif($order->status == 'completed') bg-blue-200 text-blue-900 border border-blue-400
                                        @endif">
                                        {{ $order->status == 'pending' ? 'معلق' : ($order->status == 'accepted' ? 'مقبول' : ($order->status == 'rejected' ? 'مرفوض' : ($order->status == 'completed' ? 'مكتمل' : $order->status))) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-gray-500">لا توجد طلبات على هذا العرض.</div>
        @endif
    </div>
</div>

@if($listing->latitude && $listing->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map for showing location
    const showMap = L.map('show-map').setView([{{ $listing->latitude }}, {{ $listing->longitude }}], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(showMap);
    
    // Add marker for listing location
    L.marker([{{ $listing->latitude }}, {{ $listing->longitude }}]).addTo(showMap)
        .bindPopup('{{ $listing->name }}')
        .openPopup();
});
</script>
@endif

<script>
function confirmDelete(id) {
    if (confirm('هل أنت متأكد من حذف هذا العرض؟')) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '/lender/listings/' + id;
        let token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = '{{ csrf_token() }}';
        let method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(token);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection