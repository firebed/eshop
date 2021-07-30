<div class="row small justify-content-between g-0">
    <div class="col-auto">
        <div class="dropdown p-0">
            <div class="px-3 py-2 d-flex align-items-center border-start border-end w-sm-10r" role="button" id="languages-menu" data-bs-toggle="dropdown" aria-expanded="false">
                @if(app()->getLocale() === 'el')
                    <img class="me-2" src="{{ asset('storage/images/flags/Greece.png') }}" alt="Flag of Greece" width="16" height="16">
                    <span class="d-none d-sm-inline-block">Ελληνικά</span>
                    <em class="fa fa-chevron-down text-secondary small ms-2"></em>
                @elseif(app()->getLocale() === 'en')
                    <img class="me-2" src="{{ asset('storage/images/flags/UnitedKingdom.png') }}" alt="Flag of United Kingdom" width="16" height="16">
                    <span class="d-none d-sm-inline-block">English</span>
                    <em class="fa fa-chevron-down text-secondary small ms-2"></em>
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
                @if(routeHas('register'))
                    <a href="{{ route('register', app()->getLocale()) }}" class="px-3 py-2 text-decoration-none border-end text-dark"><em class="fa fa-user-plus text-secondary"></em> {{ __("Register") }}</a>
                @endif

                @if(routeHas('login'))
                    <a href="{{ route('login', app()->getLocale()) }}" class="px-3 py-2 text-decoration-none border-end text-dark"><em class="fa fa-lock text-secondary"></em> {{ __("Login") }}</a>
                @endif
            @else
                <x-bs::dropdown>
                    <x-bs::dropdown.button class="border-start border-end shadow-none" id="user-menu">
                        <em class="text-secondary fas fa-user me-2"></em>
                        <small>{{ __('Account') }}</small>
                    </x-bs::dropdown.button>

                    <x-bs::dropdown.menu button="user-menu" alignment="right">
                        <x-bs::dropdown.item href="{{ route('account.profile.edit', app()->getLocale()) }}">{{ __('Profile') }}</x-bs::dropdown.item>
                        <x-bs::dropdown.item href="{{ route('account.orders.index', app()->getLocale()) }}">{{ __('My orders') }}</x-bs::dropdown.item>
                        {{--                                <x-bs::dropdown.item href="{{ route('account.wishlist.index') }}">{{ __('Wishlist') }}</x-bs::dropdown.item>--}}
                        <x-bs::dropdown.item href="{{ route('account.addresses.index', app()->getLocale()) }}">{{ __('My addresses') }}</x-bs::dropdown.item>
                        {{--                                <x-bs::dropdown.item href="{{ route('account.orders.index') }}">{{ __('Settings') }}</x-bs::dropdown.item>--}}
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