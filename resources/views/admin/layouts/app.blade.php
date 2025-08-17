@include('admin.layouts.head')

<!-- Sidebar Overlay for Mobile -->
<div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>
<div class="flex h-screen">
    <!-- Sidebar -->
    @include('admin.layouts.sidebar')
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        @include('admin.layouts.top-header')
        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-8 pb-24 lg:pb-32">
            <div class="animate-fade-in">
                @yield('content')
            </div>
        </main>
    </div>
</div>
@include('admin.layouts.foot')
