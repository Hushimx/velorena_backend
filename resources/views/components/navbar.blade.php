<nav class="navbar navbar-expand-lg bg-body-transparent">
    <div class="container">
        <a class="navbar-brand mx-3" href="{{ route('welcome') }}" style="width: 180px;">
            <img class="navbar-brand-logo" src="{{ asset('assets/imgs/logo.png') }}" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('welcome') }}">{{ trans('Home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.products.*') ? 'active' : '' }}"
                        href="{{ route('user.products.index') }}">{{ trans('Products') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                        href="{{ route('about') }}">{{ trans('About Us') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('design.*') ? 'active' : '' }}"
                        href="{{ route('design.search') }}">{{ trans('Design Search') }}</a>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item mx-3">
                    <x-language-dropdown />
                </li>
                <li class="nav-item mx-2">
                    @livewire('cart-indicator')
                </li>
                
                @auth
                    <!-- Enhanced User Dropdown for Logged-in Users -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle client-area-toggle d-flex align-items-center" 
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
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
