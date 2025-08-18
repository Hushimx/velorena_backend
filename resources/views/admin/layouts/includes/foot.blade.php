<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    // Sidebar toggle functionality
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (window.innerWidth <= 1024) {
            sidebar.classList.toggle('sidebar-open');
            overlay.classList.toggle('active');
        } else {
            sidebar.classList.toggle('sidebar-hidden');
        }
    }
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const menuButton = document.getElementById('menuButton');
        if (window.innerWidth <= 1024) {
            if (!sidebar.contains(event.target) && !menuButton.contains(event.target) && sidebar.classList
                .contains('sidebar-open')) {
                sidebar.classList.remove('sidebar-open');
                overlay.classList.remove('active');
            }
        }
    });
    // Handle window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (window.innerWidth > 1024) {
            sidebar.classList.remove('sidebar-open');
            overlay.classList.remove('active');
        } else {
            sidebar.classList.remove('sidebar-hidden');
        }
    });
    // Handle flash messages
    @if (session('success'))
        Swal.fire({
            title: 'نجاح!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'حسناً'
        });
    @endif
    @if (session('error'))
        Swal.fire({
            title: 'خطأ!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'حسناً'
        });
    @endif
</script>
</body>

</html>
