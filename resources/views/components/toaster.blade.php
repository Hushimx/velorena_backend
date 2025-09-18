<div id="toaster-container" class="toaster-container">
    <!-- Toaster notifications will be dynamically added here -->
</div>

<style>
    .toaster-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 400px;
        width: 100%;
    }

    .toast {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(42, 30, 30, 0.12), 0 4px 16px rgba(42, 30, 30, 0.08);
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 14px;
        transform: translateX(100%);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border-left: 4px solid;
        font-family: 'Cairo', sans-serif;
        direction: rtl;
        position: relative;
        overflow: hidden;
        margin-bottom: 12px;
        will-change: transform, opacity;
        border: 1px solid #f0f0f0;
    }

    .toast.show {
        transform: translateX(0);
        opacity: 1;
    }

    /* Entrance animation keyframes */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* Add entrance animation */
    .toast.show {
        animation: slideInRight 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    }

    .toast.success {
        border-left-color: #10b981;
        background: linear-gradient(135deg, #f8fffe 0%, #f0fdf4 100%);
        border: 1px solid #e6fffa;
    }

    .toast.error {
        border-left-color: #ef4444;
        background: linear-gradient(135deg, #fffbfb 0%, #fef2f2 100%);
        border: 1px solid #fecaca;
    }

    .toast.warning {
        border-left-color: #f5d182;
        background: linear-gradient(135deg, #fffdf7 0%, #fff4e6 100%);
        border: 1px solid #f0d4a0;
    }

    .toast.info {
        border-left-color: #3a2e2e;
        background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
        border: 1px solid #e5e5e5;
    }

    .toast-icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .toast.success .toast-icon {
        background: #ecfdf5;
        color: #10b981;
        border: 1px solid #d1fae5;
    }

    .toast.error .toast-icon {
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fecaca;
    }

    .toast.warning .toast-icon {
        background: #fff4e6;
        color: #f5d182;
        border: 1px solid #f0d4a0;
    }

    .toast.info .toast-icon {
        background: #f5f5f5;
        color: #3a2e2e;
        border: 1px solid #e5e5e5;
    }

    .toast-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .toast-title {
        font-weight: 700;
        font-size: 14px;
        color: #2a1e1e;
        margin: 0;
    }

    .toast-message {
        font-weight: 500;
        font-size: 13px;
        color: #4a3535;
        margin: 0;
        line-height: 1.4;
    }

    .toast-close {
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 6px;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        flex-shrink: 0;
    }

    .toast-close:hover {
        background: rgba(42, 30, 30, 0.08);
        color: #2a1e1e;
        transform: scale(1.1);
    }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 0 0 12px 12px;
        transition: width linear;
    }

    .toast.success .toast-progress {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .toast.error .toast-progress {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    .toast.warning .toast-progress {
        background: linear-gradient(90deg, #f5d182, #f0d4a0);
    }

    .toast.info .toast-progress {
        background: linear-gradient(90deg, #3a2e2e, #2a1e1e);
    }

    /* Animation for mobile */
    @media (max-width: 768px) {
        .toaster-container {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }

        .toast {
            transform: translateY(-100%);
        }

        .toast.show {
            animation: slideInDown 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    }
</style>

<script>
    class Toaster {
        constructor() {
            this.container = document.getElementById('toaster-container');
            this.toasts = new Map();
        }

        show(message, type = 'success', title = null, duration = 5000) {
            const toastId = Date.now() + Math.random();
            const toast = this.createToast(toastId, message, type, title, duration);

            this.container.appendChild(toast);
            this.toasts.set(toastId, toast);

            // Force reflow to ensure the element is rendered
            toast.offsetHeight;

            // Trigger animation with requestAnimationFrame for smoother animation
            requestAnimationFrame(() => {
                toast.classList.add('show');
            });

            // Auto remove
            if (duration > 0) {
                setTimeout(() => {
                    this.remove(toastId);
                }, duration);
            }

            return toastId;
        }

        createToast(id, message, type, title, duration) {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.dataset.toastId = id;

            const icon = this.getIcon(type);
            const displayTitle = title || this.getDefaultTitle(type);

            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="${icon}"></i>
                </div>
                <div class="toast-content">
                    <h4 class="toast-title">${displayTitle}</h4>
                    <p class="toast-message">${message}</p>
                </div>
                <button class="toast-close" onclick="toaster.remove(${id})">
                    <i class="fas fa-times"></i>
                </button>
                <div class="toast-progress" style="width: 100%; transition-duration: ${duration}ms;"></div>
            `;

            // Start progress bar animation
            setTimeout(() => {
                const progressBar = toast.querySelector('.toast-progress');
                if (progressBar) {
                    progressBar.style.width = '0%';
                }
            }, 10);

            return toast;
        }

        getIcon(type) {
            const icons = {
                success: 'fas fa-check',
                error: 'fas fa-times',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            return icons[type] || icons.success;
        }

        getDefaultTitle(type) {
            const titles = {
                success: '{{ trans('messages.success') }}',
                error: '{{ trans('messages.error') }}',
                warning: '{{ trans('messages.warning') }}',
                info: '{{ trans('messages.info') }}'
            };
            return titles[type] || titles.success;
        }

        remove(id) {
            const toast = this.toasts.get(id);
            if (toast) {
                toast.classList.remove('show');
                // Wait for animation to complete before removing
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                    this.toasts.delete(id);
                }, 400); // Match the CSS transition duration
            }
        }

        clear() {
            this.toasts.forEach((toast, id) => {
                this.remove(id);
            });
        }
    }

    // Initialize toaster
    const toaster = new Toaster();

    // Make toaster globally available
    window.toaster = toaster;

    // Add test function for debugging
    window.testToaster = function() {
        toaster.show('Test success message!', 'success', 'نجح', 3000);
        setTimeout(() => toaster.show('Test error message!', 'error', 'خطأ', 3000), 500);
        setTimeout(() => toaster.show('Test warning message!', 'warning', 'تحذير', 3000), 1000);
        setTimeout(() => toaster.show('Test info message!', 'info', 'معلومة', 3000), 1500);
    };

    // Livewire event listeners
    document.addEventListener('livewire:init', () => {
        Livewire.on('showToast', (event) => {
            console.log('Toast event received:', event); // Debug log

            // Extract data from event
            const message = event.message || event[0]?.message || 'Message not provided';
            const type = event.type || event[0]?.type || 'success';
            const title = event.title || event[0]?.title || null;
            const duration = event.duration || event[0]?.duration || 4000;

            console.log('Toast data:', {
                message,
                type,
                title,
                duration
            }); // Debug log

            toaster.show(message, type, title, duration);
        });
    });
</script>
