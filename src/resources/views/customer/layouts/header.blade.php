<div class="container-fluid border-bottom bg-light">
    <div class="container">
        <div class="row small justify-content-between">
            <div class="col-auto">
                <div class="dropdown p-0">
                    <div class="px-3 py-2 d-flex align-items-center border-start border-end w-10r" role="button" id="languages-menu" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(app()->getLocale() === 'el')
                            <img class="me-2" src="{{ asset('storage/images/flags/Greece.png') }}" alt="Flag of Greece" width="16" height="16">
                            <span>Ελληνικά</span>
                            <em class="fa fa-chevron-down text-secondary small ms-3"></em>
                        @elseif(app()->getLocale() === 'en')
                            <img class="me-2" src="{{ asset('storage/images/flags/UnitedKingdom.png') }}" alt="Flag of United Kingdom" width="16" height="16">
                            <span>English</span>
                            <em class="fa fa-chevron-down text-secondary small ms-3"></em>
                        @endif
                    </div>
                    <ul class="dropdown-menu rounded-0 m-0 p-0 w-10r shadow-sm" aria-labelledby="languages-menu">
                        <li>
                            <a class="dropdown-item small d-flex align-items-center py-2" href="{{ route('home', 'el') }}">
                                <img class="me-2" src="{{ asset('storage/images/flags/Greece.png') }}" alt="Flag of Greece" width="16" height="16">Ελληνικά
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item small d-flex align-items-center py-2" href="{{ route('home', 'en') }}">
                                <img class="me-2" src="{{ asset('storage/images/flags/UnitedKingdom.png') }}" alt="Flag of United Kingdom" width="16" height="16">English
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-auto">
                <div class="d-flex">
                    @guest
                        @if(Route::has('register'))
                            <a href="{{ route('register', app()->getLocale()) }}" class="px-3 py-2 text-decoration-none border-end text-dark"><em class="fa fa-user-plus text-secondary"></em> {{ __("Register") }}</a>
                        @endif
                        @if(Route::has('login'))
                            <a href="{{ route('login', app()->getLocale()) }}" class="px-3 py-2 text-decoration-none border-end text-dark"><em class="fa fa-lock text-secondary"></em> {{ __("Login") }}</a>
                        @endif
                    @else
                        <x-bs::dropdown>
                            <x-bs::dropdown.button class="border-start border-end shadow-none" id="user-menu">
                                <em class="text-secondary fas fa-user me-2"></em>{{ __('Account') }}
                            </x-bs::dropdown.button>

                            <x-bs::dropdown.menu button="user-menu" alignment="right">
                                <x-bs::dropdown.item>{{ __('Orders') }}</x-bs::dropdown.item>
                                <x-bs::dropdown.item>{{ __('Wishlist') }}</x-bs::dropdown.item>
                                <x-bs::dropdown.item>{{ __('My addresses') }}</x-bs::dropdown.item>
                                <x-bs::dropdown.item>{{ __('Settings') }}</x-bs::dropdown.item>
                                <x-bs::dropdown.divider/>
                                <li class="p-0">
                                    <div class="dropdown-item p-0">
                                        <form action="{{ route('logout', app()->getLocale()) }}" method="POST" class="w-100">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 shadow-none bg-transparent text-start border-0 w-100" style="color: inherit">{{ __("Logout") }}</button>
                                        </form>
                                    </div>
                                </li>
                            </x-bs::dropdown.menu>
                        </x-bs::dropdown>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid bg-light pt-3">
    <div class="container">
        <div class="row align-items-center">
            <a href="{{ route('home', app()->getLocale()) }}" class="col-3">
                <img class="img-fluid" src="{{ config('eshop.logo') }}" alt="{{ config('app.name') }}" height="104" width="200">
            </a>

            <div class="col">
                <label class="d-none" for="search-bar">{{ __("Search") }}</label>
                <input id="search-bar" type="text" class="form-control rounded-pill" placeholder="{{ __("I'm looking for...")}}">
            </div>

            <div class="col-3 justify-content-center d-none d-md-flex">
                <em class="fa fa-mobile-alt fa-2x text-primary me-2"></em>
                <div class="small lh-sm">
                    <div class="fw-500">25413 03417</div>
                    <div class="fw-500">6939349473</div>
                </div>
            </div>

            <div class="col-auto d-flex justify-content-end">
                <livewire:customer.checkout.cart-button/>
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container">
        <a class="navbar-brand d-lg-none" href="#">{{ __("Menu") }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active h6 mb-0 ps-lg-0" aria-current="page" href="{{ route('home', app()->getLocale()) }}">{{ __("Home") }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active h6 mb-0 px-lg-3" href="{{ route('customer.categories.show', [app()->getLocale(), 'nimata-alize']) }}">{{ __("Yarns Alize") }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active h6 mb-0 px-lg-3" href="{{ route("customer.categories.show", [app()->getLocale(), 'ergaleia-plektikis']) }}">{{ __("Knitting tools") }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active h6 mb-0 px-lg-3" href="{{ route('customer.categories.show', [app()->getLocale(), 'nimata-gia-tsantes']) }}">{{ __("Yarns for bags") }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active h6 mb-0 px-lg-3" href="{{ route("customer.categories.show", [app()->getLocale(), 'ylika-gia-tsantes']) }}">{{ __("Bag accessories") }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active h6 mb-0 px-lg-3" href="{{ route('customer.categories.show', [app()->getLocale(), 'ylika-gia-kosmimata']) }}">{{ __("Jewelry accessories")}}</a>
                </li>
            </ul>
            <div class="d-flex">
                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill">{{ __("Track your order") }}</a>
            </div>
        </div>
    </div>
</nav>
