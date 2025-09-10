<!-- Sidebar -->
<div id="sidebar" class="sidebar w-64 min-h-screen flex flex-col p-0">
    <div class="logo flex flex-col items-center py-8 border-b border-gray-600 relative">
        <img src="{{ asset('storage/qaads-logo.png') }}" alt="QAADS Logo" class="w-16 h-16 mb-2 object-contain">
        <div class="platform-title text-white">{{ __('dashboard.designer_dashboard') }}</div>
        <button id="closeSidebar" class="lg:hidden text-gray-300 hover:text-white transition-colors absolute top-4 left-4" onclick="toggleSidebar()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <nav class="mt-4 flex-1 px-2 space-y-1">
        <a href="{{ route('designer.dashboard') }}" class="sidebar-link {{ request()->routeIs('designer.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>{{ __('dashboard.dashboard') }}</span>
        </a>
        
        <!-- Appointments Tree -->
        <div class="tree-item">
            <a href="{{ route('designer.appointments.dashboard') }}" class="sidebar-link {{ request()->routeIs('designer.appointments.*') ? 'active' : '' }}" onclick="toggleTree(event, 'appointments-tree')">
                <i class="fas fa-calendar-check"></i>
                <span>{{ __('dashboard.appointments') }}</span>
                <i class="fas fa-chevron-left tree-toggle" id="appointments-toggle"></i>
            </a>
            <div class="tree-children" id="appointments-tree">
                <a href="{{ route('designer.appointments.dashboard') }}" class="sidebar-link tree-child {{ request()->routeIs('designer.appointments.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-hand-paper text-red-500"></i>
                    <span>{{ __('dashboard.new_appointments') }}</span>
                    <div class="flex items-center gap-1 ml-auto">
                        <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                        <span class="text-xs text-gray-400">Live</span>
                    </div>
                </a>
                <a href="{{ route('designer.appointments.index') }}" class="sidebar-link tree-child {{ request()->routeIs('designer.appointments.index') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>{{ __('dashboard.all_appointments') }}</span>
                </a>
                <a href="{{ route('designer.appointments.upcoming') }}" class="sidebar-link tree-child {{ request()->routeIs('designer.appointments.upcoming') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt text-green-500"></i>
                    <span>{{ __('dashboard.upcoming_appointments') }}</span>
                </a>
            </div>
        </div>

        <!-- Orders Tree -->
        <div class="tree-item">
            <a href="{{ route('designer.orders.index') }}" class="sidebar-link {{ request()->routeIs('designer.orders.*') ? 'active' : '' }}" onclick="toggleTree(event, 'orders-tree')">
                <i class="fas fa-shopping-cart"></i>
                <span>{{ __('dashboard.orders') }}</span>
                <i class="fas fa-chevron-left tree-toggle" id="orders-toggle"></i>
            </a>
            <div class="tree-children" id="orders-tree">
                <a href="{{ route('designer.orders.index') }}" class="sidebar-link tree-child {{ request()->routeIs('designer.orders.index') ? 'active' : '' }}">
                    <i class="fas fa-list-alt"></i>
                    <span>{{ __('dashboard.all_orders') }}</span>
                </a>
                <a href="{{ route('designer.orders.index', ['status' => 'pending']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'pending' ? 'active' : '' }}">
                    <i class="fas fa-hourglass-half text-yellow-500"></i>
                    <span>{{ __('dashboard.pending_orders') }}</span>
                </a>
                <a href="{{ route('designer.orders.index', ['status' => 'processing']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'processing' ? 'active' : '' }}">
                    <i class="fas fa-cog text-blue-500"></i>
                    <span>{{ __('dashboard.order_processing') }}</span>
                </a>
                <a href="{{ route('designer.orders.index', ['status' => 'completed']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'completed' ? 'active' : '' }}">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>{{ __('dashboard.order_completed') }}</span>
                </a>
            </div>
        </div>

        <!-- Profile -->
        <a href="{{ route('designer.profile.edit') }}" class="sidebar-link {{ request()->routeIs('designer.profile.*') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>{{ __('dashboard.profile') }}</span>
        </a>

        <!-- Portfolio -->
        <a href="{{ route('designer.portfolio.index') }}" class="sidebar-link {{ request()->routeIs('designer.portfolio.*') ? 'active' : '' }}">
            <i class="fas fa-images"></i>
            <span>{{ __('dashboard.portfolio') }}</span>
        </a>
    </nav>
</div>

<script>
// Tree view functionality
function toggleTree(event, treeId) {
    event.preventDefault();
    const tree = document.getElementById(treeId);
    const toggle = document.getElementById(treeId.replace('-tree', '-toggle'));
    
    if (tree.style.display === 'none' || tree.style.display === '') {
        tree.style.display = 'block';
        toggle.style.transform = 'rotate(-90deg)';
    } else {
        tree.style.display = 'none';
        toggle.style.transform = 'rotate(0deg)';
    }
}

// Auto-expand appointments tree if on appointments pages
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    if (currentPath.includes('/appointments/')) {
        const appointmentsTree = document.getElementById('appointments-tree');
        const appointmentsToggle = document.getElementById('appointments-toggle');
        if (appointmentsTree && appointmentsToggle) {
            appointmentsTree.style.display = 'block';
            appointmentsToggle.style.transform = 'rotate(-90deg)';
        }
    }
});
</script>
