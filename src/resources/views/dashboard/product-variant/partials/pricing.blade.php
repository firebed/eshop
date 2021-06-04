<div class="d-grid gap-2">
    <div class="fw-500">{{ __("Pricing") }}</div>
    <div class="row gx-2">
        <x-bs::input.group for="selling-price" label="{{ __('Selling price') }}" class="col">
            <x-bs::input.money wire:model.defer="variant.price" id="selling-price" error="variant.price"/>
        </x-bs::input.group>

        <x-bs::input.group for="compare-price" label="{{ __('Compare at price') }}" class="col">
            <x-bs::input.money wire:model.defer="variant.compare_price" id="compare-price" error="variant.compare_price"/>
        </x-bs::input.group>
    </div>

    <div class="row gx-2">
        <x-bs::input.group for="vat" label="{{ __('Vat') }}" class="col">
            <x-bs::input.select wire:model.defer="product.vat" id="vat" error="product.vat">
                <option value="" disabled>{{ __('Select vat') }}</option>
                @isset($vats)
                    @foreach($vats as $vat)
                        <option value="{{ $vat->regime }}">{{ __($vat->name) }} ({{ format_percent($vat->regime) }})</option>
                    @endforeach
                @endisset
            </x-bs::input.select>
        </x-bs::input.group>

        <x-bs::input.group for="discount" label="{{ __('Discount') }}" class="col">
            <x-bs::input.percentage wire:model.defer="variant.discount" error="variant.discount"/>
        </x-bs::input.group>
    </div>
</div>
