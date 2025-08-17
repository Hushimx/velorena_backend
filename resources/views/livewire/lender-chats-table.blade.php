<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex-shrink-0 p-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">المحادثات</h2>
        <div class="relative">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" wire:model="search" placeholder="البحث في المحادثات..." 
                class="w-full pl-3 pr-10 py-2 text-sm bg-gray-50 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 placeholder-gray-500">
        </div>
    </div>

    <!-- Chat List -->
    <div class="flex-1 overflow-y-auto">
        @forelse($chats as $chat)
            @php
                $lastMessage = $chat->messages()->latest()->first();
                $unreadCount = $chat->messages()->where('user_id', '!=', null)->where('read_at', null)->count();
            @endphp
            
            <div class="flex items-center px-4 py-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 {{ request()->route('chat') && request()->route('chat')->id == $chat->id ? 'bg-green-50 border-r-4 border-r-green-500' : '' }}"
                 onclick="window.location.href='{{ route('lender.chats.show', $chat) }}'">
                
                <!-- Chat Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <!-- User Avatar -->
                            <div class="relative flex-shrink-0">
                                <div class="h-11 w-11 rounded-full bg-green-500 flex items-center justify-center text-white font-medium text-sm">
                                    {{ substr($chat->user->name, 0, 2) }}
                                </div>
                                @if($unreadCount > 0)
                                    <div class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Name and Status -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 truncate text-sm">{{ $chat->user->name }}</h3>
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                    <span class="text-xs text-gray-500">متصل</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Time -->
                        <div class="text-right">
                            <span class="text-xs text-gray-400">
                                {{ $chat->updated_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Last Message -->
                    <div class="mr-14">
                        <p class="text-sm text-gray-600 truncate">
                            @if($lastMessage)
                                @if($lastMessage->lender_id)
                                    <span class="text-green-600 font-medium">أنت: </span>
                                @endif
                                
                                @if($lastMessage->file_name)
                                    @php
                                        $extension = strtolower(pathinfo($lastMessage->file_name, PATHINFO_EXTENSION));
                                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                            $fileIcon = '📷';
                                            $fileText = 'صورة';
                                        } elseif (in_array($extension, ['mp4', 'mov', 'avi'])) {
                                            $fileIcon = '🎥';
                                            $fileText = 'فيديو';
                                        } elseif ($extension === 'pdf') {
                                            $fileIcon = '📄';
                                            $fileText = 'ملف PDF';
                                        } else {
                                            $fileIcon = '📋';
                                            $fileText = 'مستند';
                                        }
                                    @endphp
                                    {{ $fileIcon }} {{ $fileText }}
                                @else
                                    {{ Str::limit($lastMessage->message ?? 'رسالة', 35) }}
                                @endif
                            @else
                                <span class="text-gray-400 italic">لا توجد رسائل</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">لا توجد محادثات</h3>
                    <p class="text-sm text-gray-500">ستظهر محادثاتك هنا</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
