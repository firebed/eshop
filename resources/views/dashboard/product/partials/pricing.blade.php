<div class="card shadow-sm">
    <div class="card-body">
        <div class="fs-5 mb-3">{{ __("Pricing") }}</div>
        <div class="row g-3">
            <x-bs::input.group for="selling-price" label="{{ __('Selling price') }}" class="col-4">
                <x-bs::input.money wire:model.defer="product.price" id="selling-price" error="product.price"/>
            </x-bs::input.group>

            <x-bs::input.group for="compare-price" label="{{ __('Compare price') }}" class="col-4">
                <x-bs::input.money wire:model.defer="product.compare_price" id="compare-price" error="product.compare_price"/>
            </x-bs::input.group>

            <x-bs::input.group for="discount" label="{{ __('Discount') }}" class="col-4">
                <x-bs::input.percentage wire:model.defer="product.discount" id="discount" error="product.discount"/>
            </x-bs::input.group>

            <x-bs::input.group for="vat" label="{{ __('Vat') }}" class="col-12">
                <x-bs::input.select wire:model.defer="product.vat" id="vat" error="product.vat">
                    <option value="" disabled>{{ __('Select vat') }}</option>
                    @foreach($vats as $vat)
                        <option value="{{ $vat->regime }}">{{ __($vat->name) }} ({{ format_percent($vat->regime) }})</option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.group>
        </div>
    </div>
</div>
