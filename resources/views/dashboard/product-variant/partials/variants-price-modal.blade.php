<form wire:submit.prevent="setPrice">
    <x-bs::modal wire:model.defer="showPriceModal">
        <x-bs::modal.header>{{ __("Change price") }}</x-bs::modal.header>
        <x-bs::modal.body>
            <x-bs::input.group for="global-discount" label="{{ __('Change price') }}" inline>
                <x-bs::input.money wire:model.defer="global_price" id="global-price" min="0" autofocus/>
            </x-bs::input.group>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit" class="ms-2 px-3">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
