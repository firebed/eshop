@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Simplify") }}</div>
@endsection

@section('main')
    <div class="col-6 p-4">
        @livewire('dashboard.simplify.env')

        <script type="text/javascript" src="https://www.simplify.com/commerce/simplify.pay.js"></script>
        <button data-sc-key="{{ Cache::get('SIMPLIFY_HOSTED_PUBLIC_KEY') }}"
                data-name="Sandbox tester"
                data-description="Test purchase"
                data-reference="dec"
                data-amount="50"
                data-color="#12B830">
            Buy Now
        </button>
                
        <div class="mt-4">
            <form id="checkout-form" method="POST" action="{{ route('simplify.checkout') }}">
                @csrf

                <input type="checkbox" name="country_payment_method_id" data-payment-method-name="credit_card_simplify" checked class="d-none">

                @include('eshop::customer.checkout.payment.ext.simplify')

                <button x-bind:disabled="$store.form.disabled" type="submit" class="btn btn-green mt-3" id="pay-with-simplify">
                    <div x-cloak x-show="$store.form.disabled" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    <em x-show="!$store.form.disabled" class="fas fa-lock me-2"></em>

                    {{ __('Pay') . ' ' . format_currency(0.5) }}
                </button>

            </form>
        </div>
    </div>
@endsection

{{--@push('footer_scripts')--}}
{{--    <script>--}}
{{--        document.getElementById('cc-number').value = '2222405343248877'--}}
{{--        document.getElementById('cc-expiry').value = '12/24'--}}
{{--        document.getElementById('cc-cvc').value = '124'--}}
{{--    </script>--}}
{{--@endpush--}}