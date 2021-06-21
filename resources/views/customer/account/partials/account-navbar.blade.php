<x-bs::navbar class="bg-white border-bottom" :fluid="false">
    <x-bs::navbar.collapse>
        <x-bs::navbar.nav>
            <x-bs::navbar.dropdown class="ps-0" label="{{ __('Profile') }}" id="settings">
                <x-bs::dropdown.menu button="settings">
                    <x-bs::dropdown.item href="{{ route('account.profile.edit', app()->getLocale()) }}">{{ __('Edit profile') }}</x-bs::dropdown.item>
{{--                    <x-bs::dropdown.item>{{ __('Change email') }}</x-bs::dropdown.item>--}}
                    <x-bs::dropdown.item href="{{ route('account.password.edit', app()->getLocale()) }}">{{ __('Change password') }}</x-bs::dropdown.item>
                </x-bs::dropdown.menu>
            </x-bs::navbar.dropdown>

            <x-bs::navbar.link href="{{ route('account.orders.index', app()->getLocale()) }}" class="px-4">{{ __('My orders') }}</x-bs::navbar.link>
            <x-bs::navbar.link href="{{ route('account.addresses.index', app()->getLocale()) }}" class="px-4">{{ __('My addresses') }}</x-bs::navbar.link>
{{--            <x-bs::navbar.link href="{{ route('account.invoices.index', app()->getLocale()) }}" class="px-4">{{ __('Invoices') }}</x-bs::navbar.link>--}}
            <x-bs::navbar.link href="{{ route('account.companies.index', app()->getLocale()) }}" class="px-4">{{ __('My companies') }}</x-bs::navbar.link>
        </x-bs::navbar.nav>
    </x-bs::navbar.collapse>
</x-bs::navbar>