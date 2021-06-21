<div class="d-grid gap-2">
    <div class="fw-500">{{ __("Inventory") }}</div>

    <div class="row">
        <x-bs::input.group for="barcode" label="{{ __('Barcode') }}" class="col">
            <x-bs::input.text wire:model.defer="variant.barcode" id="barcode" error="variant.barcode"/>
        </x-bs::input.group>

        <x-bs::input.group for="unit-id" label="{{ __('Unit') }}" class="col">
            <x-bs::input.select wire:model.defer="variant.unit_id" id="unit-id" error="variant.unit_id">
                <option value="" disabled>{{ __('Select unit') }}</option>
                @isset($units)
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ __($unit->name) }}</option>
                    @endforeach
                @endisset
            </x-bs::input.select>
        </x-bs::input.group>
    </div>

    <div class="row g-2">
        <x-bs::input.group for="location" label="{{ __('Location') }}" class="col">
            <x-bs::input.text wire:model.defer="variant.location" id="location" error="variant.location"/>
        </x-bs::input.group>

        <x-bs::input.group for="stock" label="{{ __('Stock') }}" class="col">
            <x-bs::input.integer wire:model.defer="variant.stock" id="stock" error="variant.stock"/>
        </x-bs::input.group>

        <x-bs::input.group for="weight" label="{{ __('Weight') }}" class="col">
            <x-bs::input.weight wire:model.defer="variant.weight" id="weight" error="variant.weight"/>
        </x-bs::input.group>
    </div>
</div>
