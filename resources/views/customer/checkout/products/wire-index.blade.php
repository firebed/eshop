@extends('eshop::customer.layouts.master', ['title' =>  __('Cart')])

@section('main')
    <div class="container-fluid py-5">
        <div class="container">
            @if(session()->has('guest-cart-merged-with-user-cart'))
                <div class="alert bg-teal-400 alert-dismissible fade show" role="alert">
                    <span>{{ __("Your current cart was merged with the cart from your previous login.") }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <livewire:customer.checkout.show-checkout-products />
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
@endpush
