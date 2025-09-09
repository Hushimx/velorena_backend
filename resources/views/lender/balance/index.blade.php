@extends('lender.layouts.app')

@section('title', 'المحفظة والمدفوعات')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('lender.dashboard') }}" class="text-green-600 hover:underline mr-2">الرئيسية</a>
        <span class="mx-2">/</span>
        <span class="text-gray-500">المحفظة والمدفوعات</span>
    </div>

    <!-- Balance Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Balance -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">الرصيد الإجمالي</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($totalBalance, 2) }} ر.س</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Pending Balance -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">الرصيد المعلق</h3>
                    <p class="text-3xl font-bold text-yellow-600">{{ number_format($pendingBalance, 2) }} ر.س</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Available Balance -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">الرصيد المتاح</h3>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($availableBalance, 2) }} ر.س</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-coins text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>


    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Withdrawal Section -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">سحب الأموال</h2>
                </div>
                <div class="p-6">
                    @if($availableBalance > 0)
                        <form method="POST" action="{{ route('lender.balance.withdraw') }}" id="withdrawForm">
                            @csrf
                            <div class="mb-4">
                                <label class="block mb-2 font-semibold text-gray-700">المبلغ المراد سحبه</label>
                                <div class="relative">
                                    <input type="number" name="amount" id="withdrawAmount" step="0.01" min="10" max="{{ $availableBalance }}"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('amount') border-red-500 @enderror"
                                           placeholder="أدخل المبلغ">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">ر.س</span>
                                    </div>
                                </div>
                                @error('amount')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                                <p class="text-sm text-gray-600 mt-1">الحد الأدنى: 10 ر.س | الحد الأقصى: {{ number_format($availableBalance, 2) }} ر.س</p>
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2 font-semibold text-gray-700">رقم الحساب البنكي (IBAN)</label>
                                @if(Auth::guard('lender')->user()->iban_number)
                                    <div class="p-3 bg-gray-50 border border-gray-300 rounded-lg">
                                        <p class="text-sm text-gray-700 font-mono">{{ Auth::guard('lender')->user()->iban_number }}</p>
                                        <p class="text-xs text-gray-500 mt-1">سيتم التحويل إلى هذا الحساب</p>
                                    </div>
                                @else
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-start text-yellow-700">
                                            <i class="fas fa-exclamation-triangle mt-0.5 ml-2 flex-shrink-0"></i>
                                            <div class="text-sm">
                                                <p class="font-medium">لم يتم إضافة رقم الحساب البنكي</p>
                                                <p class="mt-1">يرجى التواصل مع الإدارة لإضافة رقم الحساب البنكي الخاص بك</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2 font-semibold text-gray-700">ملاحظات إضافية (اختياري)</label>
                                <textarea name="account_details" rows="3" 
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('account_details') border-red-500 @enderror"
                                          placeholder="أي ملاحظات إضافية تريد إضافتها لطلب السحب"></textarea>
                                @error('account_details')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="w-full bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition duration-200 font-semibold">
                                <i class="fas fa-arrow-down ml-2"></i>
                                طلب السحب
                            </button>
                        </form>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-wallet text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">لا يوجد رصيد متاح</h3>
                            <p class="text-gray-600">لا يمكنك سحب الأموال حتى يكون لديك رصيد متاح</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">سجل المعاملات</h2>
                        <div class="flex gap-2">
                            <select id="filterType" class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">جميع المعاملات</option>
                                <option value="income">الإيرادات</option>
                                <option value="withdrawal">السحوبات</option>
                            </select>
                            <select id="filterStatus" class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">جميع الحالات</option>
                                <option value="completed">مكتملة</option>
                                <option value="pending">معلقة</option>
                                <option value="failed">فاشلة</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-right py-3 px-4 font-semibold text-gray-700">التاريخ</th>
                                        <th class="text-right py-3 px-4 font-semibold text-gray-700">النوع</th>
                                        <th class="text-right py-3 px-4 font-semibold text-gray-700">المبلغ</th>
                                        <th class="text-right py-3 px-4 font-semibold text-gray-700">الحالة</th>
                                        <th class="text-right py-3 px-4 font-semibold text-gray-700">التفاصيل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="py-3 px-4 text-sm text-gray-600">
                                                {{ $transaction->created_at->format('Y/m/d H:i') }}
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($transaction->type === 'income') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    @if($transaction->type === 'income')
                                                        <i class="fas fa-arrow-up ml-1"></i>
                                                        إيراد
                                                    @else
                                                        <i class="fas fa-arrow-down ml-1"></i>
                                                        سحب
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="font-semibold @if($transaction->type === 'income') text-green-600 @else text-red-600 @endif">
                                                    @if($transaction->type === 'income') + @else - @endif
                                                    {{ number_format($transaction->amount, 2) }} ر.س
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    @if($transaction->status === 'completed')
                                                        <i class="fas fa-check ml-1"></i>
                                                        مكتملة
                                                    @elseif($transaction->status === 'pending')
                                                        <i class="fas fa-clock ml-1"></i>
                                                        معلقة
                                                    @else
                                                        <i class="fas fa-times ml-1"></i>
                                                        فاشلة
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <button onclick="showTransactionDetails({{ $transaction->id }})" 
                                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                                    <i class="fas fa-eye ml-1"></i>
                                                    عرض التفاصيل
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">لا توجد معاملات</h3>
                            <p class="text-gray-600">لم يتم العثور على أي معاملات بعد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div id="transactionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">تفاصيل المعاملة</h3>
                    <button onclick="closeTransactionModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="transactionDetails">
                    <!-- Transaction details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filter transactions
document.getElementById('filterType').addEventListener('change', function() {
    filterTransactions();
});

document.getElementById('filterStatus').addEventListener('change', function() {
    filterTransactions();
});

function filterTransactions() {
    const type = document.getElementById('filterType').value;
    const status = document.getElementById('filterStatus').value;
    
    // You can implement AJAX filtering here or reload the page with filters
    const url = new URL(window.location);
    if (type) url.searchParams.set('type', type);
    else url.searchParams.delete('type');
    
    if (status) url.searchParams.set('status', status);
    else url.searchParams.delete('status');
    
    window.location.href = url.toString();
}

// Show transaction details
function showTransactionDetails(transactionId) {
    // You can implement AJAX to load transaction details
    fetch(`/lender/transactions/${transactionId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('transactionDetails').innerHTML = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">رقم المعاملة</label>
                        <p class="text-sm text-gray-900">${data.id}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">التاريخ</label>
                        <p class="text-sm text-gray-900">${data.created_at}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">النوع</label>
                        <p class="text-sm text-gray-900">${data.type_text}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">المبلغ</label>
                        <p class="text-sm text-gray-900">${data.amount} ر.س</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">الحالة</label>
                        <p class="text-sm text-gray-900">${data.status_text}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">الوصف</label>
                        <p class="text-sm text-gray-900">${data.description || 'لا يوجد وصف'}</p>
                    </div>
                </div>
            `;
            document.getElementById('transactionModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading transaction details:', error);
        });
}

function closeTransactionModal() {
    document.getElementById('transactionModal').classList.add('hidden');
}

// Withdrawal form validation
document.getElementById('withdrawForm')?.addEventListener('submit', function(e) {
    const amount = parseFloat(document.getElementById('withdrawAmount').value);
    const availableBalance = {{ $availableBalance }};
    
    if (amount < 10) {
        e.preventDefault();
        alert('الحد الأدنى للسحب هو 10 ر.س');
        return false;
    }
    
    if (amount > availableBalance) {
        e.preventDefault();
        alert('المبلغ المطلوب أكبر من الرصيد المتاح');
        return false;
    }
    
    if (!confirm('هل أنت متأكد من طلب السحب؟')) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection
