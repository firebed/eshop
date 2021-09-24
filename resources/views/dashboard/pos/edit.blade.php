@extends('eshop::dashboard.layouts.master')

@section('main')
    <form x-data="{ submitting: false }" x-on:submit="submitting = true" x-on:keydown.enter.prevent="" action="{{ route('pos.update', $cart) }}" method="post">
        @csrf
        @method('put')

        <div class="row">
            @livewire('dashboard.pos.models', ['editing' => true])

            @livewire('dashboard.pos.products', [
                'cart_id' => $cart->id,
                'items' => old('items', $items),
                'submitted_at' => $cart->submitted_at ?? now(),
                'shipping_fee' => old('shipping_fee', $cart->shipping_fee) ?? 0,
                'payment_fee' => old('payment_fee', $cart->payment_fee) ?? 0
            ])
        </div>

        @livewire('dashboard.pos.shipping', [
                'shipping' => old('shipping', $cart->shippingAddress?->getAttributes()) ?? [],
                'email' => old('email', $cart->email) ?? '',
                'method' => old('country_shipping_method_id') ?? '',
                'fee' => old('shipping_fee', $cart->shipping_fee),
        ])

        @livewire('dashboard.pos.payment', [
                'country_id' => old('shipping.country_id', $cart->shippingAddress->country_id ?? '') ?? '',
                'items' => old('items', []),
                'shipping_fee' => old('shipping_fee', 0) ?? 0,
                'method' => old('country_payment_method_id') ?? '',
                'fee' => old('payment_fee', $cart->payment_fee) ?? ''
        ])

        @livewire('dashboard.pos.invoice', [
                'invoice' => old('invoice', $cart->invoice?->getAttributes()) ?? [],
                'invoiceAddress' => old('invoiceAddress', $cart?->invoice?->billingAddress?->getAttributes()) ?? []
        ])
    </form>
@endsection
