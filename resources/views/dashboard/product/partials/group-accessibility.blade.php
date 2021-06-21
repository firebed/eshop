<x-bs::card>
    <x-bs::card.body class="d-grid gap-3">
        <div class="fs-5 mb-3">{{ __("Accessibility") }}</div>
        <x-bs::input.checkbox wire:model.defer="product.visible" id="visible">{{ __('Customers can view this group') }}</x-bs::input.checkbox>

        <x-bs::input.group for="variants-display" label="{{ __('Display variants as') }}">
            <x-bs::input.select wire:model.defer="product.variants_display" id="variants-display" error="variants_display">
                <option value="Grid">{{ __("Grid") }}</option>
                <option value="Buttons">{{ __("Buttons") }}</option>
                <option value="Dropdown">{{ __("Dropdown") }}</option>
            </x-bs::input.select>
        </x-bs::input.group>
    </x-bs::card.body>
</x-bs::card>