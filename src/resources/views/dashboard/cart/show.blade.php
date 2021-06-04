@extends('dashboard.layouts.dashboard')

@section('main')
    <div class="col-12 p-4">
        <div class="row g-4">
            <div class="col-12 col-xxl d-grid gap-3">

                @unless($cart->submitted_at)
                    <x-bs::alert type="danger" class="d-flex align-items-center">
                        <em class="fas fa-exclamation-circle fa-2x me-2"></em>{{ __('This cart is not submitted. Some features are disabled.') }}
                    </x-bs::alert>
                @endunless

                <div class="d-grid gap-2">
                    <div class="d-flex justify-content-between">
                        <a class="text-secondary text-decoration-none" href="{{ route('carts.index') }}">
                            <em class="fas fa-chevron-left me-2"></em>{{ __('Orders') }}
                        </a>
                        <div class="d-flex gap-4 text-secondary fs-5">
                            <em class="fas fa-arrow-left"></em>
                            <em class="fas fa-arrow-right"></em>
                        </div>
                    </div>

                    <div class="d-flex gap-4 align-items-baseline">
                        <h1 class="fs-3 mb-0">#{{ $cart->id }}</h1>
                        <div class="text-secondary">{{ optional($cart->submitted_at)->isoFormat('llll') }}</div>
                    </div>

                    @livewire('dashboard.cart.cart-header', compact('cart'))
                </div>

                <div class="row g-4">
                    <div class="col-12 col-xxl-4 d-flex flex-column gap-2 order-xxl-1">
                        @livewire('dashboard.cart.customer-notes', compact('cart'))
                        @livewire('dashboard.cart.cart-overview', compact('cart'))
                        @livewire('dashboard.cart.shipping-address', compact('cart'))
                        @livewire('dashboard.cart.billing-address', compact('cart'))
                        @livewire('dashboard.cart.invoice', compact('cart'))
                    </div>

                    <div class="col">
                        @livewire('dashboard.cart.show-cart', compact('cart'))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
