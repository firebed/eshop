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
    <div class="col-12 p-4">
        @include('eshop::dashboard.analytics.partials.navbar')
        
        <div class="row g-4">
            <div class="col-lg-8">
                @include('eshop::dashboard.analytics.orders.partials.total-orders')
            </div>
            
            <div class="col-lg-4">
                @include('eshop::dashboard.analytics.orders.partials.order-statuses')
            </div>

            <div class="col-12 col-xl-6">
                @include('eshop::dashboard.analytics.orders.partials.monthly-orders')
            </div>

            <div class="col-12 col-sm-6 col-xxl-3">
                @include('eshop::dashboard.analytics.orders.partials.weekday-orders')
            </div>
            
            <div class="col-12 col-sm-6 col-xxl-3">
                @include('eshop::dashboard.analytics.orders.partials.yearly-orders')
            </div>

            <div class="col-12 col-md-7">
                @include('eshop::dashboard.analytics.orders.partials.monthly-income')
            </div>

            <div class="col-12 col-md-5">
                @include('eshop::dashboard.analytics.orders.partials.yearly-income')
            </div>
        </div>
    </div>
@endsection
