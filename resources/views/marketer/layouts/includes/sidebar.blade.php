<!-- Sidebar -->
<div id="sidebar" class="sidebar w-64 min-h-screen flex flex-col p-0">
    <div class="logo flex flex-col items-center py-8 border-b border-gray-600 relative">
        <img src="{{ asset('storage/qaads-logo.png') }}" alt="QAADS Logo" class="w-16 h-16 mb-2 object-contain">
        <div class="platform-title text-white">{{ __('marketer.marketer_panel') }}</div>
        <button id="closeSidebar" class="lg:hidden text-gray-300 hover:text-white transition-colors absolute top-4 left-4" onclick="toggleSidebar()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <nav class="mt-4 flex-1 px-2 space-y-1">
        <a href="{{ route('marketer.dashboard') }}" class="sidebar-link {{ request()->routeIs('marketer.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>{{ __('marketer.dashboard') }}</span>
        </a>
        
        <!-- Leads Tree -->
        <div class="tree-item">
            <a href="{{ route('marketer.leads.index') }}" class="sidebar-link {{ request()->routeIs('marketer.leads.*') ? 'active' : '' }}" onclick="toggleTree(event, 'leads-tree')">
                <i class="fas fa-users"></i>
                <span>{{ __('marketer.leads') }}</span>
                <i class="fas fa-chevron-left tree-toggle" id="leads-toggle"></i>
            </a>
            <div class="tree-children" id="leads-tree">
                <a href="{{ route('marketer.leads.urgent') }}" class="sidebar-link tree-child {{ request()->routeIs('marketer.leads.urgent') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                    <span>{{ __('marketer.urgent_leads') }}</span>
                </a>
                <a href="{{ route('marketer.leads.index') }}" class="sidebar-link tree-child {{ request()->routeIs('marketer.leads.index') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>{{ __('marketer.all_leads') }}</span>
                </a>
                <a href="{{ route('marketer.leads.index', ['status' => 'new']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'new' ? 'active' : '' }}">
                    <i class="fas fa-plus text-green-500"></i>
                    <span>{{ __('marketer.new_leads') }}</span>
                </a>
                <a href="{{ route('marketer.leads.index', ['status' => 'contacted']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'contacted' ? 'active' : '' }}">
                    <i class="fas fa-phone text-blue-500"></i>
                    <span>{{ __('marketer.contacted') }}</span>
                </a>
                <a href="{{ route('marketer.leads.index', ['status' => 'qualified']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'qualified' ? 'active' : '' }}">
                    <i class="fas fa-star text-yellow-500"></i>
                    <span>{{ __('marketer.qualified') }}</span>
                </a>
                <a href="{{ route('marketer.leads.index', ['status' => 'closed_won']) }}" class="sidebar-link tree-child {{ request()->get('status') === 'closed_won' ? 'active' : '' }}">
                    <i class="fas fa-trophy text-purple-500"></i>
                    <span>{{ __('marketer.closed_won') }}</span>
                </a>
            </div>
        </div>

        <!-- Reports Tree -->
        <div class="tree-item">
            <a href="#" class="sidebar-link" onclick="toggleTree(event, 'reports-tree')">
                <i class="fas fa-chart-bar"></i>
                <span>{{ __('marketer.reports') }}</span>
                <i class="fas fa-chevron-left tree-toggle" id="reports-toggle"></i>
            </a>
            <div class="tree-children" id="reports-tree">
                <a href="#" class="sidebar-link tree-child">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ __('marketer.performance') }}</span>
                </a>
                <a href="#" class="sidebar-link tree-child">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ __('marketer.monthly_report') }}</span>
                </a>
            </div>
        </div>

        <!-- Profile -->
        <a href="#" class="sidebar-link">
            <i class="fas fa-user"></i>
            <span>{{ __('marketer.profile') }}</span>
        </a>
    </nav>
</div>

<script>
// Tree view functionality
function toggleTree(event, treeId) {
    event.preventDefault();
    const tree = document.getElementById(treeId);
    const toggle = document.getElementById(treeId.replace('-tree', '-toggle'));
    
    if (tree.classList.contains('expanded')) {
        tree.classList.remove('expanded');
        toggle.classList.remove('expanded');
    } else {
        tree.classList.add('expanded');
        toggle.classList.add('expanded');
    }
}

// Auto-expand leads tree if on leads pages
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    if (currentPath.includes('/leads/')) {
        const leadsTree = document.getElementById('leads-tree');
        const leadsToggle = document.getElementById('leads-toggle');
        if (leadsTree && leadsToggle) {
            leadsTree.classList.add('expanded');
            leadsToggle.classList.add('expanded');
        }
    }
});
</script>
