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
                        href="{{ route('home') }}">{{ __('Home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.products.*') ? 'active' : '' }}"
                        href="{{ route('user.products.index') }}">{{ __('Products') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.orders.*') ? 'active' : '' }}"
                        href="{{ route('user.orders.index') }}">{{ __('Orders') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}"
                        href="{{ route('cart.index') }}">{{ trans('cart.cart') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}"
                        href="{{ route('appointments.index') }}">{{ trans('Appointments') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ __('AI Design') }}</a>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item mx-3">
                    <x-language-dropdown />
                </li>
                <li class="nav-item">
                    <a class="btn btn-dark" type="submit">
                        <span class="ms-2">{{ __('Book with Designer') }}</span>
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
