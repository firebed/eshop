<form wire:submit.prevent="saveDiscount">
    <x-bs::modal wire:model.defer="showDiscountModal">
        <x-bs::modal.header>{{ __('Set discount') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <x-bs::input.group label="Discount" for="global-discount" inline>
                <x-bs::input.percentage wire:model.defer="global_discount" min="0" max="100" id="global-discount" error="global_discount" autofocus/>
            </x-bs::input.group>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
