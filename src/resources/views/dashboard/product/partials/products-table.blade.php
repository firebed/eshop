<x-bs::table>
    <thead>
    <tr class="table-light text-nowrap">
        <x-bs::table.heading class="w-1r rounded-top">
            <x-bs::input.checkbox wire:model="selectAll" id="select-all"/>
        </x-bs::table.heading>
        <x-bs::table.heading class="w-6r">{{ __("Graphics") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">{{ __("Product") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('SKU')" :direction="$sortField === 'SKU' ? $sortDirection : null">{{ __("SKU") }}</x-bs::table.heading>
        <x-bs::table.heading>{{ __("Manufacturer") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('stock')" :direction="$sortField === 'stock' ? $sortDirection : null">{{ __("Stock") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('variants_count')" :direction="$sortField === 'variants_count' ? $sortDirection : null">{{ __("Variants") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('price')" :direction="$sortField === 'price' ? $sortDirection : null">{{ __("Price") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('created_at')" :direction="$sortField === 'created_at' ? $sortDirection : null" class="rounded-top">{{ __("Created at") }}</x-bs::table.heading>
    </tr>
    </thead>

    <tbody>
    @forelse($products as $product)
        <tr wire:key="row-{{ $product->id }}" wire:loading.class.delay="opacity-50" wire:target="sortBy, category, manufacturer, name">
            <td>
                <x-bs::input.checkbox wire:model="selected" id="product-{{ $product->id}}" value="{{ $product->id }}"/>
            </td>
            <td>
                <div class="ratio ratio-1x1">
                    @if($product->image && $src = $product->image->url('sm') )
                        <img class="w-auto h-auto mh-100 mw-100 rounded" src="{{ $src }}" alt="{{ $product->name }}">
                    @endif
                </div>
            </td>
            <td>
                <a href="{{ route('products.edit', $product) }}" class="d-flex flex-column text-decoration-none">
                    <span>{{ $product->name }}</span>
                    <small class="text-secondary">{{ $product->category->name }}</small>
                </a>
            </td>
            <td>{{ $product->sku }}</td>
            <td>{{ $product->manufacturer->name ?? '' }}</td>
            <td>@if($product->has_variants) {{ format_number($product->variants_sum_stock) }} @else {{ format_number($product->stock) }} @endif</td>
            <td>
                @if($product->has_variants)
                    <a href="{{ route('products.variants.index', $product) }}" class="btn btn-teal">@choice("eshop::product.variants_count", $product->variants_count, ['count' => $product->variants_count])</a>
                @endif
            </td>
            <td>
                @if($product->has_variants)
                    @if($product->variants_min_price === $product->variants_max_price)
                        {{ format_currency($product->variants_min_price) }}
                    @else($product->has_variants)
                        {{ format_currency($product->variants_min_price) }} - {{ format_currency($product->variants_max_price) }}
                    @endif
                @else
                    {{ format_currency($product->price) }}
                @endif
            </td>
            <td>{{ $product->created_at->format('d/m/y') }}</td>
        </tr>
    @empty
        <tr wire:key="no-records-found">
            <td colspan="8" class="text-center py-4 fst-italic text-secondary">{{ __("No records found") }}</td>
        </tr>
    @endforelse
    </tbody>

    <caption>
        <x-eshop::pagination :paginator="$products"/>
    </caption>
</x-bs::table>
