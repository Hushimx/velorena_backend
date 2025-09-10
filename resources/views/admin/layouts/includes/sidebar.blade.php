<!-- Sidebar -->
<div id="sidebar" class="sidebar w-64 min-h-screen flex flex-col p-0">
    <div class="logo flex flex-col items-center py-8 border-b border-gray-600 relative">
        <img src="{{ asset('storage/qaads-logo.png') }}" alt="QAADS Logo" class="w-16 h-16 mb-2 object-contain">
        <div class="platform-title text-white">{{ __('admin.admin_dashboard') }}</div>
        <button id="closeSidebar" class="lg:hidden text-gray-300 hover:text-white transition-colors absolute top-4 left-4" onclick="toggleSidebar()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <nav class="mt-4 flex-1 px-2 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>{{ __('admin.dashboard') }}</span>
        </a>
        
        <!-- Users Tree -->
        <div class="tree-item">
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" onclick="toggleTree(event, 'users-tree')">
                <i class="fas fa-users"></i>
                <span>{{ __('admin.users') }}</span>
                <i class="fas fa-chevron-left tree-toggle" id="users-toggle"></i>
            </a>
            <div class="tree-children" id="users-tree">
                <a href="{{ route('admin.users.index') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>{{ __('admin.all_users') }}</span>
                </a>
                <a href="{{ route('admin.users.create') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                    <i class="fas fa-user-plus"></i>
                    <span>{{ __('admin.add_user') }}</span>
                </a>
            </div>
        </div>

        <!-- Orders Tree -->
        <div class="tree-item">
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" onclick="toggleTree(event, 'orders-tree')">
                <i class="fas fa-shopping-cart"></i>
                <span>{{ __('admin.orders') }}</span>
                <i class="fas fa-chevron-left tree-toggle" id="orders-toggle"></i>
            </a>
            <div class="tree-children" id="orders-tree">
                <a href="{{ route('admin.orders.index') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.orders.index') && !request()->get('status') ? 'active' : '' }}">
                    <i class="fas fa-list-alt"></i>
                    <span>{{ __('admin.all_orders') }}</span>
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'pending' ? 'active' : '' }}">
                    <i class="fas fa-hourglass-half text-yellow-500"></i>
                    <span>{{ __('admin.pending_orders') }}</span>
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'processing' ? 'active' : '' }}">
                    <i class="fas fa-cog text-blue-500"></i>
                    <span>{{ __('admin.order_processing') }}</span>
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'completed' ? 'active' : '' }}">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>{{ __('admin.order_completed') }}</span>
                </a>
            </div>
        </div>

        <!-- Products Tree -->
        <div class="tree-item">
            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" onclick="toggleTree(event, 'products-tree')">
                <i class="fas fa-box"></i>
                <span>{{ __('admin.products') }}</span>
                <i class="fas fa-chevron-left tree-toggle" id="products-toggle"></i>
            </a>
            <div class="tree-children" id="products-tree">
                <a href="{{ route('admin.products.index') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>{{ __('admin.all_products') }}</span>
                </a>
                <a href="{{ route('admin.products.create') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                    <i class="fas fa-plus"></i>
                    <span>{{ __('admin.add_product') }}</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>{{ __('admin.categories') }}</span>
                </a>
            </div>
        </div>

        <!-- Appointments -->
        <a href="{{ route('admin.appointments.index') }}" class="sidebar-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i>
            <span>{{ __('admin.appointments') }}</span>
        </a>

        <!-- Availability Slots -->
        <a href="{{ route('admin.availability-slots.index') }}" class="sidebar-link {{ request()->routeIs('admin.availability-slots.*') ? 'active' : '' }}">
            <i class="fas fa-clock"></i>
            <span>{{ __('admin.availability_slots') }}</span>
        </a>

        <!-- Designers -->
        <a href="{{ route('admin.designers.index') }}" class="sidebar-link {{ request()->routeIs('admin.designers.*') ? 'active' : '' }}">
            <i class="fas fa-paint-brush"></i>
            <span>{{ __('admin.designers') }}</span>
        </a>

        <!-- Marketers -->
        <a href="{{ route('admin.marketers.index') }}" class="sidebar-link {{ request()->routeIs('admin.marketers.*') ? 'active' : '' }}">
            <i class="fas fa-bullhorn"></i>
            <span>{{ __('admin.marketers') }}</span>
        </a>

        <!-- Leads -->
        <a href="{{ route('admin.leads.index') }}" class="sidebar-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
            <i class="fas fa-user-friends"></i>
            <span>{{ __('admin.leads') }}</span>
        </a>

        <!-- Admins -->
        <a href="{{ route('admin.admins.index') }}" class="sidebar-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i>
            <span>{{ __('admin.admins') }}</span>
        </a>
    </nav>
</div>
