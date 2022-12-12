<div>
    <x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap">
        <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100 py-0">Σύνοψη καλαθιού</x-bs::navbar.brand>

        <x-bs::navbar.toggler target="cart-info"/>

        <x-bs::navbar.collapse id="cart-info">
            <div class="d-grid flex-grow-1 gap-1 mt-3">
                <x-bs::group label="Έγγραφο" inline>
                    <x-bs::badge :type="$cart->document_type === 'Invoice' ? 'danger' : 'blue'">{{ __($cart->document_type) }}</x-bs::badge>
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

                <x-bs::group label="{{ __('Subtotal') }}" inline>
                    {{ format_currency($cart->total - $cart->total_fees) }}
                </x-bs::group>

                <x-bs::group label="{{ __('Fees') }}" inline>
                    {{ format_currency($cart->total_fees) }}
                </x-bs::group>

                <x-bs::group label="{{ __('Total') }}" inline class="fw-bold">
                    {{ format_currency($cart->total) }}
                </x-bs::group>
                
                @if(eshop('auto_payments'))
                    <x-bs::group label="Πληρώθηκε" inline>
                        @if($payment)
                            <div wire:key="order-paid">
                                <span title="Πληρωμή: {{ format_currency($payment->total) }}. @if($payment->fees > 0) Προμήθεια {{ format_currency($payment->fees) }} @endif">{{ $payment->created_at?->format('d/m/Y H:i') }}</span>
                                <a href="#" wire:click.prevent="markAsUnpaid" title="Διαγραφή πληρωμής"><em class="fas fa-times-circle text-secondary"></em></a>
                            </div>
                        @else
                            <div wire:key="order-unpaid">
                                <a wire:key="order-unpaid" href="#" wire:click.prevent="markAsPaid">{{ __("Mark as paid") }}</a>
                            </div>
                        @endif
                    </x-bs::group>
                @endif
                
                <x-bs::group label="Κέρδος" inline>{{ format_currency($profit) }}</x-bs::group>
                
                <div class="row border-top pt-2 mt-2">
                    <a href="#" class="col text-decoration-none" wire:click="edit">{{ __("Edit") }}</a>
                </div>
            </div>

        </x-bs::navbar.collapse>
    </x-bs::navbar>

    @include('eshop::dashboard.cart.partials.show.cart-overview-modal')
</div>