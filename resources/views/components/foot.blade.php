<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script>
    // Initialize Swiper
    document.addEventListener('DOMContentLoaded', function() {

        // Services Swiper
        const servicesSwiper = new Swiper('.servicesSwiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                    centeredSlides: false,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    centeredSlides: false,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 40,
                    centeredSlides: false,
                },
            },
        });

        // Add some interactive animations
        const productBoxes = document.querySelectorAll('.product-box');

        productBoxes.forEach((box, index) => {
            box.style.animationDelay = `${index * 0.1}s`;
            box.style.animation = 'fadeInUp 0.6s ease forwards';
        });

        // Add hover effects to buttons
        const buttons = document.querySelectorAll('.appointment-btn, .print-btn');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });

            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>

<!-- Bootstrap JavaScript is loaded via Vite in head.blade.php -->
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Enhanced Bootstrap dropdowns with better interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns with enhanced options
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl, {
            autoClose: true,
            boundary: 'viewport',
            display: 'dynamic',
            offset: [0, 4],
            popperConfig: {
                placement: 'bottom-end',
                modifiers: [
                    {
                        name: 'preventOverflow',
                        options: {
                            boundary: 'viewport',
                            padding: 8,
                        },
                    },
                    {
                        name: 'flip',
                        options: {
                            fallbackPlacements: ['bottom-start', 'top-end', 'top-start'],
                        },
                    },
                    {
                        name: 'computeStyles',
                        options: {
                            adaptive: true,
                            roundOffsets: true,
                        },
                    },
                ],
            },
        });
    });

    // Enhanced keyboard navigation
    document.addEventListener('keydown', function(e) {
        const dropdown = document.querySelector('.client-area-dropdown.show');
        if (!dropdown) return;

        const items = dropdown.querySelectorAll('.dropdown-item-enhanced, .logout-btn-enhanced');
        const activeItem = dropdown.querySelector('.dropdown-item-enhanced:focus, .logout-btn-enhanced:focus');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const currentIndex = Array.from(items).indexOf(activeItem);
            const nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
            items[nextIndex].focus();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const currentIndex = Array.from(items).indexOf(activeItem);
            const prevIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
            items[prevIndex].focus();
        } else if (e.key === 'Escape') {
            const toggle = document.getElementById('userDropdownToggle');
            if (toggle) {
                bootstrap.Dropdown.getInstance(toggle)?.hide();
                toggle.focus();
            }
        }
    });

    // Add click outside to close dropdown
    document.addEventListener('click', function(e) {
        const dropdown = document.querySelector('.client-area-dropdown');
        const toggle = document.getElementById('userDropdownToggle');
        
        if (dropdown && toggle && !dropdown.contains(e.target) && !toggle.contains(e.target)) {
            const dropdownInstance = bootstrap.Dropdown.getInstance(toggle);
            if (dropdownInstance) {
                dropdownInstance.hide();
            }
        }
    });

    // Add smooth scroll behavior for dropdown items
    document.querySelectorAll('.dropdown-item-enhanced, .logout-btn-enhanced').forEach(item => {
        item.addEventListener('click', function(e) {
            // Add a small delay to show the click effect before navigation
            if (this.href && !this.href.includes('#')) {
                e.preventDefault();
                const href = this.href;
                
                // Add click animation
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                    window.location.href = href;
                }, 150);
            }
        });
    });

    // Add ripple effect on click
    document.querySelectorAll('.dropdown-item-enhanced, .logout-btn-enhanced').forEach(item => {
        item.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Add focus management for better accessibility
    const userDropdownToggle = document.getElementById('userDropdownToggle');
    if (userDropdownToggle) {
        userDropdownToggle.addEventListener('shown.bs.dropdown', function() {
            const firstItem = document.querySelector('.client-area-dropdown .dropdown-item-enhanced');
            if (firstItem) {
                setTimeout(() => firstItem.focus(), 100);
            }
        });
    }
});
</script>

<!-- Add ripple effect styles -->
<style>
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Enhanced focus styles for better accessibility */
.dropdown-item-enhanced:focus,
.logout-btn-enhanced:focus {
    outline: 2px solid var(--brand-yellow);
    outline-offset: 2px;
    background: linear-gradient(135deg, var(--brand-yellow-light) 0%, rgba(255, 222, 159, 0.2) 100%);
}

/* Smooth transitions for all interactive elements */
.dropdown-item-enhanced,
.logout-btn-enhanced {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Loading state for dropdown items */
.dropdown-item-enhanced.loading {
    opacity: 0.7;
    pointer-events: none;
}

.dropdown-item-enhanced.loading::after {
    content: '';
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    border: 2px solid var(--brand-yellow);
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translateY(-50%) rotate(0deg); }
    100% { transform: translateY(-50%) rotate(360deg); }
}
</style>
@livewireScripts
</body>

</html>
