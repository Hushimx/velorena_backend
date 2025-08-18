<div>
    <!-- Search and Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                <input type="text" wire:model.live.debounce.500ms="search" wire:key="search-input"
                    placeholder="البحث في العروض..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('name')">
                            الاسم
                            {{-- @if ($sortField === 'name')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} mr-1"></i>
                            @endif --}}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الفئة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('base_price')">
                            السعر
                            {{-- @if ($sortField === 'base_price')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} mr-1"></i>
                            @endif --}}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المدينة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            تاريخ الإنشاء
                            {{-- @if ($sortField === 'created_at')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} mr-1"></i>
                            @endif --}}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $listing)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($listing->images && count($listing->images) > 0)
                                        <img class="h-10 w-10 rounded-lg object-cover ml-3"
                                            src="{{ $listing->images[0] }}" alt="">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center ml-3">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $listing->name }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($listing->brand . ' ' . $listing->model, 30) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $listing->category->name ?? 'غير محدد' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($listing->base_price, 2) }} ر.س</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $listing->city->name ?? ($listing->city ?? 'غير محدد') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $listing->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $listing->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $listing->is_available ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $listing->is_available ? 'متاح' : 'غير متاح' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $listing->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                <a href="{{ route('admin.users.show', $listing) }}"
                                    class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">لا توجد عروض</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $users->links() }}
            {{-- OR better: --}}
            {{-- {{ $users->links(data: ['scrollTo' => false]) }} --}}
        </div>


    </div>
</div>
