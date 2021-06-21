<x-bs::modal wire:model.defer="showVoucherModal">
    <form wire:submit.prevent="saveVoucher">
        <x-bs::modal.header>{{ __('Voucher code') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <x-bs::input.group for="cart-voucher-alone" label="{{ __('Voucher') }}" inline>
                <x-bs::input.text wire:model.defer="voucher" error="voucher" id="cart-voucher-alone" autofocus/>
            </x-bs::input.group>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </form>
</x-bs::modal>
