@extends('eshop::dashboard.layouts.master')

@section('main')
    <form x-data="{submitting: false}" x-on:submit="submitting = true" x-on:keydown.enter.prevent="" action="{{ route('pos.store') }}" method="post" class="d-flex px-0">
        @csrf
        <div class="col-12 col-md-8 d-grid p-4 border-end">
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

                <div class="col d-grid">
                    <button x-bind:disabled="submitting" type="submit" name="action" value="save" class="btn btn-green p-3 fw-500">
                        <em class="fas fa-save fs-4 text-light"></em>
                        <br>
                        Αποθήκευση
                    </button>
                </div>

                <div class="col d-grid">
                    <button x-bind:disabled="submitting" type="submit" name="action" value="saveAsOrder" class="btn btn-primary p-3 fw-500">
                        <em class="fas fa-cart-arrow-down fs-4 text-light"></em>
                        <br>
                        Παραγγελία
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 bg-white p-4 shadow">
            @livewire('dashboard.pos.products', [
                'items' => old('items', []),
                'submitted_at' => now(),
                'shipping_fee' => old('shipping_fee', 0),
                'payment_fee' => old('payment_fee', 0)
            ])
        </div>

        @livewire('dashboard.pos.shipping', [
                'shipping' => old('shipping', []),
                'email' => old('email', '') ?? '',
                'method' => old('shipping_method_id', '') ?? '',
                'fee' => old('shipping_fee', 0) ?? ''
        ])

        @livewire('dashboard.pos.payment', [
                'country_id' => old('shipping.country_id', '') ?? '',
                'items' => old('items', []),
                'shipping_fee' => old('shipping_fee', 0) ?? 0,
                'method' => old('payment_method_id', '') ?? '',
                'fee' => old('payment_fee', 0) ?? ''
        ])

        @livewire('dashboard.pos.invoice', [
                'invoice' => old('invoice', []),
                'invoiceAddress' => old('invoiceAddress', [])
        ])
    </form>
@endsection
