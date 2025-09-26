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
                <a href="{{ route('admin.highlights.index') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.highlights.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>{{ __('admin.highlights_management') }}</span>
                </a>
            </div>
        </div>

        <!-- Appointments -->
        <a href="{{ route('admin.appointments.index') }}" class="sidebar-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i>
            <span>{{ __('admin.appointments') }}</span>
        </a>

        <!-- Support Tickets Tree -->
        <div class="tree-item">
            <a href="{{ route('admin.support-tickets.index') }}" class="sidebar-link {{ request()->routeIs('admin.support-tickets.*') ? 'active' : '' }}" onclick="toggleTree(event, 'support-tickets-tree')">
                <i class="fas fa-ticket-alt"></i>
                <span>{{ __('admin.support_tickets') }}</span>
                <i class="fas fa-chevron-left tree-toggle" id="support-tickets-toggle"></i>
            </a>
            <div class="tree-children" id="support-tickets-tree">
                <a href="{{ route('admin.support-tickets.index') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.support-tickets.index') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>{{ __('admin.all_tickets') }}</span>
                </a>
                <a href="{{ route('admin.support-tickets.index', ['status' => 'open']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'open' ? 'active' : '' }}">
                    <i class="fas fa-folder-open text-green-500"></i>
                    <span>{{ __('admin.open_tickets') }}</span>
                </a>
                <a href="{{ route('admin.support-tickets.index', ['status' => 'in_progress']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'in_progress' ? 'active' : '' }}">
                    <i class="fas fa-cog text-blue-500"></i>
                    <span>{{ __('admin.in_progress_tickets') }}</span>
                </a>
                <a href="{{ route('admin.support-tickets.statistics') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.support-tickets.statistics') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar text-purple-500"></i>
                    <span>{{ __('admin.ticket_statistics') }}</span>
                </a>
            </div>
        </div>

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

        <!-- Settings Tree -->
        <div class="tree-item">
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.home-banners.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.site-settings.*') ? 'active' : '' }}" onclick="toggleTree(event, 'settings-tree')">
                <i class="fas fa-cog"></i>
                <span>{{ __('admin.settings') }}</span>
                <i class="fas fa-chevron-left tree-toggle" id="settings-toggle"></i>
            </a>
            <div class="tree-children" id="settings-tree">
                <a href="{{ route('admin.home-banners.index') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.home-banners.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i>
                    <span>{{ __('admin.home_banners_management') }}</span>
                </a>
                <a href="{{ route('admin.pages.index') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Pages Management</span>
                </a>
                <a href="{{ route('admin.site-settings.index') }}" class="sidebar-link tree-child {{ request()->routeIs('admin.site-settings.*') ? 'active' : '' }}">
                    <i class="fas fa-sliders-h"></i>
                    <span>Site Settings</span>
                </a>
            </div>
        </div>

        <!-- Admins -->
        <a href="{{ route('admin.admins.index') }}" class="sidebar-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i>
            <span>{{ __('admin.admins') }}</span>
        </a>
    </nav>
</div>
