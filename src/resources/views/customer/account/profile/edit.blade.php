@extends('layouts.master', ['title' =>  __('Profile')])

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
@endpush

@section('main')
    <div class="container-fluid bg-pink-500">
        <div class="container pt-4">
            <div class="row py-4">
                <div class="col fs-3 text-light">{{ user()->fullName }}</div>
            </div>
        </div>
    </div>

    <x-bs::navbar class="bg-white border-bottom" :fluid="false">
        <x-bs::navbar.collapse>
            <x-bs::navbar.nav>
                <x-bs::navbar.link class="ps-0">{{ __('Orders') }}</x-bs::navbar.link>
                <x-bs::navbar.link class="px-4">{{ __('Addresses') }}</x-bs::navbar.link>
                <x-bs::navbar.link class="px-4">{{ __('Invoices') }}</x-bs::navbar.link>
                <x-bs::navbar.link class="px-4">{{ __('Companies') }}</x-bs::navbar.link>

                <x-bs::navbar.dropdown class="px-4" label="{{ __('Profile') }}" id="settings">
                    <x-bs::dropdown.menu button="settings">
                        <x-bs::dropdown.item>{{ __('Update profile') }}</x-bs::dropdown.item>
                        <x-bs::dropdown.item>{{ __('Change email') }}</x-bs::dropdown.item>
                        <x-bs::dropdown.item>{{ __('Change password') }}</x-bs::dropdown.item>
                    </x-bs::dropdown.menu>
                </x-bs::navbar.dropdown>
            </x-bs::navbar.nav>
        </x-bs::navbar.collapse>
    </x-bs::navbar>
@endsection
