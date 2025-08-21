<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>
    /* Simple Toggle Switch */
    .toggle-switch {
        appearance: none;
        width: 50px;
        height: 24px;
        background-color: #d1d5db;
        border-radius: 12px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .toggle-switch:checked {
        background-color: #059669;
    }

    .toggle-switch::before {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: white;
        top: 3px;
        left: 3px;
        transition: transform 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .toggle-switch:checked::before {
        transform: translateX(26px);
    }

    .toggle-switch:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.3);
    }

    /* Form Input Focus Effects */
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }

    /* File Upload Area Hover Effect */
    .file-upload-area:hover {
        border-color: #059669;
        background-color: #f0fdf4;
    }
</style>
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
