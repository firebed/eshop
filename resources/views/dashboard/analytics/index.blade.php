@extends('eshop::dashboard.layouts.master', ['title' => 'Analytics'])

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>
@endpush

@section('main')
    <div class="col-12 p-4 d-grid gap-3">
        <h1 class="fs-3 mb-0">{{ __("Analytics") }}</h1>

        <div class="row row-cols-1 row-cols-xxl-3">
            <div class="col">
                @include('eshop::dashboard.analytics.partials.total-orders')
            </div>

            <div class="col">
                @include('eshop::dashboard.analytics.partials.total-sales')
            </div>

            <div class="col">
                @include('eshop::dashboard.analytics.partials.orders-channel')
            </div>
        </div>
    </div>
@endsection
