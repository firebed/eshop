@extends('eshop::dashboard.layouts.master')

@section('main')
    <form x-data="{ submitting: false }" x-on:submit="submitting = true" x-on:keydown.enter.prevent="" action="{{ route('pos.update', $cart) }}" method="post" class="d-flex px-0">
        @csrf
        @method('put')

        <div class="col-12 col-md-7 d-grid p-4 border-end">
            <div class="row scrollbar" style="overflow-y:auto; height: calc(100vh - 13.8rem)">
                <livewire:dashboard.pos.models/>
            </div>

            <hr>

            <div class="row g-3">
                <div class="col d-grid">
                    <button x-bind:disabled="submitting" type="button" class="btn btn-warning fw-500" data-bs-toggle="offcanvas" data-bs-target="#shipping-form">
                        <em class="fas fa-map-marked-alt fs-4 text-orange-700"></em>
                        <br>
                        Στοιχεία αποστολής
                    </button>
                </div>

                <div class="col d-grid">
                    <button x-bind:disabled="submitting" type="button" class="btn btn-info p-3 fw-500" data-bs-toggle="offcanvas" data-bs-target="#payment-form">
                        <em class="fas fa-money-check-alt fs-4 text-cyan-800"></em>
                        <br>
                        Στοιχεία πληρωμής
                    </button>
                </div>

                <div class="col d-grid">
                    <button x-bind:disabled="submitting" type="button" class="btn btn-danger p-3 fw-500" data-bs-toggle="offcanvas" data-bs-target="#invoice-form">
                        <em class="fas fa-file-invoice fs-4 text-light"></em>
                        <br>
                        Τιμολόγιο
                    </button>
                </div>

                <div class="col-4 d-grid">
                    <button x-bind:disabled="submitting" type="submit" name="action" value="save" class="btn btn-green p-3 fw-500">
                        <em class="fas fa-save fs-4 text-light"></em>
                        <br>
                        Αποθήκευση
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-5 bg-white p-4 shadow">
            @livewire('dashboard.pos.products', [
                'items' => old('items', $items),
                'submitted_at' => $cart->submitted_at ?? now(),
                'shipping_fee' => old('shipping_fee', $cart->shipping_fee) ?? 0,
                'payment_fee' => old('payment_fee', $cart->payment_fee) ?? 0
            ])
        </div>

        @livewire('dashboard.pos.shipping', [
                'shipping' => old('shipping', $cart->shippingAddress?->getAttributes()) ?? [],
                'email' => old('email', $cart->email) ?? '',
                'method' => old('shipping_method_id', $cart->shipping_method_id) ?? '',
                'fee' => old('shipping_fee', $cart->shipping_fee),
        ])

        @livewire('dashboard.pos.payment', [
                'country_id' => old('shipping.country_id', $cart->shippingAddress->country_id ?? '') ?? '',
                'items' => old('items', []),
                'shipping_fee' => old('shipping_fee', 0) ?? 0,
                'method' => old('payment_method_id', $cart->payment_method_id) ?? '',
                'fee' => old('payment_fee', $cart->payment_fee) ?? ''
        ])

        @livewire('dashboard.pos.invoice', [
                'invoice' => old('invoice', $cart->invoice?->getAttributes()) ?? [],
                'invoiceAddress' => old('invoiceAddress', $cart?->invoice?->billingAddress?->getAttributes()) ?? []
        ])
    </form>
@endsection
