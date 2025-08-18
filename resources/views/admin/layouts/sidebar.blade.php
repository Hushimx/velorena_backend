<div id="sidebar" class="sidebar w-64 min-h-screen flex flex-col p-0">
    <div class="logo flex flex-col items-center py-8 border-b border-gray-200 relative">
        <img src="https://ui-avatars.com/api/?name=Lender&background=059669&color=fff&rounded=true&size=64" alt="Logo"
            class="w-16 h-16 mb-2">
        <div class="platform-title">لوحة المقرض</div>
        <button id="closeSidebar" class="lg:hidden text-gray-300 hover:text-white transition-colors absolute top-4 left-4"
            onclick="toggleSidebar()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <nav class="mt-4 flex-1 px-2 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>الرئيسية</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Users</span>
        </a>

        <a href="#" class="sidebar-link">
            <i class="fas fa-user"></i>
            <span>Designers</span>
        </a>
    </nav>
    <div class="mt-auto mb-4 px-2">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link logout-btn w-full flex items-center justify-center gap-3">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
</div>
