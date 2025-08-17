@extends('lender.layouts.app')

@section('title', 'المحادثة')

@push('styles')
<style>
/* Remove padding from main content for full height chat */
main {
    padding: 0 !important;
    height: calc(100vh - 4rem); /* Account for header height */
    overflow: hidden;
}
/* Make content take full height */
.animate-fade-in {
    height: 100%;
    margin: 0;
}
</style>
@endpush

@section('content')
<div class="h-full w-full bg-gray-50 flex" dir="rtl">
    <!-- Chat Users Sidebar (Right side) -->
    <div class="w-80 bg-white border-l border-gray-200 flex flex-col">
        @livewire('lender-chats-table')
    </div>
    
    <!-- Main Chat Area (Middle) -->
    <div class="flex-1 bg-white flex flex-col max-h-[calc(100vh-4rem)] p-0">
        <!-- Clean Chat Header -->
    <div class="bg-white px-4 py-3 flex items-center justify-between border-b border-gray-100 flex-shrink-0">
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 chat-header-button">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div class="flex items-center gap-3 flex-1">
                <div class="w-9 h-9 rounded-full bg-green-500 flex items-center justify-center text-white font-medium text-sm">
                    {{ substr($chat->user->name, 0, 2) }}
                </div>
                <div class="flex-1">
                    <h3 class="font-medium text-gray-900 text-sm">{{ $chat->user->name }}</h3>
                    @if($chat->listing)
                        <p class="text-gray-500 text-xs">{{ Str::limit($chat->listing->name, 25) }}</p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Messages Container -->
    <div id="messages-container" class="flex-1 overflow-y-auto px-4 py-4 bg-gray-50 flex flex-col gap-3">
        @forelse($messages as $message)
            @php
                $isLenderMessage = $message->lender_id && !$message->user_id;
                $isUserMessage = $message->user_id && !$message->lender_id;
                
                // Determine file type for proper display
                $fileType = null;
                if ($message->file_path) {
                    $extension = strtolower(pathinfo($message->file_name, PATHINFO_EXTENSION));
                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $fileType = 'image';
                    } elseif (in_array($extension, ['mp4', 'mov', 'avi'])) {
                        $fileType = 'video';
                    } elseif ($extension === 'pdf') {
                        $fileType = 'pdf';
                    } else {
                        $fileType = 'document';
                    }
                }
            @endphp
            
            <div class="flex {{ $isLenderMessage ? 'justify-start' : 'justify-end' }}">
                <div class="max-w-[75%]">
                    <!-- Clean Message Bubble -->
                    <div class="{{ $isLenderMessage ? 'bg-green-500 text-white' : 'bg-white text-gray-800' }} 
                                rounded-2xl px-4 py-2 {{ $isLenderMessage ? '' : 'shadow-sm border border-gray-100' }}">
                        
                        <!-- Message Content -->
                        <div class="break-words">
                            @if($message->message)
                                <p class="text-sm leading-relaxed">{{ $message->message }}</p>
                            @endif
                            
                            @if($message->file_name)
                                <div class="mt-3">
                                    @if($fileType === 'image')
                                        <!-- Image Preview -->
                                        <div class="relative">
                                            <img src="{{ Storage::url($message->file_path) }}" 
                                                 alt="{{ $message->file_name }}"
                                                 class="max-w-full h-auto rounded-lg cursor-pointer max-h-64 object-cover"
                                                 onclick="openImageModal('{{ Storage::url($message->file_path) }}', '{{ $message->file_name }}')">
                                            <div class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                                <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                </svg>
                                                صورة
                                            </div>
                                        </div>
                                    @elseif($fileType === 'video')
                                        <!-- Video Preview -->
                                        <div class="relative">
                                            <video controls class="max-w-full h-auto rounded-lg max-h-64">
                                                <source src="{{ Storage::url($message->file_path) }}" type="video/mp4">
                                                متصفحك لا يدعم تشغيل الفيديو
                                            </video>
                                        </div>
                                    @else
                                        <!-- File Attachment -->
                                        <div class="p-3 {{ $isLenderMessage ? 'bg-green-600' : 'bg-gray-50' }} rounded-lg border {{ $isLenderMessage ? 'border-green-400' : 'border-gray-200' }}">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 {{ $isLenderMessage ? 'bg-green-400' : 'bg-green-100' }} rounded-lg flex items-center justify-center">
                                                        @if($fileType === 'pdf')
                                                            <svg class="w-4 h-4 {{ $isLenderMessage ? 'text-white' : 'text-red-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 {{ $isLenderMessage ? 'text-white' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium {{ $isLenderMessage ? 'text-white' : 'text-gray-900' }} truncate">
                                                        {{ $message->file_name }}
                                                    </p>
                                                    <a href="{{ Storage::url($message->file_path) }}" 
                                                       class="text-xs {{ $isLenderMessage ? 'text-green-100 hover:text-white' : 'text-green-600 hover:text-green-700' }}" 
                                                       target="_blank">
                                                        تحميل الملف
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <!-- Message Time -->
                        @if($message->message || $message->file_name)
                        <div class="flex justify-end mt-1">
                            <span class="text-xs {{ $isLenderMessage ? 'text-green-100' : 'text-gray-400' }}">
                                {{ $message->created_at->format('H:i') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">لا توجد رسائل</h3>
                    <p class="text-sm text-gray-500">ابدأ محادثة مع {{ $chat->user->name }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Message Input -->
    <div class="bg-white border-t border-gray-100 p-4 flex-shrink-0">
        <form action="{{ route('lender.chats.sendMessage', $chat) }}" method="POST" enctype="multipart/form-data" 
              class="flex items-end gap-3">
            @csrf
            
            <!-- Send Button -->
            <button type="submit" 
                    class="flex items-center justify-center w-10 h-10 bg-green-500 hover:bg-green-600 text-white rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
            
            <!-- Message Input -->
            <div class="flex-1 relative">
                <textarea name="message" 
                          placeholder="اكتب رسالتك هنا..." 
                          rows="1"
                          class="w-full px-4 py-3 border-0 bg-gray-50 rounded-full resize-none focus:outline-none focus:ring-2 focus:ring-green-500 placeholder-gray-500 text-right"
                          style="min-height: 40px; max-height: 120px;"
                          onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();this.form.submit();}"></textarea>
            </div>
            
            <!-- File Upload Options -->
            <div class="relative">
                <button type="button" id="file-menu-btn" 
                        class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-green-600 hover:bg-gray-100 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </button>
                
                <!-- File Type Menu -->
                <div id="file-menu" class="absolute bottom-12 left-0 bg-white border border-gray-200 rounded-lg shadow-lg py-2 hidden min-w-48 z-50">
                    <button type="button" onclick="selectFileType('image')" class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer w-full text-right">
                        <svg class="w-5 h-5 text-blue-500 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">صورة</span>
                    </button>
                    
                    <button type="button" onclick="selectFileType('video')" class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer w-full text-right">
                        <svg class="w-5 h-5 text-red-500 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                        </svg>
                        <span class="text-sm text-gray-700">فيديو</span>
                    </button>
                    
                    <button type="button" onclick="selectFileType('document')" class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer w-full text-right">
                        <svg class="w-5 h-5 text-green-500 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">مستند</span>
                    </button>
                </div>
                
                <!-- Hidden File Input -->
                <input type="file" id="file-upload" name="file" class="hidden" accept="image/*,video/*,.pdf,.doc,.docx,.txt,.zip,.rar" onchange="handleFileSelect(this)">
            </div>
        </form>
        
        <!-- File Preview -->
        <div id="file-preview" class="mt-3 hidden">
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                <div id="file-icon" class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <!-- Icon will be populated by JavaScript -->
                </div>
                <div class="flex-1">
                    <span id="file-name" class="text-sm font-medium text-gray-700"></span>
                    <div id="file-size" class="text-xs text-gray-500"></div>
                </div>
                <button type="button" onclick="clearFile()" class="text-gray-500 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Image Preview -->
            <div id="image-preview" class="hidden mt-2">
                <img id="preview-image" src="" alt="صورة معاينة" class="max-w-32 h-20 object-cover rounded-lg border">
            </div>
        </div>
        
        <!-- Error Messages -->
        @if($errors->any())
            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                <div class="text-sm text-red-600">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    <!-- Image Modal -->
    <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="relative max-w-4xl max-h-full p-4">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img id="modal-image" src="" alt="" class="max-w-full max-h-full object-contain">
        </div>
    </div>
</div>

<script>
// Auto-scroll to bottom
function scrollToBottom() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
}

// Scroll to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});

// File menu toggle
document.getElementById('file-menu-btn').addEventListener('click', function(e) {
    e.stopPropagation();
    const menu = document.getElementById('file-menu');
    menu.classList.toggle('hidden');
});

// Close menu when clicking outside
document.addEventListener('click', function() {
    const menu = document.getElementById('file-menu');
    menu.classList.add('hidden');
});

// Prevent menu from closing when clicking inside
document.getElementById('file-menu').addEventListener('click', function(e) {
    e.stopPropagation();
});

// File type selection
function selectFileType(type) {
    const fileInput = document.getElementById('file-upload');
    
    // Set appropriate accept attribute based on type
    switch(type) {
        case 'image':
            fileInput.accept = 'image/*';
            break;
        case 'video':
            fileInput.accept = 'video/*';
            break;
        case 'document':
            fileInput.accept = '.pdf,.doc,.docx,.txt,.zip,.rar';
            break;
    }
    
    // Store the selected type for later use
    fileInput.dataset.selectedType = type;
    
    // Hide menu and trigger file selection
    document.getElementById('file-menu').classList.add('hidden');
    fileInput.click();
}

// File selection handler
function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Validate file size (10MB max)
    const maxSize = 10 * 1024 * 1024;
    if (file.size > maxSize) {
        alert('حجم الملف كبير جداً. الحد الأقصى 10 ميجابايت');
        clearFile();
        return;
    }
    
    // Determine file type
    const fileType = determineFileType(file);
    
    // Show file preview
    showFilePreview(file, fileType);
}

// Determine file type from file object
function determineFileType(file) {
    const extension = file.name.split('.').pop().toLowerCase();
    const mimeType = file.type;
    
    if (mimeType.startsWith('image/') || ['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
        return 'image';
    } else if (mimeType.startsWith('video/') || ['mp4', 'mov', 'avi'].includes(extension)) {
        return 'video';
    } else if (mimeType === 'application/pdf' || extension === 'pdf') {
        return 'pdf';
    } else {
        return 'document';
    }
}

function showFilePreview(file, type) {
    const preview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const fileIcon = document.getElementById('file-icon');
    const imagePreview = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');
    
    // Set file name and size
    fileName.textContent = file.name;
    fileSize.textContent = formatFileSize(file.size);
    
    // Set appropriate icon based on type
    let iconHTML = '';
    let iconColor = 'bg-green-100';
    
    switch(type) {
        case 'image':
            iconHTML = '<svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>';
            iconColor = 'bg-blue-100';
            
            // Show image preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
            break;
            
        case 'video':
            iconHTML = '<svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>';
            iconColor = 'bg-red-100';
            imagePreview.classList.add('hidden');
            break;
            
        case 'document':
        default:
            iconHTML = '<svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>';
            iconColor = 'bg-green-100';
            imagePreview.classList.add('hidden');
            break;
    }
    
    fileIcon.className = `w-8 h-8 ${iconColor} rounded-lg flex items-center justify-center`;
    fileIcon.innerHTML = iconHTML;
    
    preview.classList.remove('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 بايت';
    const k = 1024;
    const sizes = ['بايت', 'كيلوبايت', 'ميجابايت', 'جيجابايت'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function clearFile() {
    // Clear file input
    document.getElementById('file-upload').value = '';
    
    // Hide previews
    document.getElementById('file-preview').classList.add('hidden');
    document.getElementById('image-preview').classList.add('hidden');
}

// Image modal functions
function openImageModal(imageSrc, imageName) {
    const modal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');
    
    modalImage.src = imageSrc;
    modalImage.alt = imageName;
    modal.classList.remove('hidden');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('image-modal');
    modal.classList.add('hidden');
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Auto-resize textarea
const textarea = document.querySelector('textarea[name="message"]');
textarea.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});

// Form validation
document.querySelector('button[type="submit"]').addEventListener('click', function(e) {
    const hasMessage = textarea.value.trim() !== '';
    const hasFile = document.getElementById('file-upload').files.length > 0;
    
    if (!hasMessage && !hasFile) {
        e.preventDefault();
        alert('يرجى كتابة رسالة أو إرفاق ملف');
    }
});
</script>

<style>
/* RTL Direction */
[dir="rtl"] {
    direction: rtl;
}

/* Clean scrollbar */
#messages-container::-webkit-scrollbar {
    width: 6px;
}

#messages-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

#messages-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

#messages-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Clean focus states */
textarea:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}

/* Responsive message width */
@media (max-width: 768px) {
    .max-w-xs {
        max-width: 85%;
    }
    .max-w-sm {
        max-width: 85%;
    }
    .max-w-md {
        max-width: 85%;
    }
}

/* Clean hover states */
button:hover {
    opacity: 0.9;
}

.chat-header-button:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

/* File menu positioning */
#file-menu {
    z-index: 50;
}

/* Image modal styles */
#image-modal {
    backdrop-filter: blur(4px);
}

/* RTL-specific adjustments */
[dir="rtl"] .gap-1 > :not([hidden]) ~ :not([hidden]) {
    margin-right: 0.25rem;
    margin-left: 0;
}

[dir="rtl"] .gap-2 > :not([hidden]) ~ :not([hidden]) {
    margin-right: 0.5rem;
    margin-left: 0;
}

[dir="rtl"] .gap-3 > :not([hidden]) ~ :not([hidden]) {
    margin-right: 0.75rem;
    margin-left: 0;
}

[dir="rtl"] .gap-4 > :not([hidden]) ~ :not([hidden]) {
    margin-right: 1rem;
    margin-left: 0;
}

/* RTL Text alignment */
[dir="rtl"] textarea {
    text-align: right;
}

/* Animation for smooth transitions */
#file-menu {
    transition: all 0.2s ease-in-out;
    transform-origin: bottom left;
}

#file-menu.hidden {
    opacity: 0;
    transform: scale(0.95) translateY(-10px);
}

/* File preview animation */
#file-preview {
    transition: all 0.3s ease-in-out;
}

#image-preview {
    transition: all 0.3s ease-in-out;
}

/* Message bubble animations */
.message-bubble {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
    </div>
</div>
@endsection
