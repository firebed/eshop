<div class="card shadow-sm">
    <div class="card-body">
        <div class="fs-5 mb-3">{{ __("Inventory") }}</div>
        <div class="row row-cols-4 g-3 mb-3">
            <x-bs::input.group for="sku" label="{{ __('SKU') }}" class="col">
                <x-bs::input.text wire:model.defer="product.sku" id="sku" error="product.sku"/>
            </x-bs::input.group>

            <x-bs::input.group for="barcode" label="{{ __('Barcode') }}" class="col">
                <x-bs::input.text wire:model.defer="product.barcode" id="barcode" error="product.barcode"/>
            </x-bs::input.group>

            <x-bs::input.group for="location" label="{{ __('Location') }}" class="col">
                <x-bs::input.text wire:model.defer="product.location" id="location" error="product.location"/>
            </x-bs::input.group>

            <x-bs::input.group for="stock" label="{{ __('Stock') }}" class="col">
                <x-bs::input.integer wire:model.defer="product.stock" id="stock" error="product.stock"/>
            </x-bs::input.group>

            <x-bs::input.group for="weight" label="{{ __('Weight') }}">
                <x-bs::input.weight wire:model.defer="product.weight" id="weight" error="product.weight"/>
            </x-bs::input.group>

            <x-bs::input.group for="unit-id" label="{{ __('Unit') }}">
                <x-bs::input.select wire:model.defer="product.unit_id" id="unit-id" error="product.unit_id">
                    <option value="" disabled>{{ __('Select unit') }}</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ __($unit->name) }}</option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.group>
        </div>
    </div>
</div>
