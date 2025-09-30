<div class="cart-indicator">
    <a href="{{ route('cart.index') }}" class="cart-link" title="{{ trans('Shopping Cart') }}">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-badge" data-count="{{ $itemCount }}" style="{{ $itemCount == 0 ? 'display: none;' : '' }}">{{ $itemCount }}</span>
    </a>

    <style>
    .cart-indicator {
        display: flex;
        align-items: center;
    }

    .cart-link {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        text-decoration: none;
        color: #6c757d;
        border-radius: 50%;
        transition: all 0.3s ease;
        background: transparent;
    }

    .cart-link:hover {
        background: rgba(0, 0, 0, 0.1);
        color: #495057;
        text-decoration: none;
    }

    .cart-link i {
        font-size: 1.2rem;
    }

    .cart-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
        border: 2px solid white;
        padding: 0 4px;
    }


    /* Responsive */
    @media (max-width: 768px) {
        .cart-link {
            width: 35px;
            height: 35px;
        }

        .cart-link i {
            font-size: 1rem;
        }

        .cart-badge {
            min-width: 16px;
            height: 16px;
            font-size: 0.65rem;
        }
    }
    </style>
</div>
