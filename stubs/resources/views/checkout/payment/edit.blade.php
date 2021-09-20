@extends('layouts.master', ['title' =>  __('Cart')])

@push('header_scripts')
    <link rel="stylesheet" href="https://unpkg.com/simplebar@5.3.3/dist/simplebar.css"/>
    <script src="https://unpkg.com/simplebar@5.3.3/dist/simplebar.min.js"></script>

    <script src="https://www.paypal.com/sdk/js?client-id={{ config("eshop." . app()->isProduction() ? "paypal_live_client_id" : "paypal_sandbox_client_id") }}&currency={{ config('eshop.currency') }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@section('main')
    <div class="container-fluid py-5">
        <div class="container">
            <div class="d-grid gap-3">
                <h1 class="fs-3 fw-normal">{{ __('Checkout') }}</h1>

                <form action="{{ route('checkout.payment.store', app()->getLocale()) }}" method="post" id="checkout-form">
                    @csrf

                    @if(session()->has('order-total-changed'))
                        <x-bs::alert type="warning">
                            {{ __("Your cart has changed during payment. Please check your products and try again.") }}
                        </x-bs::alert>
                    @endif

                    @if($paymentMethods->isEmpty())
                        <x-bs::alert type="danger">
                            {{ __("There aren't any payment options available right now.") }}
                        </x-bs::alert>
                    @endif

                    @if($shippingMethods->isEmpty())
                        <x-bs::alert type="danger" wire:key="empty-shipping-methods">
                            {{ __("There aren't any payment options available right now.") }}
                        </x-bs::alert>
                    @endif

                    <div class="row row-cols-1 row-cols-lg-2 gx-5 gy-4">
                        <div class="col flex-grow-1">
                            <div class="d-grid gap-4 align-items-start">
                                @include('checkout.payment.partials.checkout-shipping-methods')
                                @include('checkout.payment.partials.checkout-payment-methods')

                                <div class="d-none d-lg-flex">
                                    <a href="{{ route('checkout.details.edit', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                                        <em class="fas fa-chevron-left me-3"></em>{{ __('Back to shipping address') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col w-lg-25r align-self-start sticky-md-top" style="top: 2rem">
                            @include('checkout.payment.partials.checkout-payment-summary')
                        </div>

                        <div class="d-flex d-lg-none">
                            <a href="{{ route('checkout.details.edit', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                                <em class="fas fa-chevron-left me-3"></em>{{ __('Back to shipping address') }}
                            </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
