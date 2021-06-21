<form wire:submit.prevent="setDiscount">
    <x-bs::modal wire:model.defer="showDiscountModal">
        <x-bs::modal.header>{{ __("Set discount") }}</x-bs::modal.header>
        <x-bs::modal.body>
            <x-bs::input.group for="global-discount" label="{{ __('Global discount') }}" inline>
                <x-bs::input.percentage wire:model.defer="global_discount" id="global-discount" min="0" max="100" autofocus/>
            </x-bs::input.group>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit" class="ms-2 px-3">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
