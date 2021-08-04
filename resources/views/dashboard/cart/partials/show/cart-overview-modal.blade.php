<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showEditingModal">
        <x-bs::modal.header>{{ __('Cart details') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="row mb-3">
                <x-bs::input.group for="chart-shipping-method" label="{{ __('Shipping') }}" class="col-7">
                    <x-bs::input.select wire:model.defer="cart.shipping_method_id" id="cart-shipping-method" error="cart.shipping_method_id">
                        @foreach($shippingMethods as $method)
                            <option value="{{ $method->id }}">{{ __("eshop::shipping.$method->name") }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.group>

                <x-bs::input.group for="cart-shipping-fee" label="{{ __('Shipping fee') }}" class="col">
                    <x-bs::input.money wire:model.defer="cart.shipping_fee" id="cart-shipping-fee" error="cart.shipping_fee"/>
                </x-bs::input.group>
            </div>

            <div class="row mb-3">
                <x-bs::input.group for="cart-payment-method" label="{{ __('Payment') }}" class="col-7">
                    <x-bs::input.select wire:model.defer="cart.payment_method_id" id="cart-payment-method" error="cart.payment_method_id">
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ __("eshop::payment.$method->name") }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.group>

                <x-bs::input.group for="cart-payment-fee" label="{{ __('Payment fee') }}" class="col">
                    <x-bs::input.money wire:model.defer="cart.payment_fee" id="cart-payment-fee" error="cart.payment_fee"/>
                </x-bs::input.group>
            </div>

            <div class="row">
                <x-bs::input.group for="cart-document-type" label="{{ __('Document') }}" class="col">
                    <x-bs::input.select wire:model.defer="cart.document_type" id="cart-document" error="cart.document_type">
                        <option value="{{ \Eshop\Models\Cart\DocumentType::RECEIPT }}">{{ __('Receipt') }}</option>
                        <option value="{{ \Eshop\Models\Cart\DocumentType::INVOICE }}">{{ __('Invoice') }}</option>
                    </x-bs::input.select>
                </x-bs::input.group>

                <x-bs::input.group for="cart-channel" label="{{ __('Channel') }}" class="col">
                    <x-bs::input.select wire:model.defer="cart.channel" id="cart-channel" error="cart.channel">
                        <option value="" disabled>{{ __("Select channel") }}</option>
                        @foreach(\Eshop\Models\Cart\CartChannel::all() as $channel)
                            <option value="{{ $channel }}">{{ __("eshop::cart.channel.$channel") }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.group>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
