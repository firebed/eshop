@extends('layouts.master', ['title' => __("Order") . ' #' . $order->id])

@section('main')
    <div class="container-fluid py-4">
        <div class="container-xxl">
            <x-bs::card class="shadow-none bg-light">
                <div class="row row-cols-1 row-cols-lg-2 g-0">
                    <div class="col p-3 bg-white rounded-start border-end">
                        <div class="d-grid align-items-start gap-4">
                            <h1 class="fs-4 fw-normal">{{ __('Order') . ' #' . $order->id }}</h1>

                            @includeWhen(filled($order->voucher), 'account.order.partials.tracking')

                            @include('account.order.partials.order-details')

                            @include('account.order.partials.shipping-address')

                            @includeIf($order->billingAddress, 'account.order.partials.billing-address')

                            @includeIf($order->invoice, 'account.order.partials.invoice')

                            @includeWhen($order->details, 'account.order.partials.customer-request')
                        </div>
                    </div>

                    <div class="col p-3">
                        <div class="table-responsive">
                            @include('account.order.partials.products')
                        </div>
                    </div>
                </div>
            </x-bs::card>
        </div>
    </div>
@endsection
