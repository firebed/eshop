@extends('customer.layouts.master', ['title' =>  __('Cart')])

@push('header_scripts')
    <link rel="stylesheet" href="https://unpkg.com/simplebar@5.3.3/dist/simplebar.css"/>
    <script src="https://unpkg.com/simplebar@5.3.3/dist/simplebar.min.js"></script>

    <script src="https://www.paypal.com/sdk/js?client-id={{ env("PAYPAL_SANDBOX_CLIENT_ID") }}&currency=EUR"></script>
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@section('main')
    <div class="container-fluid py-5">
        <div class="container">
            <div class="d-grid gap-3">
                <h1 class="fs-3 fw-normal">{{ __('Checkout') }}</h1>

                <livewire:customer.checkout.edit-checkout-payment />
            </div>
        </div>
    </div>
@endsection
