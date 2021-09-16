<div class="hstack small justify-content-between">
    <x-bs::dropdown>
        <x-bs::dropdown.button class="shadow-none ps-0 text-light" id="languages-menu">
            @if(app()->getLocale() === 'el')
                <img class="me-2" src="{{ asset('storage/images/flags/Greece.png') }}" alt="Flag of Greece" width="16" height="16">
                <small>Ελληνικά</small>
            @else
                <img class="me-2" src="{{ asset('storage/images/flags/UnitedKingdom.png') }}" alt="Flag of United Kingdom" width="16" height="16">
                <small>English</small>
            @endif
        </x-bs::dropdown.button>

        <x-bs::dropdown.menu class="shadow-sm" button="languages-menu">
            <x-bs::dropdown.item href="{{ route('home', 'el') }}">
                <img class="me-2" src="{{ asset('storage/images/flags/Greece.png') }}" alt="Flag of Greece" width="16" height="16">Ελληνικά
            </x-bs::dropdown.item>

            <x-bs::dropdown.item href="{{ route('home', 'en') }}">
                <img class="me-2" src="{{ asset('storage/images/flags/UnitedKingdom.png') }}" alt="Flag of United Kingdom" width="16" height="16">English
            </x-bs::dropdown.item>
        </x-bs::dropdown.menu>
    </x-bs::dropdown>

    @isset(__("company.phone")[0])
        @php $phone = __("company.phone")[0] @endphp
        <a href="tel:{{ $phone }}" class="d-none d-sm-inline-block lh-sm text-decoration-none text-light text-nowrap"><em class="fas fa-phone"></em> {{ $phone }}</a>
    @endisset

    <div class="d-flex">
        @guest
            @if(routeHas('register'))
                <a href="{{ route('register', app()->getLocale()) }}" class="px-3 py-2 text-decoration-none border-end text-light"><em class="fa fa-user-plus"></em> {{ __("Register") }}</a>
            @endif

            @if(routeHas('login'))
                <a href="{{ route('login', app()->getLocale()) }}" class="px-3 py-2 text-decoration-none border-end text-light"><em class="fa fa-lock"></em> {{ __("Login") }}</a>
            @endif
        @else
            <x-bs::dropdown>
                <x-bs::dropdown.button class="text-light shadow-none pe-0" id="user-menu">
                    <em class="text-light fas fa-user me-2"></em>
                    <small>{{ __('Account') }}</small>
                </x-bs::dropdown.button>

                <x-bs::dropdown.menu class="shadow-sm" button="user-menu" alignment="right" style="z-index: 1500">
                    @can('View dashboard')
                        <x-bs::dropdown.item target="_blank" href="{{ route('products.index') }}">Διαχείριση</x-bs::dropdown.item>
                        <x-bs::dropdown.divider/>
                    @endcan

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