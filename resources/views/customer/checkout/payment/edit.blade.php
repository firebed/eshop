@extends('eshop::customer.layouts.master', ['title' =>  __('Cart')])

@push('footer_scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{ config("eshop." . (app()->isProduction() ? "paypal_live_client_id" : "paypal_sandbox_client_id")) }}&currency={{ eshop('currency') }}"></script>
@endpush

@section('main')
    <div class="container-fluid py-5">
        <div class="container-xxl">
            <div class="d-grid gap-3">
                <h1 class="fs-3 fw-normal">{{ __('Checkout') }}</h1>

                <form x-data x-on:submit="$store.form.disable()" action="{{ route('checkout.payment.store', app()->getLocale()) }}" method="post" id="checkout-form">
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
                                @include('eshop::customer.checkout.payment.partials.checkout-shipping-methods')
                                @include('eshop::customer.checkout.payment.partials.checkout-payment-methods')

                                <div class="d-none d-lg-flex">
                                    <a href="{{ route('checkout.details.edit', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                                        <em class="fas fa-chevron-left me-3"></em>{{ __('Back to shipping address') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col w-lg-25r align-self-start sticky-md-top" style="top: 2rem">
                            @include('eshop::customer.checkout.payment.partials.checkout-payment-summary')
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

@push('footer_scripts')
    @include('eshop::dashboard.layouts.toasts')

    <script>
        document.addEventListener('alpine:init', () => {
            const form = document.getElementById("checkout-form");

            Alpine.store('form', {
                disabled: false,

                enable() {
                    this.disabled = false
                    form.querySelectorAll('input[type=radio]').forEach(i => i.removeAttribute('disabled'))
                },

                disable() {
                    this.disabled = true
                    form.querySelectorAll('input[type=radio]').forEach(i => i.setAttribute('disabled', 'disabled'))
                }
            })
        })
    </script>
@endpush
