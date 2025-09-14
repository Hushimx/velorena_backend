<div class="cart-indicator">
    <a href="{{ route('cart.index') }}" class="cart-link">
        <div class="cart-icon">
            <i class="fas fa-shopping-cart"></i>
            @if($itemCount > 0)
                <span class="cart-badge">{{ $itemCount }}</span>
            @endif
        </div>
        <div class="cart-info">
            <span class="cart-text">السلة</span>
            @if($itemCount > 0)
                <span class="cart-price">{{ number_format($totalPrice, 2) }} ريال</span>
            @endif
        </div>
    </a>

    <style>
    .cart-indicator {
        display: flex;
        align-items: center;
    }

    .cart-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        color: inherit;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
    }

    .cart-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: inherit;
        text-decoration: none;
    }

    .cart-icon {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
        border-radius: 50%;
        border: 2px solid rgba(139, 69, 19, 0.2);
    }

    .cart-icon i {
        font-size: 1.2rem;
        color: #8B4513;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
        border: 2px solid white;
    }

    .cart-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .cart-text {
        font-weight: 600;
        color: #8B4513;
        font-size: 0.9rem;
    }

    .cart-price {
        font-size: 0.8rem;
        color: #28a745;
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cart-link {
            padding: 0.25rem 0.5rem;
            gap: 0.25rem;
        }

        .cart-icon {
            width: 35px;
            height: 35px;
        }

        .cart-icon i {
            font-size: 1rem;
        }

        .cart-badge {
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
        }

        .cart-text {
            font-size: 0.8rem;
        }

        .cart-price {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .cart-info {
            display: none;
        }
    }
    </style>
</div>
