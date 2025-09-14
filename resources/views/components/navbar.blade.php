<nav class="navbar navbar-expand-lg bg-body-transparent">
    <div class="container">
        <a class="navbar-brand mx-3" href="{{ route('home') }}" style="width: 180px;">
            <img class="navbar-brand-logo" src="{{ asset('assets/imgs/logo.png') }}" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('home') }}">{{ trans('Home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.products.*') ? 'active' : '' }}"
                        href="{{ route('user.products.index') }}">{{ trans('Products') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.orders.*') ? 'active' : '' }}"
                        href="{{ route('user.orders.index') }}">{{ trans('Orders') }}</a>
                </li>
                <li class="nav-item">
                    @livewire('cart-indicator')
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}"
                        href="{{ route('appointments.index') }}">{{ trans('Appointments') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ trans('AI Design') }}</a>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item mx-3">
                    <x-language-dropdown />
                </li>
                
                @auth
                    <!-- Client Area Dropdown for Logged-in Users -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle client-area-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ Auth::user()->full_name ?? Auth::user()->company_name ?? 'حسابي' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end client-area-dropdown">
                            <li>
                                <h6 class="dropdown-header">{{ trans('حسابي') }}</h6>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('client.index') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>{{ trans('لوحة التحكم') }}
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('client.orders') }}">
                                <i class="fas fa-clipboard-list me-2"></i>{{ trans('طلباتي') }}
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('client.appointments') }}">
                                <i class="fas fa-calendar-alt me-2"></i>{{ trans('مواعيدي') }}
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-btn">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ trans('تسجيل الخروج') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-dark" type="submit">
                            <span class="ms-2">{{ trans('Book with Designer') }}</span>
                            <i class="fa fa-arrow-left"></i>
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
