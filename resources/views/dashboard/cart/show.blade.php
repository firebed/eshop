@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fs-5 fw-500">{{ __("Orders") }}</div>
@endsection

@section('main')
    <div class="col-12 mx-auto p-3 px-xl-4">
        <div class="row g-4">
            <div class="col-12 col-xxl d-grid gap-3">

                @unless($cart->submitted_at)
                    <x-bs::alert type="danger" class="d-flex align-items-center">
                        <em class="fas fa-exclamation-circle fa-2x me-2"></em>{{ __('This cart is not submitted. Some features are disabled.') }}
                    </x-bs::alert>
                @endunless

                <div class="d-grid">
                    <h1 class="fs-4">#{{ $cart->id }}</h1>
                    <div class="d-flex small text-secondary mb-3">
                        <div class="me-3">{{ optional($cart->submitted_at)->isoFormat('dddd, ll HH:mm') }}</div>

                        @can('View cart history')
                            <livewire:dashboard.cart.show-cart-events :cart="$cart"/>
                        @endcan
                    </div>

                    <livewire:dashboard.cart.cart-header :cart="$cart"/>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-lg-5 col-xxl-4 order-xxl-1">
                        <div class="d-flex flex-column gap-4 ">
                            <livewire:dashboard.cart.track-and-trace :cart_id="$cart->id"/>
                            <livewire:dashboard.cart.customer-notes :cart="$cart"/>
                            <livewire:dashboard.cart.cart-overview :cart="$cart"/>
                            <livewire:dashboard.cart.shipping-address :cart="$cart"/>
                            {{--                        <livewire:dashboard.cart.billing-address :cart="$cart"/>--}}
                            <livewire:dashboard.cart.invoice :cart="$cart"/>
                        </div>
                    </div>

                    <div class="col">
                        <livewire:dashboard.cart.show-cart :cart="$cart"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
