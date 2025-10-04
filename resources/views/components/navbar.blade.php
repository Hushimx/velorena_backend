<!-- Multi-row Navigation Structure -->
<nav class="multi-row-navbar">
    <!-- First Row: Language and Additional Info -->
    <div class="navbar-top-row">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-2">
                <div class="top-nav-left">
                    <small class="text-muted me-3">
                        <i class="fas fa-phone me-1"></i>
                        {{ trans('Follow up requests') }}
                    </small>
                    <small class="text-muted">
                        <i class="fas fa-envelope me-1"></i>
                        {{ trans('Contact us') }}
                    </small>
                </div>
                <div class="top-nav-right">
                    <x-language-dropdown />
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row: Logo, Search, Cart, User -->
    <div class="navbar-main-row">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between py-3">
                <!-- Logo -->
                <div class="navbar-brand-container">
                    <a class="navbar-brand" href="{{ route('welcome') }}">
                        <img class="navbar-brand-logo" src="{{ asset('assets/imgs/logo.png') }}" alt="Logo">
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="navbar-search-container flex-grow-1 mx-4">
                    <form action="{{ route('user.products.index') }}" method="GET" class="search-wrapper position-relative">
                        <input type="text" 
                               class="form-control search-input" 
                               placeholder="{{ trans('Search products...') }}"
                               id="navbar-search"
                               name="search"
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <button class="search-btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <!-- Search Results Dropdown -->
                        <div class="search-results-dropdown" id="search-results" style="display: none;">
                            <div class="search-results-content">
                                <!-- Results will be populated via JavaScript -->
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Right Side: Cart and User -->
                <div class="navbar-actions d-flex align-items-center">
                    <!-- Cart with Hover -->
                    <div class="cart-container me-3" id="cart-container">
                        @livewire('cart-indicator')
                        <!-- Cart Hover Preview -->
                        <div class="cart-preview-dropdown" id="cart-preview">
                            <div class="cart-preview-content">
                                <!-- Cart preview will be populated via JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- User Account -->
                    <div class="user-account-container">
                        @guest
                            <!-- Guest User Account Section -->
                            <div class="dropdown">
                                <a class="user-toggle guest-login-toggle d-flex align-items-center" 
                                   href="#" 
                                   role="button" 
                                   data-bs-toggle="dropdown" 
                                   aria-expanded="false"
                                   aria-haspopup="true"
                                   aria-label="{{ trans('Guest Account Menu') }}"
                                   id="guestDropdownToggle">
                                    <!-- Guest Avatar -->
                                    <div class="user-avatar me-2">
                                        <div class="avatar-placeholder guest-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <!-- Guest Info -->
                                    <div class="user-info d-none d-md-block">
                                        <div class="user-name guest-text">{{ trans('Guest') }}</div>
                                        <div class="user-role text-muted small">{{ trans('Login to continue') }}</div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end guest-dropdown" 
                                    aria-labelledby="guestDropdownToggle"
                                    data-bs-popper="static">
                                    <!-- Guest Header -->
                                    <li class="dropdown-header guest-profile-header">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-large me-3">
                                                <div class="avatar-placeholder-large guest-avatar-large">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <div class="user-details">
                                                <div class="user-name-large guest-text-large">{{ trans('Guest User') }}</div>
                                                <div class="user-email guest-email">{{ trans('Login to access your account') }}</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <!-- Login Actions -->
                                    <li class="dropdown-item-section">
                                        <h6 class="dropdown-section-title">{{ trans('Account Actions') }}</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-enhanced login-action" href="{{ route('login') }}">
                                            <div class="dropdown-item-content">
                                                <i class="fas fa-sign-in-alt dropdown-item-icon"></i>
                                                <div class="dropdown-item-text">
                                                    <div class="dropdown-item-title">{{ trans('Login') }}</div>
                                                    <div class="dropdown-item-subtitle">{{ trans('Access your account') }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-enhanced register-action" href="{{ route('register') }}">
                                            <div class="dropdown-item-content">
                                                <i class="fas fa-user-plus dropdown-item-icon"></i>
                                                <div class="dropdown-item-text">
                                                    <div class="dropdown-item-title">{{ trans('Register') }}</div>
                                                    <div class="dropdown-item-subtitle">{{ trans('Create new account') }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                        
                        @auth
                            <!-- Enhanced User Dropdown for Logged-in Users -->
                            <div class="dropdown">
                                <a class="user-toggle client-area-toggle d-flex align-items-center" 
                                   href="#" 
                                   role="button" 
                                   data-bs-toggle="dropdown" 
                                   aria-expanded="false"
                                   aria-haspopup="true"
                                   aria-label="{{ trans('User Account Menu') }}"
                                   id="userDropdownToggle">
                                    <!-- User Avatar -->
                                    <div class="user-avatar me-2">
                                        @if(Auth::user()->profile_photo_path)
                                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                                 alt="{{ Auth::user()->full_name ?? Auth::user()->company_name ?? 'User' }}"
                                                 class="avatar-img">
                                        @else
                                            <div class="avatar-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- User Info -->
                                    <div class="user-info d-none d-md-block">
                                        <div class="user-name">{{ Auth::user()->full_name ?? Auth::user()->company_name ?? 'حسابي' }}</div>
                                        <div class="user-role text-muted small">{{ trans('Client') }}</div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end client-area-dropdown" 
                                    aria-labelledby="userDropdownToggle"
                                    data-bs-popper="static">
                                    <!-- User Profile Header -->
                                    <li class="dropdown-header user-profile-header">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-large me-3">
                                                @if(Auth::user()->profile_photo_path)
                                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                                         alt="{{ Auth::user()->full_name ?? Auth::user()->company_name ?? 'User' }}"
                                                         class="avatar-img-large">
                                                @else
                                                    <div class="avatar-placeholder-large">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="user-details">
                                                <div class="user-name-large">{{ Auth::user()->full_name ?? Auth::user()->company_name ?? 'حسابي' }}</div>
                                                <div class="user-email">{{ Auth::user()->email }}</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <!-- Quick Actions -->
                                    <li class="dropdown-item-section">
                                        <h6 class="dropdown-section-title">{{ trans('Quick Actions') }}</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-enhanced" href="{{ route('client.index') }}">
                                            <div class="dropdown-item-content">
                                                <i class="fas fa-tachometer-alt dropdown-item-icon"></i>
                                                <div class="dropdown-item-text">
                                                    <div class="dropdown-item-title">{{ trans('Client Area') }}</div>
                                                    <div class="dropdown-item-subtitle">{{ trans('Dashboard & Overview') }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-enhanced" href="{{ route('user.orders.index') }}">
                                            <div class="dropdown-item-content">
                                                <i class="fas fa-clipboard-list dropdown-item-icon"></i>
                                                <div class="dropdown-item-text">
                                                    <div class="dropdown-item-title">{{ trans('My Orders') }}</div>
                                                    <div class="dropdown-item-subtitle">{{ trans('Track & Manage Orders') }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-enhanced" href="{{ route('appointments.index') }}">
                                            <div class="dropdown-item-content">
                                                <i class="fas fa-calendar-alt dropdown-item-icon"></i>
                                                <div class="dropdown-item-text">
                                                    <div class="dropdown-item-title">{{ trans('My Appointments') }}</div>
                                                    <div class="dropdown-item-subtitle">{{ trans('Schedule & View Appointments') }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <!-- Account Settings -->
                                    <li class="dropdown-item-section">
                                        <h6 class="dropdown-section-title">{{ trans('Account') }}</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-enhanced" href="{{ route('client.index') }}">
                                            <div class="dropdown-item-content">
                                                <i class="fas fa-user-cog dropdown-item-icon"></i>
                                                <div class="dropdown-item-text">
                                                    <div class="dropdown-item-title">{{ trans('Account Settings') }}</div>
                                                    <div class="dropdown-item-subtitle">{{ trans('Manage Your Account') }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <!-- Logout -->
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                            @csrf
                                            <button type="submit" class="dropdown-item logout-btn-enhanced w-100">
                                                <div class="dropdown-item-content">
                                                    <i class="fas fa-sign-out-alt dropdown-item-icon"></i>
                                                    <div class="dropdown-item-text">
                                                        <div class="dropdown-item-title">{{ trans('Logout') }}</div>
                                                        <div class="dropdown-item-subtitle">{{ trans('Sign out of your account') }}</div>
                                                    </div>
                                                </div>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

</nav>

<style>
/* Brand Colors Import */
:root {
    --brand-yellow: #ffde9f;
    --brand-yellow-dark: #f5d182;
    --brand-brown: #2a1e1e;
    --brand-brown-light: #3a2e2e;
    --brand-yellow-light: #fff4e6;
    --brand-yellow-hover: #f0d4a0;
    --brand-brown-dark: #1a1414;
    --brand-brown-hover: #4a3e3e;
}

/* Multi-row Navbar Styles */
.multi-row-navbar {
    background: linear-gradient(180deg, var(--brand-yellow-light) 0%, #FFFFFF 100%);
    box-shadow: 0 4px 8px rgba(42, 30, 30, 0.15);
    position: sticky;
    top: 0;
    z-index: 1000;
    font-family: 'Cairo', sans-serif;
}

/* Top Row Styles */
.navbar-top-row {
    background: linear-gradient(90deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
    border-bottom: 1px solid var(--brand-yellow-dark);
    font-size: 0.9rem;
    font-weight: 500;
}

.top-nav-left small {
    color: var(--brand-brown) !important;
    font-weight: 600;
}

.top-nav-left i {
    color: var(--brand-brown-light);
    margin-left: 8px;
    font-size: 1.1em;
}

/* Main Row Styles */
.navbar-main-row {
    padding: 1rem 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
}

.navbar-brand-container {
    flex-shrink: 0;
}

.navbar-brand-logo {
    height: 80px;
    width: auto;
    filter: drop-shadow(0 2px 4px rgba(42, 30, 30, 0.1));
    transition: transform 0.3s ease;
}

.navbar-brand-logo:hover {
    transform: scale(1.05);
}

/* Search Bar Styles */
.navbar-search-container {
    max-width: 600px;
}

.search-wrapper {
    position: relative;
}

.search-input {
    border-radius: 25px;
    padding: 12px 50px 12px 20px;
    border: 2px solid #e9ecef;
    font-size: 0.95rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.search-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.search-input:hover {
    border-color: #007bff;
}

.search-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: none;
}



/* Search Results Dropdown */
.search-results-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-height: 400px;
    overflow-y: auto;
    z-index: 1050;
}

.search-result-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f8f9fa;
    cursor: pointer;
    transition: background-color 0.2s ease;
    display: flex;
    align-items: center;
}

.search-result-item:hover {
    background-color: #f8f9fa;
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-image {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 12px;
}

.search-result-details h6 {
    margin: 0;
    font-size: 0.9rem;
    color: #333;
}

.search-result-details .text-muted {
    font-size: 0.8rem;
}

/* Optimized Cart Container Styles */
.cart-container {
    position: relative;
}

.cart-container .cart-indicator {
    transition: transform 0.2s ease;
}

.cart-container:hover .cart-indicator {
    transform: scale(1.05);
}

.cart-preview-dropdown {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    width: 380px;
    background: white;
    border: 2px solid var(--brand-yellow-dark);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(42, 30, 30, 0.2);
    z-index: 1050;
    backdrop-filter: blur(10px);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.cart-preview-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.cart-preview-dropdown::before {
    content: '';
    position: absolute;
    top: -8px;
    right: 20px;
    width: 16px;
    height: 16px;
    background: white;
    border: 2px solid var(--brand-yellow-dark);
    border-bottom: none;
    border-right: none;
    transform: rotate(45deg);
}

.cart-preview-content {
    padding: 20px;
    max-height: 450px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--brand-yellow-dark) transparent;
}

.cart-preview-content::-webkit-scrollbar {
    width: 6px;
}

.cart-preview-content::-webkit-scrollbar-track {
    background: transparent;
}

.cart-preview-content::-webkit-scrollbar-thumb {
    background: var(--brand-yellow-dark);
    border-radius: 3px;
}

.cart-preview-header {
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--brand-yellow-light);
    color: var(--brand-brown);
    text-align: center;
}

.cart-preview-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid var(--brand-yellow-light);
    transition: all 0.2s ease;
}

.cart-preview-item:hover {
    background: var(--brand-yellow-light);
    margin: 0 -20px;
    padding: 12px 20px;
    border-radius: 8px;
}

.cart-preview-item:last-child {
    border-bottom: none;
}

.cart-preview-item-image {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 16px;
    border: 2px solid var(--brand-yellow-light);
    transition: transform 0.2s ease;
}

.cart-preview-item:hover .cart-preview-item-image {
    transform: scale(1.1);
    border-color: var(--brand-yellow);
}

.cart-preview-item-details {
    flex-grow: 1;
}

.cart-preview-item-name {
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0 0 4px 0;
    color: var(--brand-brown);
    line-height: 1.3;
}

.cart-preview-item-price {
    font-size: 0.8rem;
    color: var(--brand-brown-light);
    font-weight: 500;
}

.cart-preview-footer {
    padding-top: 16px;
    border-top: 2px solid var(--brand-yellow-light);
    margin-top: 16px;
}

.cart-preview-total {
    display: flex;
    justify-content: space-between;
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 16px;
    color: var(--brand-brown);
}

.cart-preview-actions {
    display: flex;
    gap: 12px;
}

.cart-preview-btn {
    flex: 1;
    padding: 12px 16px;
    border: none;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.cart-preview-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.5s;
}

.cart-preview-btn:hover::before {
    left: 100%;
}

.cart-preview-btn-outline {
    background: white;
    border: 2px solid var(--brand-yellow-dark);
    color: var(--brand-brown);
}

.cart-preview-btn-outline:hover {
    background: var(--brand-yellow-light);
    color: var(--brand-brown);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 222, 159, 0.4);
}

.cart-preview-btn-primary {
    background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
    color: var(--brand-brown);
    border: 2px solid var(--brand-yellow-dark);
}

.cart-preview-btn-primary:hover {
    background: linear-gradient(135deg, var(--brand-yellow-hover) 0%, var(--brand-yellow) 100%);
    color: var(--brand-brown);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255, 222, 159, 0.5);
}

.cart-empty {
    text-align: center;
    padding: 40px 20px;
    color: var(--brand-brown-light);
}

.cart-empty i {
    font-size: 3rem;
    color: var(--brand-yellow-dark);
    margin-bottom: 16px;
    display: block;
}

.cart-empty h6 {
    color: var(--brand-brown);
    font-weight: 600;
    margin-bottom: 8px;
}

.cart-empty p {
    color: var(--brand-brown-light);
    font-size: 0.9rem;
}

/* Enhanced User Account Styles */
.user-toggle {
    text-decoration: none;
    color: var(--brand-brown);
    padding: 10px 16px;
    border-radius: 25px;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid var(--brand-yellow-light);
    backdrop-filter: blur(10px);
}

.user-toggle:hover {
    background: var(--brand-yellow-light);
    text-decoration: none;
    color: var(--brand-brown);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 222, 159, 0.3);
    border-color: var(--brand-yellow);
}

.user-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid var(--brand-yellow);
    transition: all 0.3s ease;
}

.user-toggle:hover .user-avatar {
    border-color: var(--brand-yellow-dark);
    transform: scale(1.1);
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--brand-brown);
    font-size: 1.2rem;
}

.user-name {
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1.2;
    color: var(--brand-brown);
}

.user-role {
    font-size: 0.8rem;
    line-height: 1;
    color: var(--brand-brown-light);
    font-weight: 500;
}


/* Enhanced Mobile Responsive Design */
@media (max-width: 1199.98px) {
    .navbar-search-container {
        max-width: 500px;
    }
}

@media (max-width: 991.98px) {
    .navbar-top-row {
        display: none;
    }
    
    .navbar-main-row {
        padding: 0.75rem 0;
    }
    
    .navbar-main-row .d-flex {
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .navbar-brand-container {
        order: 1;
        flex: 0 0 auto;
    }
    
    .navbar-actions {
        order: 2;
        flex: 0 0 auto;
    }
    
    .navbar-search-container {
        order: 3;
        width: 100%;
        max-width: none;
        margin: 0;
    }
    
    .user-info {
        display: none !important;
    }
    
    .cart-preview-dropdown {
        width: 320px;
        right: -50px;
    }
}

@media (max-width: 767.98px) {
    .multi-row-navbar {
        box-shadow: 0 2px 12px rgba(42, 30, 30, 0.2);
    }
    
    .navbar-brand-logo {
        height: 60px;
    }
    
    .search-wrapper {
        border-radius: 25px;
    }
    
    .search-input {
        padding: 14px 50px 14px 20px;
        font-size: 0.95rem;
        border-radius: 25px;
    }
    
    .search-btn {
        width: 38px;
        height: 38px;
        right: 4px;
        font-size: 1rem;
    }
    
    .user-avatar {
        width: 36px;
        height: 36px;
        border-width: 2px;
    }
    
    .user-toggle {
        padding: 8px 12px;
    }
    
    .cart-preview-dropdown {
        width: 280px;
        right: -20px;
    }
    
    .cart-preview-content {
        padding: 16px;
    }
    
    .search-results-dropdown {
        border-radius: 16px;
        max-height: 300px;
    }
    
    .search-result-item {
        padding: 12px 16px;
    }
    
    .search-result-image {
        width: 40px;
        height: 40px;
        margin-right: 12px;
    }
}

@media (max-width: 575.98px) {
    .navbar-brand-logo {
        height: 50px;
    }
    
    .search-input {
        padding: 12px 45px 12px 16px;
        font-size: 0.9rem;
    }
    
    .search-btn {
        width: 34px;
        height: 34px;
        font-size: 0.9rem;
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
    }
    
    .user-toggle {
        padding: 6px 10px;
    }
    
    .cart-preview-dropdown {
        width: 260px;
        right: -10px;
    }
    
    .navbar-main-row {
        padding: 0.5rem 0;
    }
    
    .cart-preview-actions {
        flex-direction: column;
        gap: 8px;
    }
    
    .cart-preview-btn {
        font-size: 0.85rem;
        padding: 10px 14px;
    }
    
    .search-result-details h6 {
        font-size: 0.9rem;
    }
    
    .search-result-details .text-muted {
        font-size: 0.8rem;
    }
}

/* Performance Optimizations */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Touch Optimization */
@media (hover: none) and (pointer: coarse) {
    .cart-container:hover .cart-indicator,
    .user-toggle:hover {
        transform: none;
    }
    
    .cart-preview-dropdown,
    .search-results-dropdown {
        touch-action: pan-y;
    }
}

/* Arabic RTL Support */
[dir="rtl"] .search-input {
    padding: 12px 20px 12px 50px;
}

[dir="rtl"] .search-btn {
    right: auto;
    left: 8px;
}

[dir="rtl"] .cart-preview-dropdown {
    right: auto;
    left: 0;
}

[dir="rtl"] .search-result-image,
[dir="rtl"] .cart-preview-item-image {
    margin-right: 0;
    margin-left: 12px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('navbar-search');
    const searchResults = document.getElementById('search-results');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            } else {
                hideSearchResults();
            }
        });

        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                hideSearchResults();
            }
        });
    }

    // Cart hover functionality
    const cartContainer = document.getElementById('cart-container');
    const cartPreview = document.getElementById('cart-preview');
    let cartHoverTimeout;
    let quickPreviewShown = false;

    if (cartContainer && cartPreview) {
        // Show cart preview on hover
        cartContainer.addEventListener('mouseenter', function() {
            clearTimeout(cartHoverTimeout);
            
            // Quick check - if cart badge exists, show immediate preview
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                const itemCount = parseInt(cartBadge.textContent) || 0;
                console.log('🚀 Quick preview: Cart badge shows', itemCount, 'items');
                
                // Always show cart preview (empty or not)
                console.log('🚀 Showing cart preview for', itemCount, 'items');
                quickPreviewShown = true;
                // Load real data
                loadCartPreview();
                showCartPreview();
            } else {
                loadCartPreview();
            }
        });

        // Hide cart preview when leaving cart container
        cartContainer.addEventListener('mouseleave', function() {
            cartHoverTimeout = setTimeout(() => {
                hideCartPreview();
                quickPreviewShown = false; // Reset flag
            }, 300);
        });

        // Keep preview visible when hovering over it
        cartPreview.addEventListener('mouseenter', function() {
            clearTimeout(cartHoverTimeout);
        });

        // Hide preview when leaving the preview itself
        cartPreview.addEventListener('mouseleave', function() {
            hideCartPreview();
            quickPreviewShown = false; // Reset flag
        });
    }

    function performSearch(query) {
        fetch(`/api/products?search=${encodeURIComponent(query)}&limit=5`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.data) {
                    displaySearchResults(data.data.data);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
            });
    }

    function displaySearchResults(products) {
        const resultsContent = searchResults.querySelector('.search-results-content');
        
        if (products.length === 0) {
            resultsContent.innerHTML = '<div class="p-3 text-muted text-center">لا توجد نتائج</div>';
        } else {
            resultsContent.innerHTML = products.map(product => `
                <div class="search-result-item" onclick="window.location.href='/products/${product.id}'">
                    <img src="${product.image || '/assets/imgs/no-image.png'}" 
                         alt="${product.name}" 
                         class="search-result-image"
                         onerror="this.src='/assets/imgs/no-image.png'">
                    <div class="search-result-details">
                        <h6>${product.name_ar || product.name}</h6>
                        <div class="text-muted">${product.base_price} ر.س</div>
                    </div>
                </div>
            `).join('');
        }
        
        showSearchResults();
    }

    function showSearchResults() {
        searchResults.style.display = 'block';
    }

    function hideSearchResults() {
        searchResults.style.display = 'none';
    }

    // This function will be overridden by the newer implementation below

    function displayCartPreview(cartData) {
        console.log('Displaying cart preview with data:', cartData);
        const cartPreviewContent = cartPreview.querySelector('.cart-preview-content');
        
        if (!cartPreviewContent) {
            console.error('Cart preview content element not found');
            return;
        }
        
        if (!cartData.items || cartData.items.length === 0) {
            console.log('Showing empty cart');
            cartPreviewContent.innerHTML = `
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart"></i>
                    <h6>سلة التسوق فارغة</h6>
                    <p>ابدأ بإضافة منتجات إلى سلتك</p>
                </div>
            `;
        } else {
            console.log(`Showing cart with ${cartData.items.length} items`);
            cartPreviewContent.innerHTML = `
                <div class="cart-preview-header">
                    سلة التسوق (${cartData.item_count} منتج)
                </div>
                <div class="cart-preview-items">
                    ${cartData.items.slice(0, 3).map(item => `
                        <div class="cart-preview-item">
                            <img src="${item.product_image || '/assets/imgs/no-image.png'}" 
                                 alt="${item.product_name}" 
                                 class="cart-preview-item-image"
                                 onerror="this.src='/assets/imgs/no-image.png'"
                                 loading="lazy">
                            <div class="cart-preview-item-details">
                                <div class="cart-preview-item-name">${item.product_name}</div>
                                <div class="cart-preview-item-price">${item.quantity} × ${parseFloat(item.unit_price).toFixed(2)} ر.س</div>
                            </div>
                        </div>
                    `).join('')}
                    ${cartData.items.length > 3 ? `<div class="text-muted small text-center mt-2" style="color: var(--brand-brown-light); font-style: italic;">و ${cartData.items.length - 3} منتجات أخرى</div>` : ''}
                </div>
                <div class="cart-preview-footer">
                    <div class="cart-preview-total">
                        <span>الإجمالي</span>
                        <span style="color: var(--brand-brown); font-weight: 700;">${parseFloat(cartData.total_price).toFixed(2)} ر.س</span>
                    </div>
                    <div class="cart-preview-actions">
                        <a href="/cart" class="cart-preview-btn cart-preview-btn-outline">عرض السلة</a>
                        <a href="/checkout" class="cart-preview-btn cart-preview-btn-primary">إتمام الطلب</a>
                    </div>
                </div>
            `;
        }
    }

    function showCartPreview() {
        if (cartPreview) {
            cartPreview.classList.add('show');
        }
    }

    function hideCartPreview() {
        if (cartPreview) {
            cartPreview.classList.remove('show');
        }
    }

    // Performance optimization: Debounce cart hover
    let cartPreviewCache = null;
    let cacheTimeout = null;
    
    function loadCartPreview() {
        console.log('🛒 Loading cart preview...');
        console.log('🛒 Current URL:', window.location.href);
        
        // Check if cart indicator shows items
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            console.log('🛒 Cart badge shows:', cartBadge.textContent, 'items');
        } else {
            console.log('🛒 No cart badge found');
        }
        
        // Clear old cache timeout
        if (cacheTimeout) {
            clearTimeout(cacheTimeout);
        }
        
        // Add CSRF token to headers
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('🛒 CSRF token found:', !!csrfToken);
        
        // Try relative URL first
        const apiUrl = '/api/cart/preview';
        console.log('🛒 Fetching from:', apiUrl);
        
        fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
            .then(response => {
                console.log('🛒 Response status:', response.status);
                console.log('🛒 Response headers:', response.headers);
                
                if (!response.ok) {
                    console.log('🛒 Response not ok, trying to read as text...');
                    return response.text().then(text => {
                        console.log('🛒 Error response body:', text);
                        throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('🛒 Cart preview data received:', data);
                
                if (data.success) {
                    console.log('🛒 API success! Items count:', data.data?.item_count || 0);
                    console.log('🛒 Items array:', data.data?.items || []);
                    
                    const apiItemCount = data.data?.item_count || 0;
                    
                    // Always update with API data (empty or not)
                    console.log('🛒 Updating preview with API data');
                    
                    cartPreviewCache = {
                        data: data.data,
                        timestamp: Date.now()
                    };
                    
                    // Cache for 30 seconds
                    cacheTimeout = setTimeout(() => {
                        cartPreviewCache = null;
                    }, 30000);
                    
                    displayCartPreview(data.data);
                    showCartPreview();
                } else {
                    console.error('🛒 Cart preview API error:', data.error || data);
                    tryLivewireCartData();
                }
            })
            .catch(error => {
                console.error('🛒 Cart preview fetch error:', error);
                // Try to get cart data from Livewire component as fallback
                tryLivewireCartData();
            });
    }

    function tryLivewireCartData() {
        console.log('🔴 Trying to get cart data from Livewire...');
        
        // Check if there's a cart indicator component with data
        const cartIndicator = document.querySelector('[wire\\:id]');
        console.log('🔴 Cart indicator found:', !!cartIndicator);
        console.log('🔴 Livewire available:', !!window.Livewire);
        
        if (cartIndicator && window.Livewire) {
            try {
                console.log('🔴 Emitting getCartPreviewData event...');
                
                // Try to emit a Livewire event to get cart data
                window.Livewire.emit('getCartPreviewData');
                
                // Listen for cart data response
                window.Livewire.on('cartPreviewData', (data) => {
                    console.log('🔴 Received cart data from Livewire:', data);
                    displayCartPreview(data);
                    showCartPreview();
                });
                
                // No timeout fallback - let the quick preview or API handle it
                
            } catch (e) {
                console.error('🔴 Livewire fallback failed:', e);
                displayCartPreview({ items: [], item_count: 0, total_price: 0 });
                showCartPreview();
            }
        } else {
            console.log('🔴 No Livewire found, trying manual cart data extraction...');
            
            // Try to manually extract cart data from the page
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                const itemCount = parseInt(cartBadge.textContent) || 0;
                console.log('🔴 Manual extraction: found', itemCount, 'items');
                
                // Show empty cart instead of placeholder data
                displayCartPreview({ items: [], item_count: 0, total_price: 0 });
            } else {
                console.log('🔴 No cart badge found either, showing empty cart');
                displayCartPreview({ items: [], item_count: 0, total_price: 0 });
            }
            showCartPreview();
        }
    }

    // Note: Language dropdown is now handled by its own component

    // Add loading states
    function addLoadingState(element) {
        element.style.opacity = '0.7';
        element.style.pointerEvents = 'none';
    }

    function removeLoadingState(element) {
        element.style.opacity = '1';
        element.style.pointerEvents = 'auto';
    }

    // Preload critical images
    const criticalImages = [
        '/assets/imgs/logo.png',
        '/assets/imgs/no-image.png'
    ];

    criticalImages.forEach(src => {
        const img = new Image();
        img.src = src;
    });

    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
