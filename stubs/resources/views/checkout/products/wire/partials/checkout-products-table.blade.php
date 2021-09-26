@foreach($order->products as $product)
    <div class="row row-cols-1 row-cols-sm-2 g-3" wire:key="{{ $product->id }}">
        <div class="col d-flex gap-3">
            <div class="@if($loop->last) border-0 @endif">
                <div class="ratio ratio-1x1 w-5r">
                    @if($product->image)
                        <img src="{{ $product->image->url('sm') }}" alt="{{ $product->trademark }}" class="img-top rounded">
                    @endif
                </div>
            </div>

            <div @if($loop->last) class="border-0" @endif>
                <div class="d-grid justify-content-start">
                    @if($product->isVariant())
                        <a class="text-secondary small text-decoration-none" href="{{ route('products.show', [app()->getLocale(), $product->category->slug, $product->parent->slug]) }}">{{ $product->parent->name }}</a>
                        <a href="{{ route('products.show', [app()->getLocale(), $product->category->slug, $product->slug]) }}" class="text-dark text-decoration-none">{{ $product->optionValues }}</a>
                    @else
                        <a href="{{ route('products.show', [app()->getLocale(), $product->category->slug, $product->slug]) }}" class="text-dark text-decoration-none">{{ $product->trademark }}</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col d-flex gap-3 align-items-baseline">
            <div class="col">
                <label for="qty-{{ $product->id }}" class="visually-hidden"></label>
                <x-bs::input.integer wire:model="quantities.{{ $product->id }}" id="qty-{{ $product->id }}" placeholder="0"/>

                @unless($product->canBeBought($quantities[$product->id]))
                    <div class="fw-500 text-danger small mt-2">Διαθέσιμα: {{ $product->available_stock }}</div>
                @endunless
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
