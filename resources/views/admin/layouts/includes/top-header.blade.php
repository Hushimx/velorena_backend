<header class="shadow-lg">
    <div class="px-4 lg:px-8 py-4 flex justify-between items-center">
        <!-- Mobile menu button -->
        <button id="menuButton" class="lg:hidden text-gray-600 hover:text-gray-800 transition-colors"
            onclick="toggleSidebar()">
            <i class="fas fa-bars text-xl"></i>
        </button>

        @include('admin.layouts.includes.lang-switcher')

        <h1 class="text-xl font-bold text-gray-800">@yield('title', __('sidebar.main'))</h1>
        <div class="flex items-center relative">
            <span class="hidden sm:block profile-name">{{ Auth::guard('admin')->user()?->name }}</span>
            <div class="relative ml-3">
                @if (Auth::guard('admin')->user()?->image)
                    <img class="profile-img" src="{{ Storage::url(Auth::guard('admin')->user()?->image) }}"
                        alt="{{ __('Profile Picture') }}">
                @else
                    <img class="profile-img"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()?->name) }}&background=random"
                        alt="{{ __('Profile Picture') }}">
                @endif
                <span class="profile-status"></span>
            </div>
        </div>
    </div>
</header>
