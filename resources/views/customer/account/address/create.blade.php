@extends('eshop::customer.layouts.master', ['title' =>  __('Add new address')])

@push('footer_scripts')
    @include('eshop::dashboard.layouts.toasts')
@endpush

@section('main')
    @include('eshop::customer.account.partials.account-navbar')

    <div class="container-fluid py-4">
        <div class="container">
            <h1 class="fs-4 fw-normal mb-4">{{ __("Add new address") }}</h1>

            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col">
                    <form x-data="{ submitting: false }" x-on:submit="submitting = true" method="post" action="{{ route('account.addresses.store', app()->getLocale()) }}">
                        @csrf

                        <div class="d-grid gap-3">
                            <x-bs::input.floating-label for="first-name" label="{{ __('Name') }}">
                                <x-bs::input.text name="first_name" :value="old('first_name')" error="first_name" id="first-name" placeholder="{{ __('Name') }}"/>
                            </x-bs::input.floating-label>

                            <x-bs::input.floating-label for="last-name" label="{{ __('Surname') }}">
                                <x-bs::input.text name="last_name" :value="old('last_name')" error="last_name" id="last-name" placeholder="{{ __('Surname') }}"/>
                            </x-bs::input.floating-label>

                            <div class="row">
                                <div class="col">
                                    <x-bs::input.floating-label for="street" label="{{ __('Street') }}">
                                        <x-bs::input.text name="street" :value="old('street')" error="street" id="street" placeholder="{{ __('Street') }}"/>
                                    </x-bs::input.floating-label>
                                </div>

                                <div class="col-4">
                                    <x-bs::input.floating-label for="street-no" label="{{ __('Street no') }}">
                                        <x-bs::input.text name="street_no" :value="old('street_no')" error="street_no" id="street-no" placeholder="{{ __('Street no') }}"/>
                                    </x-bs::input.floating-label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <x-bs::input.floating-label for="city" label="{{ __('City') }}">
                                        <x-bs::input.text name="city" :value="old('city')" error="city" id="city" placeholder="{{ __('City') }}"/>
                                    </x-bs::input.floating-label>
                                </div>

                                <div class="col-4">
                                    <x-bs::input.floating-label for="postcode" label="{{ __('Postcode') }}">
                                        <x-bs::input.text name="postcode" :value="old('postcode')" error="postcode" id="postcode" placeholder="{{ __('Postcode') }}"/>
                                    </x-bs::input.floating-label>
                                </div>
                            </div>

                            @livewire('account.user-address-country', [
                                'country_id' => old('country_id', ''),
                                'province' => old('province', '')
                            ])

                            <x-bs::input.floating-label for="floor" label="{{ __('Floor') }}">
                                <x-bs::input.text name="floor" :value="old('floor')" error="floor" id="floor" placeholder="{{ __('Floor') }}"/>
                            </x-bs::input.floating-label>

                            <x-bs::input.floating-label for="phone" label="{{ __('Phone') }}">
                                <x-bs::input.text name="phone" :value="old('phone')" error="phone" id="phone" placeholder="{{ __('phone') }}"/>
                            </x-bs::input.floating-label>

                            <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                                <div x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                {{ __("Save") }}
                            </x-bs::button.primary>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
