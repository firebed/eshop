<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showEditingModal">
        <x-bs::modal.header>{{ __("Edit product") }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="row row-cols-3">
                <x-bs::input.group for="edit-quantity" label="{{ __('Quantity') }}" class="col">
                    <x-bs::input.decimal wire:model.defer="model.quantity" min="0" id="edit-quantity" error="model.quantity"/>
                </x-bs::input.group>

                <x-bs::input.group for="edit-price" label="{{ __('Price') }}" class="col">
                    <x-bs::input.money wire:model.defer="model.price" id="edit-price" error="model.price"/>
                </x-bs::input.group>

                <x-bs::input.group for="edit-discount" label="{{ __('Discount') }}" class="col">
                    <x-bs::input.percentage wire:model.defer="model.discount" min="0" max="100" id="edit-discount" error="model.discount"/>
                </x-bs::input.group>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
