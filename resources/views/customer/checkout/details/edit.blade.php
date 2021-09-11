@extends('eshop::customer.layouts.master', ['title' =>  __('Shipping address')])

@push('header_scripts')
    <link rel="stylesheet" href="https://unpkg.com/simplebar@5.3.3/dist/simplebar.css"/>
    <script src="https://unpkg.com/simplebar@5.3.3/dist/simplebar.min.js"></script>
@endpush

@section('main')
    <div class="container-fluid py-5">
        <div class="container">

            @if(session()->has('guest-cart-merged-with-user-cart'))
                <div class="alert bg-teal-400 alert-dismissible fade show" role="alert">
                    <span>{{ __("Your current cart was merged with the cart from your previous login.") }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-grid gap-3">
                <h1 class="fs-3 fw-normal">{{ __('Shipping address') }}</h1>

                <form x-data="{submitting:false}" x-on:submit="submitting = true" action="{{ route('checkout.details.update', app()->getLocale()) }}" method="post">
                    @csrf
                    @method('put')

                    <div class="d-grid gap-4">
                        <div class="row row-cols-1 row-cols-lg-2 gx-5 gy-4">
                            <div class="col flex-grow-1">
                                <div class="d-grid gap-4 align-items-start">
                                    @include('eshop::customer.checkout.details.partials.checkout-details-shipping-addresses')
                                    @include('eshop::customer.checkout.details.partials.checkout-details-invoicing')

                                    <x-bs::input.floating-label for="customer-notes" label="{{ __('Instructions about your order') }}">
                                        <x-bs::input.textarea name="details" error="details" id="customer-notes" style="height: 6rem" placeholder="{{ __('Instructions about your order') }}">{{ old('details', $order->details) ?? '' }}</x-bs::input.textarea>
                                    </x-bs::input.floating-label>

                                    <div class="d-none d-lg-flex">
                                        <a href="{{ route('checkout.products.index', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                                            <em class="fas fa-chevron-left me-3"></em>{{ __('Back to cart') }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col w-lg-25r align-self-start sticky-md-top" style="top: 2rem">
                                @include('eshop::customer.checkout.details.partials.checkout-details-summary')
                            </div>
                        </div>

                        <div class="d-flex d-lg-none">
                            <a href="{{ route('checkout.products.index', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                                <em class="fas fa-chevron-left me-3"></em>{{ __('Back to cart') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
