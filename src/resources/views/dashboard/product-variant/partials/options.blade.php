<div class="d-grid gap-2">
    <div class="fw-500">{{ __("Primary") }}</div>
    <div class="d-grid gap-2">
        <x-bs::input.group for="sku" label="{{ __('SKU') }}" inline>
            <x-bs::input.text wire:model.defer="variant.sku" id="sku" error="variant.sku"/>
        </x-bs::input.group>

        @isset($variantTypes)
            @foreach($variantTypes as $variantType)
                <x-bs::input.group for="vt-{{ $variantType->id }}" label="{{ __($variantType->name) }}" inline>
                    <x-bs::input.text wire:model="variant_values.{{ $variantType->id }}" id="vt-{{ $variantType->id }}" error="variant_values.{{ $variantType->id }}"/>
                </x-bs::input.group>
            @endforeach
        @endisset

        <x-bs::input.group for="slug" label="{{ __('Slug') }}" inline>
            <x-bs::input.text wire:model.defer="variant.slug" id="slug" error="variant.slug"/>
        </x-bs::input.group>

        <x-bs::input.group for="image" label="{{ __('Image') }}" inline>
            <x-bs::input.image wire:model.defer="image" id="image" error="image"/>
        </x-bs::input.group>
    </div>
</div>
