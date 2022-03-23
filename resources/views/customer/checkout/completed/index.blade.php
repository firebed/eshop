@extends('eshop::customer.layouts.master', ['title' =>  __('Cart')])

@if(eshop('google_conversion_id') && eshop('google_conversion_tracking'))
    @push('header_scripts')
        <!-- Event snippet for Purchase conversion page -->
        <script>
            gtag('event', 'conversion', {
                'send_to': '{{ eshop('google_conversion_if') }}/{{ eshop('google_conversion_tracking') }}',
                'value': {{ $cart->total }},
                'currency': '{{ eshop('currency') }}',
                'transaction_id': '{{ $cart->id }}'
            });
        </script>
    @endpush
@endif

@section('main')
    <div class="container-fluid bg-white">
        <div class="container-xxl">
            <div class="row">
                <div class="col d-grid gap-4 py-4">
                    <div class="vstack border rounded p-3">
                        <div class="text-secondary">{{ __('Order') . ' #' . $cart->id }}</div>
                        <h1 class="fs-4 fw-500">{{ __('eshop::order.thank_you', ['name' => $cart->shippingAddress->first_name]) }}</h1>
                        <div>{{ __("eshop::order.received") }}</div>
                    </div>

                    <div class="d-grid text-secondary border rounded p-3">
                        <div class="text-dark fw-500 mb-2">{{ __("eshop::account.order.info") }}</div>

                        <x-bs::group :label="__('Date')" inline>
                            <span class="text-dark">{{ $cart->submitted_at?->isoFormat('llll') }}</span>
                        </x-bs::group>

                        @if($cart->status)
                            <x-bs::group :label="__('Status')" inline>
                                <span class="text-dark">{{ __('eshop::cart.status.action.' . $cart->status->name) }}</span>
                            </x-bs::group>
                        @endif

                        <x-bs::group :label="__('Shipping')" inline>
                            <span class="text-dark">@isset($cart->shippingMethod) {{ __("eshop::shipping.{$cart->shippingMethod->name}") }} @else {{ "-" }} @endisset</span>
                        </x-bs::group>

                        <x-bs::group :label="__('Shipping fee')" inline>
                            <span class="text-dark">{{ format_currency($cart->shipping_fee) }}</span>
                        </x-bs::group>

                        <x-bs::group :label="__('Payment')" inline>
                            <span class="text-dark">@isset($cart->paymentMethod) {{ __("eshop::payment.{$cart->paymentMethod->name}") }} @else {{ "-" }} @endisset</span>
                        </x-bs::group>

                        <x-bs::group :label="__('Payment fee')" inline>
                            <span class="text-dark">{{ format_currency($cart->payment_fee) }}</span>
                        </x-bs::group>

                        <x-bs::group :label="__('Total')" inline class="fw-500">
                            <span class="text-dark">{{ format_currency($cart->total) }}</span>
                        </x-bs::group>
                    </div>

                    @if($cart->details)
                        <div class="d-grid text-secondary border rounded p-3">
                            <div class="text-dark fw-500 mb-2">{{ __("eshop::account.order.comments") }}</div>
                            <div>{{ $cart->details }}</div>
                        </div>
                    @endif

                    <div class="d-grid text-secondary border rounded p-3">
                        <div class="text-dark fw-500 mb-2">{{ __("Shipping details") }}</div>
                        <div>{{ $cart->shippingAddress->full_name }}</div>
                        <div>{{ $cart->shippingAddress->full_street }}</div>
                        <div>{{ $cart->shippingAddress->city }}, {{ $cart->shippingAddress->postcode }}</div>
                        <div>{{ __("Province") }}: {{ $cart->shippingAddress->province }}</div>
                        <div>{{ $cart->shippingAddress->country->name }}</div>
                    </div>

                    @if($cart->billingAddress)
                        <div class="d-grid text-secondary border rounded p-3">
                            <div class="text-dark fw-500 mb-2">{{ __("Invoice information") }}</div>
                            <div>{{ $cart->billingAddress->full_name }}</div>
                            <div>{{ $cart->billingAddress->full_street }}</div>
                            <div>{{ $cart->billingAddress->city }}, {{ $cart->billingAddress->postcode }}</div>
                            <div>{{ __("Province") }}: {{ $cart->shippingAddress->province }}</div>
                            <div>{{ $cart->billingAddress->country?->name }}</div>
                        </div>
                    @endif

                    @if($order->isDocumentInvoice() && $cart->invoice && $cart->invoice->billingAddress)
                        <div class="d-grid text-secondary border rounded p-3">
                            <div class="text-dark fw-500 mb-2">{{ __("Invoice") }}</div>
                            <div>{{ $cart->invoice->name }}</div>
                            <div>{{ $cart->invoice->job }}</div>
                            <div>{{ $cart->invoice->vat_number }} {{ $cart->invoice->tax_authority }}</div>
                            <div>{{ $cart->invoice->billingAddress->full_street }}</div>
                            <div>{{ $cart->invoice->billingAddress->city }}, {{ $cart->invoice->billingAddress->postcode }}</div>
                            <div>{{ __("Province") }}: {{ $cart->invoice->billingAddress->province }}</div>
                            <div>{{ $cart->invoice->billingAddress->country?->name }}</div>
                        </div>
                    @endif
                </div>

                <div class="col">
                    <div class="table-responsive h-100 bg-light border-start border-end py-4">
                        <x-bs::table>
                            <tbody>
                            @foreach($cart->products as $product)
                                <tr>
                                    <td class="w-6r">
                                        <div class="ratio ratio-1x1">
                                            @if($product->image && $src = $product->image->url('sm'))
                                                <img src="{{ $src }}" alt="{{ $product->trademark }}" class="img-top">
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        @if($product->isVariant())
                                            <div class="vstack">
                                                <div class="fw-500">{{ $product->parent->name }}</div>
                                                <small class="text-secondary">{{ $product->option_values }}</small>
                                            </div>
                                        @else
                                            <div class="fw-500">{{ $product->trademark }}</div>
                                        @endif
                                    </td>

                                    <td class="text-end text-nowrap">{{ format_number($product->pivot->quantity) }}&nbsp;x</td>
                                    <td class="text-end">{{ format_currency($product->pivot->netValue) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </x-bs::table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
