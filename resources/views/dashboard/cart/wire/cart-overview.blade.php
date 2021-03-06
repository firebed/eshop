<div>
    <x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap">
        <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100 py-0">{{ __('Cart') }}</x-bs::navbar.brand>

        <x-bs::navbar.toggler target="cart-info"/>

        <x-bs::navbar.collapse id="cart-info">
            <div class="d-grid flex-grow-1 gap-1 mt-3">
                <a href="#" class="text-decoration-none" wire:click="edit">{{ __("Edit") }}</a>

                <x-bs::group label="{{ __('Document') }}" inline>
                    <x-bs::badge :type="$cart->document_type === 'Invoice' ? 'danger' : 'blue'">{{ __($cart->document_type) }}</x-bs::badge>
                </x-bs::group>

                <x-bs::group label="{{ __('Channel') }}" inline>
                    <x-bs::badge type="blue">{{ __("eshop::cart.channel.$cart->channel") }}</x-bs::badge>
                </x-bs::group>

                <x-bs::group label="{{ __('Shipping') }}" inline>
                    @isset($shippingMethod){{ __("eshop::shipping.$shippingMethod->name") ?? '' }}@endisset ({{ format_currency($cart->shipping_fee) }})
                </x-bs::group>

                <x-bs::group label="{{ __('Payment') }}" inline>
                    @isset($paymentMethod){{ __("eshop::payment.$paymentMethod->name") ?? '' }}@endisset ({{ format_currency($cart->payment_fee) }})
                    @isset($cc)
                        <div class="d-flex gap-2 small fw-500">
                            {{ strtoupper($cc->network) }} * {{ $cc->last4 }} ({{ $cc->exp_month }}/{{ $cc->exp_year }})
                        </div>
                    @endisset
                </x-bs::group>

                @if(eshop('auto_payments'))
                    <x-bs::group label="Πληρώθηκε" inline>
                        @if($payment)
                            <div wire:key="order-paid">
                                {{ $payment->created_at?->format('d/m/Y H:i') }}
                                <a href="#" wire:click.prevent="markAsUnpaid" title="Διαγραφή πληρωμής"><em class="fas fa-times-circle text-secondary"></em></a>
                            </div>
                        @else
                            <div wire:key="order-unpaid">
                                <a wire:key="order-unpaid" href="#" wire:click.prevent="markAsPaid">{{ __("Mark as paid") }}</a>
                            </div>
                        @endif
                    </x-bs::group>
                @endif

                <x-bs::group label="{{ __('Weight') }}" inline>
                    {{ format_weight($cart->parcel_weight) }}
                </x-bs::group>

                <x-bs::group label="{{ __('Total quantity') }}" inline>
                    {{ format_number($cart->total_quantity) }}
                </x-bs::group>

                <x-bs::group label="{{ __('Subtotal') }}" inline>
                    {{ format_currency($cart->total - $cart->total_fees) }}
                </x-bs::group>

                <x-bs::group label="{{ __('Fees') }}" inline>
                    {{ format_currency($cart->total_fees) }}
                </x-bs::group>

                <x-bs::group label="{{ __('Total') }}" inline class="fw-bold">
                    <div class="col d-flex justify-content-between">
                        <span>{{ format_currency($cart->total) }}</span>
                        <span class="text-teal-500"><em class="fas fa-check-circle"></em> {{ format_currency($profit) }}</span>
                    </div>
                </x-bs::group>
            </div>

        </x-bs::navbar.collapse>
    </x-bs::navbar>

    @include('eshop::dashboard.cart.partials.show.cart-overview-modal')
</div>