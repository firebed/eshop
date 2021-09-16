<x-bs::table class="table-hover" x-data="">
    <thead>
    <tr class="table-light">
        <td class="w-2r rounded-top">
            <x-bs::input.checkbox x-on:change="$refs.table.querySelectorAll('input').forEach(i => i.checked = $el.checked)" id="select-all"/>
        </td>
        <td class="w-5r rounded-top">{{ __('eshop::product.image') }}</td>
        <td class="rounded-top">{{ __('eshop::product.variant_options') }}</td>
    </tr>
    </thead>

    <tbody x-ref="table">
    @foreach($variants as $variant)
        <tr wire:key="variant-row-{{ $variant->id }}">
            <td>
                <x-bs::input.checkbox :checked="in_array($variant->id, old('bulk_ids', []))" class="variant" id="variant-{{ $variant->id }}" value="{{ $variant->id }}"/>
            </td>

            <td>
                <div class="ratio ratio-1x1 border rounded">
                    @if($src = $variant->image?->url('sm'))
                        <img class="w-auto h-auto mw-100 mh-100 rounded" src="{{ $src }}" alt="{{ $variant->sku }}">
                    @endif
                </div>
            </td>

            <td>
                <a href="{{ route('variants.edit', array_filter([$variant, 'search' => $search])) }}" class="d-grid gap-1 text-decoration-none">
                    <div class="text-dark">{{ $variant->options->pluck('pivot.value')->join(' / ') }}</div>
                    <small class="text-secondary lh-sm">{{ $variant->sku }}</small>
                    <small class="text-secondary lh-sm">{{ __('eshop::product.in_stock', ['stock' => format_number($variant->stock)]) }}</small>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>

    <caption>
        <x-eshop::pagination :paginator="$variants"/>
    </caption>
</x-bs::table>
