<div>
    @foreach($product->variantTypes as $type)
        <div class="fw-500 mb-3">{{ __("Do select") }} {{ $type->name }}</div>

        <div class="d-flex gap-3 mb-3">
            @foreach($uniqueOptions[$type->id] as $option)
                <button type="button" wire:click="select({{ $type->id }}, '{{ $option->pivot->slug }}')" class="btn @if(in_array($option->pivot->slug, $filters, true)) btn-primary @else btn-outline-primary @endif rounded-pill">{{ $option->pivot->value }}</button>
            @endforeach
        </div>
    @endforeach

    <form wire:submit.prevent="addToCart">
        <div class="row mb-3 g-4">
            @if($product->canDisplayStock())
                <div class="col-12 fw-500 text-success">@choice("eshop::product.availability", $product->available_stock, ['count' => format_number($product->available_stock)])</div>
            @endif

            <div class="col-12">
                <div class="h3 mb-0">{{ format_currency($product->netValue) }}</div>
            </div>

            <div class="col-12 col-sm d-grid gap-1">
                <div class="input-group">
                    <x-bs::button.light onclick="AutoNumeric.set('#quantity', AutoNumeric.getNumber('#quantity')-1)" class="border shadow-none" aria-label="{{ __('Decrease quantity') }}"><em class="fa fa-minus"></em></x-bs::button.light>
                    <label for="quantity" class="visually-hidden">{{ __("Quantity") }}</label>
                    <x-bs::input.integer id="quantity" wire:model.defer="quantity" placeholder="0" min="0" max="999" aria-label="{{ __('Quantity') }}" class="text-center"/>
                    <x-bs::button.light onclick="AutoNumeric.set('#quantity', AutoNumeric.getNumber('#quantity')+1)" class="border shadow-none" aria-label="{{ __('Increase quantity') }}"><em class="fa fa-plus"></em></x-bs::button.light>
                </div>

                <div id="errors" class="fw-500 small text-danger">
                    @error('quantity') {{ $message }} @enderror
                </div>
            </div>

            <div class="col-12 col-sm">
                <button type="submit" class="btn btn-green w-100">
                    <em class="fa fa-shopping-cart"></em>
                    <span class="ms-3">{{ __("Add to cart") }}</span>
                </button>
            </div>
        </div>
    </form>
</div>