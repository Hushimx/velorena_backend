<div>
    <!-- Search and Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                <input type="text" wire:model="search" placeholder="البحث في التقييمات..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">التقييم</label>
                <select wire:model="rating" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">جميع التقييمات</option>
                    <option value="5">5 نجوم</option>
                    <option value="4">4 نجوم</option>
                    <option value="3">3 نجوم</option>
                    <option value="2">2 نجوم</option>
                    <option value="1">1 نجمة</option>
                </select>
            </div>
            <div>
                <label for="perPage" class="block text-sm font-medium text-gray-700 mb-1">عدد العناصر</label>
                <select wire:model="perPage" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القائمة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                            wire:click="sortBy('rating')">
                            التقييم
                            @if($sortField === 'rating')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} mr-1"></i>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التعليق</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                            wire:click="sortBy('created_at')">
                            التاريخ
                            @if($sortField === 'created_at')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} mr-1"></i>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="h-8 w-8 rounded-full object-cover ml-3" 
                                        src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random" 
                                        alt="">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $review->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $review->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $review->listing->name }}</div>
                                <div class="text-sm text-gray-500">{{ $review->listing->brand }} {{ $review->listing->model }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <div class="text-sm text-gray-500">{{ $review->rating }}/5</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($review->comment, 80) }}</div>
                                @if($review->lender_reply)
                                    <div class="text-sm text-green-600 mt-1">
                                        <strong>ردك:</strong> {{ Str::limit($review->lender_reply, 60) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $review->created_at->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('lender.reviews.show', $review) }}" 
                                        class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!$review->lender_reply)
                                        <button onclick="openReplyModal({{ $review->id }}, '{{ addslashes($review->comment) }}')" 
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                لا توجد تقييمات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $reviews->links() }}
        </div>
    </div>

    <!-- Reply Modal -->
    <div id="replyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">الرد على التقييم</h3>
            <form id="replyForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">التعليق الأصلي:</label>
                    <p id="originalComment" class="text-sm text-gray-600 bg-gray-50 p-3 rounded"></p>
                </div>
                <div class="mb-4">
                    <label for="lender_reply" class="block text-sm font-medium text-gray-700 mb-1">ردك:</label>
                    <textarea name="lender_reply" id="lender_reply" rows="4" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="اكتب ردك هنا..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeReplyModal()" 
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        إلغاء
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700">
                        إرسال الرد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openReplyModal(reviewId, comment) {
    document.getElementById('replyModal').classList.remove('hidden');
    document.getElementById('replyModal').classList.add('flex');
    document.getElementById('originalComment').textContent = comment;
    document.getElementById('replyForm').action = `/lender/reviews/${reviewId}/reply`;
}

function closeReplyModal() {
    document.getElementById('replyModal').classList.add('hidden');
    document.getElementById('replyModal').classList.remove('flex');
    document.getElementById('lender_reply').value = '';
}
</script>
@endpush
