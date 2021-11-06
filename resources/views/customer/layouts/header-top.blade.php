<div class="hstack small justify-content-between">
    <x-bs::dropdown>
        <x-bs::dropdown.button class="shadow-none ps-0" id="languages-menu">
            @if(app()->getLocale() === 'el')
                <img class="me-2" src="{{ asset('storage/images/flags/Greece.png') }}" alt="Flag of Greece" width="16" height="16">
                <small>Ελληνικά</small>
            @else
                <img class="me-2" src="{{ asset('storage/images/flags/UnitedKingdom.png') }}" alt="Flag of United Kingdom" width="16" height="16">
                <small>English</small>
            @endif
        </x-bs::dropdown.button>

        <x-bs::dropdown.menu class="shadow-sm" button="languages-menu">
            @if(Route::currentRouteName() === 'landing_page')
                <x-bs::dropdown.item href="{{ url()->current() . '/el' }}">
                    <img class="me-2" src="{{ asset('storage/images/flags/Greece.png') }}" alt="Flag of Greece" width="16" height="16">Ελληνικά
                </x-bs::dropdown.item>

                <x-bs::dropdown.item href="{{ url()->current() . '/en' }}">
                    <img class="me-2" src="{{ asset('storage/images/flags/UnitedKingdom.png') }}" alt="Flag of United Kingdom" width="16" height="16">English
                </x-bs::dropdown.item>
            @else
                <x-bs::dropdown.item href="{{ str_replace('/' . app()->getLocale(), '/el', url()->current()) }}">
                    <img class="me-2" src="{{ asset('storage/images/flags/Greece.png') }}" alt="Flag of Greece" width="16" height="16">Ελληνικά
                </x-bs::dropdown.item>

                <x-bs::dropdown.item href="{{ str_replace('/' . app()->getLocale(), '/en', url()->current()) }}">
                    <img class="me-2" src="{{ asset('storage/images/flags/UnitedKingdom.png') }}" alt="Flag of United Kingdom" width="16" height="16">English
                </x-bs::dropdown.item>
            @endif
        </x-bs::dropdown.menu>
    </x-bs::dropdown>

    @if($phone = __("company.phone")[0])
        <a href="tel:{{ telephone($phone) }}" class="d-none d-sm-inline-block lh-sm text-decoration-none text-nowrap"><em class="fas fa-phone"></em> {{ $phone }}</a>
    @endif

    <div class="d-flex gap-4 gap-sm-3">
        @guest
            @if(routeHas('register'))
                <a href="{{ route('register', app()->getLocale()) }}" class="py-2 text-decoration-none"><em class="fa fa-user-plus"></em> <span class="d-none d-sm-inline">{{ __("Register") }}</span></a>
            @endif

            @if(routeHas('login'))
                <a href="{{ route('login', app()->getLocale()) }}" class="py-2 text-decoration-none"><em class="fa fa-lock"></em> <span class="d-none d-sm-inline">{{ __("Login") }}</span></a>
            @endif
        @else
            <x-bs::dropdown>
                <x-bs::dropdown.button class="shadow-none pe-0" id="user-menu">
                    <em class="fas fa-user me-2"></em>
                    <small>{{ __('Account') }}</small>
                </x-bs::dropdown.button>

                <x-bs::dropdown.menu class="shadow-sm" button="user-menu" alignment="right" style="z-index: 1500">
                    @can('View dashboard')
                        <x-bs::dropdown.item target="_blank" href="{{ route('products.index') }}">Διαχείριση</x-bs::dropdown.item>
                        @can('Edit configuration')
                            <x-bs::dropdown.item href="#theme-form" data-bs-toggle="modal">Θέμα</x-bs::dropdown.item>
                        @endcan
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

@can('View dashboard')
    <div id="theme-form" class="modal" tabindex="-1">
        <form action="/dashboard/theme" method="post">
            @csrf
            @method('put')
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="exampleColorInput" class="form-label">Color picker</label>
                        <input type="color" class="form-control form-control-color" id="exampleColorInput" value="#563d7c" title="Choose your color">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endcan