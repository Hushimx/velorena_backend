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
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        transform: translateX(100%);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid;
        font-family: 'Cairo', sans-serif;
        direction: rtl;
        position: relative;
        overflow: hidden;
    }

    .toast.show {
        transform: translateX(0);
        opacity: 1;
    }

    .toast.success {
        border-left-color: #10b981;
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    }

    .toast.error {
        border-left-color: #ef4444;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    }

    .toast.warning {
        border-left-color: #f59e0b;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }

    .toast.info {
        border-left-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
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
        background: #10b981;
        color: white;
    }

    .toast.error .toast-icon {
        background: #ef4444;
        color: white;
    }

    .toast.warning .toast-icon {
        background: #f59e0b;
        color: white;
    }

    .toast.info .toast-icon {
        background: #3b82f6;
        color: white;
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
        color: #1f2937;
        margin: 0;
    }

    .toast-message {
        font-weight: 500;
        font-size: 13px;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }

    .toast-close {
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        flex-shrink: 0;
    }

    .toast-close:hover {
        background: rgba(0, 0, 0, 0.05);
        color: #6b7280;
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
        background: #10b981;
    }

    .toast.error .toast-progress {
        background: #ef4444;
    }

    .toast.warning .toast-progress {
        background: #f59e0b;
    }

    .toast.info .toast-progress {
        background: #3b82f6;
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
            transform: translateY(0);
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

            // Trigger animation
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

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
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                    this.toasts.delete(id);
                }, 300);
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
