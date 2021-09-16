@extends('layouts.master', ['title' => __('Order') . ' #' . $order->id])

@section('main')
    <div class="container-fluid bg-pink-500">
        <div class="container pt-4">
            <div class="row py-4">
                <div class="col fs-3 text-light">{{ user()->fullName }}</div>
            </div>
        </div>
    </div>

    @include('account.partials.account-navbar')

    <div class="container-fluid py-4" @if(session('success')) x-data x-init="$dispatch('toast-notification', {type: 'success', title: '{{ session('success') }}', content: '', autohide: true})" @endif>
        <div class="container">
            <x-bs::card class="shadow-none bg-light">
                <div class="row g-0">
                    <div class="col d-grid gap-4 p-3 bg-white rounded-start border-end">
                        <h1 class="fs-4 fw-normal">{{ __('Order') . ' #' . $order->id }}</h1>

                        <div class="d-grid text-secondary">
                            <div class="text-dark fw-500 mb-2">{{ __("eshop::account.order.info") }}</div>
                            <x-bs::group :label="__('Date')" inline>
                                <span class="text-dark">{{ $order->submitted_at->isoFormat('llll') }}</span>
                            </x-bs::group>

                            <x-bs::group :label="__('Status')" inline>
                                <span class="text-dark">{{ __('eshop::cart.status.action.' . $order->status->name) }}</span>
                            </x-bs::group>

                            <x-bs::group :label="__('Shipping')" inline>
                                <span class="text-dark">{{ __("eshop::shipping.{$order->shippingMethod->name}") ?? '' }}</span>
                            </x-bs::group>

                            <x-bs::group :label="__('Shipping fee')" inline>
                                <span class="text-dark">{{ format_currency($order->shipping_fee) }}</span>
                            </x-bs::group>

                            <x-bs::group :label="__('Payment')" inline>
                                <span class="text-dark">{{ __("eshop::payment.{$order->paymentMethod->name}") ?? '' }}</span>
                            </x-bs::group>

                            <x-bs::group :label="__('Payment fee')" inline>
                                <span class="text-dark">{{ format_currency($order->payment_fee) }}</span>
                            </x-bs::group>

                            <x-bs::group :label="__('Total')" inline class="fw-500">
                                <span class="text-dark">{{ format_currency($order->total) }}</span>
                            </x-bs::group>
                        </div>

                        @if($order->details)
                            <div class="d-grid text-secondary">
                                <div class="text-dark fw-500 mb-2">{{ __("eshop::account.order.comments") }}</div>
                                <div>{{ $order->details }}</div>
                            </div>
                        @endif

                        <div class="d-grid text-secondary">
                            <div class="text-dark fw-500 mb-2">{{ __("Shipping address") }}</div>
                            <div>{{ $order->shippingAddress->full_name }}</div>
                            <div>{{ $order->shippingAddress->full_street }}</div>
                            <div>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->postcode }}</div>
                            <div>{{ __("Province") }}: {{ $order->shippingAddress->province }}</div>
                            <div>{{ $order->shippingAddress->country->name }}</div>
                        </div>

                        @if($order->billingAddress)
                            <div class="d-grid text-secondary">
                                <div class="text-dark fw-500 mb-2">{{ __("Invoice information") }}</div>
                                <div>{{ $order->billingAddress->full_name }}</div>
                                <div>{{ $order->billingAddress->full_street }}</div>
                                <div>{{ $order->billingAddress->city }}, {{ $order->billingAddress->postcode }}</div>
                                <div>{{ __("Province") }}: {{ $order->shippingAddress->province }}</div>
                                <div>{{ $order->billingAddress->country->name }}</div>
                            </div>
                        @endif

                        @if($order->invoice)
                            <div class="d-grid text-secondary">
                                <div class="text-dark fw-500 mb-2">{{ __("Invoice information") }}</div>
                                <div>{{ $order->shippingAddress->full_name }}</div>
                                <div>{{ $order->shippingAddress->full_street }}</div>
                                <div>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->postcode }}</div>
                                <div>{{ __("Province") }}: {{ $order->shippingAddress->province }}</div>
                                <div>{{ $order->shippingAddress->country->name }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="col p-3">
                        <div class="table-responsive">
                            <x-bs::table>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <div class="ratio ratio-1x1 w-5r">
                                                @if($product->image && $src = $product->image->url('sm'))
                                                    <img src="{{ $src }}" alt="{{ $product->trademark }}" class="h-auto mh-100 w-auto mw-100 rounded">
                                                @endif
                                            </div>
                                        </td>

                                        <td>{{ $product->name }}</td>
                                        <td class="text-end">{{ format_number($product->pivot->quantity) }} x</td>
                                        <td class="text-end">{{ format_currency($product->pivot->netValue) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </x-bs::table>
                        </div>
                    </div>
                </div>
            </x-bs::card>
        </div>
    </div>
@endsection
