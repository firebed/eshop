@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">{{ __("Analytics") }}</h1>
@endsection

@push('header_scripts')
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net/">
@endpush

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>
@endpush

@section('main')
    <div class="col-12 p-4 d-grid gap-3">
        @include('eshop::dashboard.analytics.partials.navbar')
        
        @include('eshop::dashboard.analytics.partials.date-range')

        <div class="row row-cols-1 row-cols-md-2 row-cols-xxl-3 g-4">
            <div class="col">
                @include('eshop::dashboard.analytics.partials.total-orders')
            </div>

            <div class="col">
                @include('eshop::dashboard.analytics.partials.total-sales')
            </div>

            <div class="col">
                @include('eshop::dashboard.analytics.partials.total-profit')
            </div>

            <div class="col">
                @include('eshop::dashboard.analytics.partials.payment-methods')
            </div>

            <div class="col">
                @include('eshop::dashboard.analytics.partials.shipping-methods')
            </div>
            
            <div class="col">
                @include('eshop::dashboard.analytics.partials.orders-channel')
            </div>
        </div>
    </div>
@endsection
