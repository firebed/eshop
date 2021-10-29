@extends('layouts.master', ['title' =>  __('Cart')])

@push('meta')
    <link rel="canonical" href="{{ route('checkout.products.index', app()->getLocale()) }}" />
    @foreach(array_keys(config('eshop.locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ route('checkout.products.index', $locale) }}" />
    @endforeach

    <meta name='robots' content='index, follow' />

    <meta name="description" content="">
    <meta property="og:title" content="{{ __('Cart') }}">
    {{--    <meta property="og:description" content="">--}}
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('checkout.products.index', app()->getLocale()) }}">
    @include('layouts.partials.meta-logo')
    <meta name="twitter:card" content="summary" />

    <script type="application/ld+json">{!! schema()->webPage(__('Cart')) !!}</script>
@endpush

@section('main')
    <div class="container-fluid py-5">
        <div class="container-xxl">
            @if(session()->has('guest-cart-merged-with-user-cart'))
                <div class="alert bg-teal-400 alert-dismissible fade show" role="alert">
                    <span>{{ __("Your current cart was merged with the cart from your previous login.") }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-grid gap-3">
                <livewire:checkout.show-checkout-products />
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
@endpush
