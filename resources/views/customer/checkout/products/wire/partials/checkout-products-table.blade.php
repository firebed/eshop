@foreach($order->products as $product)
    <div class="row row-cols-1 row-cols-sm-2 g-3 @unless($loop->last) border-bottom mb-4 pb-4 @endunless" wire:key="{{ $product->id }}">
        <div class="col d-flex gap-3">
            <div class="ratio ratio-1x1 w-5r">
                @if($product->image)
                    <img src="{{ $product->image->url('sm') }}" alt="{{ $product->trademark }}" class="img-top rounded">
                @endif
            </div>

            <div class="vstack justify-content-start">
                @if($product->isVariant())
                    <a class="text-secondary small text-decoration-none" href="{{ route('products.show', [app()->getLocale(), $product->category->slug, $product->parent->slug]) }}">{{ $product->parent->name }}</a>
                    <a href="{{ route('products.show', [app()->getLocale(), $product->category->slug, $product->slug]) }}" class="text-dark text-decoration-none">{{ $product->optionValues }}</a>
                @else
                    <a href="{{ route('products.show', [app()->getLocale(), $product->category->slug, $product->slug]) }}" class="text-dark text-decoration-none">{{ $product->trademark }}</a>
                @endif
            </div>
        </div>

        <div class="col d-flex gap-3 align-items-baseline">
            <div class="col">
                <label for="qty-{{ $product->id }}" class="visually-hidden"></label>
                {{--                <x-bs::input.integer wire:model="quantities.{{ $product->id }}" id="qty-{{ $product->id }}" placeholder="0"/>--}}
                <input x-data
                       type="number"
                       step="1"
                       pattern="\d+"
                       x-on:focus="$el.select()"
                       x-on:keydown="if ($event.key === '.' || $event.key === ',') $event.preventDefault();"
                       autocomplete="off"
                       wire:model="quantities.{{ $product->id }}"
                       id="qty-{{ $product->id }}"
                       placeholder="{{ __("Quantity") }}"
                       class="form-control @error("quantities.$product->id") is-invalid @enderror"
                       min="0"
                       max="1000">

                @error("quantities.$product->id")
                    <div class="invalid-feedback fw-500 mt-1">{{ $message }}</div>
                @else
                    @if(isset($quantities[$product->id]))
                        @if($product->isAccessible())
                            @unless($product->canBeBought($quantities[$product->id]))
                                <div class="fw-500 text-danger small mt-1">{{ __("In stock") }}: {{ max(0, $product->available_stock) }}</div>
                            @endunless
                        @else
                            <div class="fw-500 text-danger small mt-1">{{ __("Out of stock") }}</div>
                        @endif
                    @endif
                @enderror
            </div>

            <div class="col text-end fw-500">{{ format_currency($product->netValue) }}</div>

            <div class="text-end">
                <x-bs::button.link wire:click="deleteProduct({{ $product->id }})" wire:loading.attr="disabled" type="button" size="sm">
                    <em class="far fa-trash-alt"></em>
                </x-bs::button.link>
            </div>
        </div>
    </div>
@endforeach
