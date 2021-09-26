<x-bs::table id="products-table">
    <tbody>
    @foreach($order->products as $product)
        <tr wire:key="{{ $product->id }}">
            <td class="@if($loop->last) border-0 @endif">
                <div class="ratio ratio-1x1 w-6r">
                    @if($product->image)
                        <img src="{{ $product->image->url('sm') }}" alt="{{ $product->trademark }}" class="img-top rounded">
                    @endif
                </div>
            </td>
            <td @if($loop->last) class="border-0" @endif>
                <div class="d-grid justify-content-start text-nowrap">
                    @if($product->isVariant())
                        <a class="text-secondary small text-decoration-none" href="{{ route('products.show', [app()->getLocale(), $product->category->slug, $product->parent->slug]) }}">{{ $product->parent->name }}</a>
                        <a href="{{ route('products.show', [app()->getLocale(), $product->category->slug, $product->slug]) }}" class="text-dark text-decoration-none">{{ $product->optionValues }}</a>
                    @else
                        <a href="{{ route('products.show', [app()->getLocale(), $product->category->slug, $product->slug]) }}" class="text-dark text-decoration-none">{{ $product->trademark }}</a>
                    @endif
                </div>
            </td>
            <td class="w-10r align-baseline @if($loop->last) border-0 @endif">
                <label for="qty-{{ $product->id }}" class="visually-hidden"></label>
                <x-bs::input.integer wire:model="quantities.{{ $product->id }}" id="qty-{{ $product->id }}" placeholder="0"/>

                @unless($product->canBeBought($quantities[$product->id]))
                    <div class="fw-500 text-danger small mt-2">Διαθέσιμα: {{ $product->available_stock }}</div>
                @endunless
            </td>
            <td class="w-5r text-end fw-500 align-baseline @if($loop->last) border-0 @endif">{{ format_currency($product->netValue) }}</td>
            <td class="w-5r text-end align-baseline @if($loop->last) border-0 @endif">
                <x-bs::button.link wire:click="deleteProduct({{ $product->id }})" wire:loading.attr="disabled" type="button" size="sm">
                    <em class="far fa-trash-alt"></em>
                </x-bs::button.link>
            </td>
        </tr>
    @endforeach
    </tbody>
</x-bs::table>
