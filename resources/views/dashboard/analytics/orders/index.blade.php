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

            <div class="col-12">
                @include('eshop::dashboard.analytics.orders.partials.monthly-orders')
            </div>

            <div class="col-12 col-md-4">
                @include('eshop::dashboard.analytics.orders.partials.weekday-orders')
            </div>

            <div class="col-12 col-md-4">
                @include('eshop::dashboard.analytics.orders.partials.hourly-orders')
            </div>

            <div class="col-12 col-md-4">
                @include('eshop::dashboard.analytics.orders.partials.yearly-orders')
            </div>

            {{-- Income/Profits --}}
            <div class="col-12 col-lg-7 col-xxl-8">
                @include('eshop::dashboard.analytics.orders.partials.monthly-income')
            </div>

            <div class="col-12 col-lg-5 col-xxl-4">
                @include('eshop::dashboard.analytics.orders.partials.yearly-income')
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        const horizontalLinePlugin = {
            id: 'horizontalLine',
            beforeDraw: function (chartInstance) {
                const context = chartInstance.ctx
                const yScale = chartInstance.scales["y"];

                let index;
                let line;
                let style;
                let yValue;

                if (chartInstance.options.horizontalLine) {
                    for (index = 0; index < chartInstance.options.horizontalLine.length; index++) {
                        line = chartInstance.options.horizontalLine[index];

                        if (!line.style) {
                            style = "#6aa4f0";
                        } else {
                            style = line.style;
                        }

                        if (line.y) {
                            yValue = yScale.getPixelForValue(line.y);
                        } else {
                            yValue = 0;
                        }

                        context.lineWidth = 1;

                        if (yValue) {
                            context.beginPath();
                            context.moveTo(0, yValue);
                            context.lineTo(chartInstance.width, yValue);
                            context.strokeStyle = style;
                            context.stroke();

                            // context.fillText(line.y, 0, yValue + context.lineWidth + 3);
                        }

                        if (line.text) {
                            context.fillStyle = style;
                        }
                    }
                }
            }
        }

        Chart.register(horizontalLinePlugin)
    </script>
@endpush