@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">POS</h1>
@endsection

@section('main')
    <form x-data="{submitting: false}" x-on:submit="submitting = true" x-on:keydown.enter.prevent="" action="{{ route('pos.store') }}" method="post">
        @csrf

        <div class="row">
            <livewire:dashboard.pos.models/>

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
