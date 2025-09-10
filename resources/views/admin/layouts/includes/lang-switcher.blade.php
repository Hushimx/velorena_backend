<!-- Language Switcher -->
<div class="relative">
    <button id="languageDropdown" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-300 hover:text-white transition-colors" onclick="toggleLanguageDropdown()">
        <i class="fas fa-globe"></i>
        <span class="hidden sm:block">{{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}</span>
        <i class="fas fa-chevron-down text-xs"></i>
    </button>
    
    <!-- Language Dropdown -->
    <div id="languageDropdownMenu" class="absolute left-0 mt-2 w-32 bg-gray-800 rounded-lg shadow-lg border border-gray-600 py-2 z-50 hidden">
        <a href="{{ route('admin.language.switch', 'ar') }}" class="flex items-center px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'ar' ? 'bg-gray-700 font-medium' : '' }}">
            <span class="ml-2">🇸🇦</span>
            <span>العربية</span>
            @if(app()->getLocale() === 'ar')
                <i class="fas fa-check text-green-400 mr-auto"></i>
            @endif
        </a>
        <a href="{{ route('admin.language.switch', 'en') }}" class="flex items-center px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'en' ? 'bg-gray-700 font-medium' : '' }}">
            <span class="ml-2">🇺🇸</span>
            <span>English</span>
            @if(app()->getLocale() === 'en')
                <i class="fas fa-check text-green-400 mr-auto"></i>
            @endif
        </a>
    </div>
</div>

<script>
function toggleLanguageDropdown() {
    const dropdown = document.getElementById('languageDropdownMenu');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('languageDropdownMenu');
    const button = document.getElementById('languageDropdown');
    
    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>