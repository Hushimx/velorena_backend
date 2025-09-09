<!-- Sidebar -->
<div id="sidebar" class="sidebar w-64 min-h-screen flex flex-col p-0">
    <div class="logo flex flex-col items-center py-8 border-b border-gray-200 relative">
        <img src="https://ui-avatars.com/api/?name=Lender&background=059669&color=fff&rounded=true&size=64" alt="Logo" class="w-16 h-16 mb-2">
        <div class="platform-title">منصة جارك </div>
        <button id="closeSidebar" class="lg:hidden text-gray-300 hover:text-white transition-colors absolute top-4 left-4" onclick="toggleSidebar()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <nav class="mt-4 flex-1 px-2 space-y-1">
        <a href="{{ route('lender.dashboard') }}" class="sidebar-link {{ request()->routeIs('lender.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>الرئيسية</span>
        </a>
        
        <!-- Listings Tree -->
        <div class="tree-item">
            <a href="{{ route('lender.listings.index') }}" class="sidebar-link {{ request()->routeIs('lender.listings.*') ? 'active' : '' }}" onclick="toggleTree(event, 'listings-tree')">
                <i class="fas fa-boxes"></i>
                <span>عروضي</span>
                <i class="fas fa-chevron-left tree-toggle" id="listings-toggle"></i>
            </a>
            <div class="tree-children" id="listings-tree">
                <a href="{{ route('lender.listings.index') }}" class="sidebar-link tree-child {{ request()->routeIs('lender.listings.index') && !request()->get('status') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>جميع العروض</span>
                </a>
                <a href="{{ route('lender.listings.index', ['status' => 'active']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'active' ? 'active' : '' }}">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>العروض النشطة</span>
                </a>
                <a href="{{ route('lender.listings.index', ['status' => 'pending']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'pending' ? 'active' : '' }}">
                    <i class="fas fa-hourglass-half text-yellow-500"></i>
                    <span>قيد المراجعة</span>
                </a>
                <a href="{{ route('lender.listings.index', ['status' => 'inactive']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'inactive' ? 'active' : '' }}">
                    <i class="fas fa-pause-circle text-gray-500"></i>
                    <span>العروض غير النشطة</span>
                </a>
                <a href="{{ route('lender.listings.index', ['status' => 'rejected']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'rejected' ? 'active' : '' }}">
                    <i class="fas fa-times-circle text-red-500"></i>
                    <span>المرفوضة</span>
                </a>
            </div>
        </div>
        
        
        <!-- Orders Tree -->
        <div class="tree-item">
            <a href="{{ route('lender.orders.index') }}" class="sidebar-link {{ request()->routeIs('lender.orders.*') ? 'active' : '' }}" onclick="toggleTree(event, 'orders-tree')">
                <i class="fas fa-shopping-cart"></i>
                <span>الطلبات</span>
                <i class="fas fa-chevron-left tree-toggle" id="orders-toggle"></i>
            </a>
            <div class="tree-children" id="orders-tree">
                <a href="{{ route('lender.orders.index') }}" class="sidebar-link tree-child {{ request()->routeIs('lender.orders.index') && !request()->get('status') ? 'active' : '' }}">
                    <i class="fas fa-list-alt"></i>
                    <span>جميع الطلبات</span>
                </a>
                <a href="{{ route('lender.orders.index', ['status' => 'pending']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'pending' ? 'active' : '' }}">
                    <i class="fas fa-hourglass-half text-yellow-500"></i>
                    <span>الطلبات المعلقة</span>
                </a>
                <a href="{{ route('lender.orders.index', ['status' => 'approved']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'approved' ? 'active' : '' }}">
                    <i class="fas fa-check text-blue-500"></i>
                    <span>المقبولة</span>
                </a>
                <a href="{{ route('lender.orders.index', ['status' => 'paid']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'paid' ? 'active' : '' }}">
                    <i class="fas fa-credit-card text-purple-500"></i>
                    <span>المدفوعة</span>
                </a>
                <a href="{{ route('lender.orders.index', ['status' => 'active']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'active' ? 'active' : '' }}">
                    <i class="fas fa-play-circle text-indigo-500"></i>
                    <span>النشطة</span>
                </a>



            </div>
        </div>
        
        <a href="{{ route('lender.chats.index') }}" class="sidebar-link {{ request()->routeIs('lender.chats.*') ? 'active' : '' }}">
            <i class="fas fa-comments"></i>
            <span>الدردشات</span>
        </a>
        <a href="{{ route('lender.reviews.index') }}" class="sidebar-link {{ request()->routeIs('lender.reviews.*') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span>التقييمات</span>
        </a>
        <a href="{{ route('lender.coupons.index') }}" class="sidebar-link {{ request()->routeIs('lender.coupons.*') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i>
            <span>الكوبونات</span>
        </a>
        <a href="{{ route('lender.balance.index') }}" class="sidebar-link {{ request()->routeIs('lender.balance.*') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i>
            <span>المحفظة</span>
        </a>
    </nav>
    <div class="mt-auto mb-4 px-2">
        <button class="sidebar-link bg-white text-gray-700 border-2 border-gray-300 hover:bg-gray-50 hover:border-gray-400 w-full flex items-center justify-center gap-3 transition-colors">
            <i class="fas fa-plus"></i>
            <span>إضافة عرض جديد</span>
        </button>
    </div>
</div>
