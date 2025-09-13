@props(['type' => 'message'])

@php
    $sessionKey = $type === 'error' ? 'error' : 'message';
    $iconClass = $type === 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle';
    $direction = app()->getLocale() === 'ar' ? 'rtl' : 'ltr';
@endphp

@if (session()->has($sessionKey))
    <div class="session-message {{ $type }}-message" style="direction: {{ $direction }};">
        <i class="fas {{ $iconClass }}"></i>
        <span>{{ session($sessionKey) }}</span>

        @if ($slot->isNotEmpty())
            <div class="message-actions">
                {{ $slot }}
            </div>
        @endif
    </div>
@endif

<style>
    /* Session Message Component Styles */
    .session-message {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        font-family: 'Cairo', sans-serif;
    }

    .session-message.message-message {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .session-message.error-message {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .session-message i {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .session-message.message-message i {
        color: #28a745;
    }

    .session-message.error-message i {
        color: #dc3545;
    }

    .session-message span {
        flex: 1;
        font-weight: 600;
    }

    .message-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: auto;
    }

    .message-actions button {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: inherit;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .message-actions button:hover {
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .session-message {
            padding: 0.875rem 1rem;
            gap: 0.5rem;
        }

        .message-actions {
            flex-direction: column;
            gap: 0.25rem;
        }

        .message-actions button {
            width: 100%;
            justify-content: center;
        }
    }
</style>
