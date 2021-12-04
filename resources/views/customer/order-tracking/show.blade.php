@extends('eshop::customer.layouts.master', ['title' => __("Order") . ' #' . $order->id])

@section('main')
    <div class="container-fluid py-4">
        <div class="container-xxl">
            <x-bs::card class="shadow-none bg-light">
                <div class="row row-cols-1 row-cols-lg-2 g-0">
                    <div class="col p-3 bg-white rounded-start border-end">
                        <div class="d-grid align-items-start gap-4">
                            <h1 class="fs-4 fw-normal">{{ __('Order') . ' #' . $order->id }}</h1>

                            @includeWhen(filled($order->voucher), 'eshop::customer.order-tracking.partials.tracking')

                            @include('eshop::customer.order-tracking.partials.order-details')

                            @include('eshop::customer.order-tracking.partials.shipping-address')

                            @includeWhen($order->billingAddress, 'eshop::customer.order-tracking.partials.billing-address')

                            @includeWhen($order->invoice, 'eshop::customer.order-tracking.partials.invoice')

                            @includeWhen($order->details, 'eshop::customer.order-tracking.partials.customer-request')
                        </div>
                    </div>

                    <div class="col p-3">
                        <div class="table-responsive">
                            @include('eshop::customer.order-tracking.partials.products')
                        </div>
                    </div>
                </div>
            </x-bs::card>
        </div>
    </div>
@endsection
