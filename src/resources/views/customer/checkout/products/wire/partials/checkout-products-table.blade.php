<x-bs::table id="products-table">
    <tbody>
    @foreach($order->products as $product)
        <tr wire:key="{{ $product->id }}">
            <td class="w-6r @if($loop->last) border-0 @endif">
                <div class="ratio ratio-1x1">
                    @if($product->image)
                        <img src="{{ $product->image->url('sm') }}" alt="{{ $product->tradeName }}" class="img-middle">
                    @endif
                </div>
            </td>
            <td @if($loop->last) class="border-0" @endif>
                <div class="d-grid justify-content-start">
                    @if($product->isVariant())
                        <a class="text-secondary small text-decoration-none" href="{{ route('customer.products.show', [app()->getLocale(), $product->category->slug, $product->parent->slug]) }}">{{ $product->parent->name }}</a>
                        <a href="{{ route('customer.products.show', [app()->getLocale(), $product->category->slug, $product->slug]) }}" class="text-dark text-decoration-none">{{ $product->sku }} {{ $product->optionValues }}</a>
                    @else
                        <a href="{{ route('customer.products.show', [app()->getLocale(), $product->category->slug, $product->slug]) }}" class="text-dark text-decoration-none">{{ $product->tradeName }}</a>
                    @endif
                </div>
            </td>
            <td class="align-middle w-5r  @if($loop->last) border-0 @endif">
                <label for="qty-{{ $product->id }}" class="visually-hidden"></label>
                <x-bs::input.integer wire:model="quantities.{{ $product->id }}" id="qty-{{ $product->id }}" placeholder="0"/>
            </td>
            <td class="w-5r text-end fw-500 align-middle @if($loop->last) border-0 @endif">{{ format_currency($product->netValue) }}</td>
            <td class="w-5r align-middle text-end @if($loop->last) border-0 @endif">
                <x-bs::button.link wire:click="deleteProduct({{ $product->id }})" wire:loading.attr="disabled" type="button" size="sm">
                    <em class="far fa-trash-alt"></em>
                </x-bs::button.link>
            </td>
        </tr>
    @endforeach
    </tbody>
</x-bs::table>
