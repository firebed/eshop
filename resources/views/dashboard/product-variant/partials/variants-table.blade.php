<x-bs::table class="table-hover">
    <thead>
    <tr class="table-light">
        <td class="w-2r rounded-top">
            <x-bs::input.checkbox wire:model="selectAll" id="select-all"/>
        </td>
        <td class="w-2r"><em class="fa fa-eye text-secondary"></em></td>
        <td class="w-2r"><em class="fa fa-store text-secondary"></em></td>
        <td class="w-2r"><em class="fa fa-tag text-secondary"></em></td>
        <td>{{ __("Image") }}</td>
        <td>SKU</td>
        @foreach($variantTypes as $variantType)
            <td>{{ $variantType->name }}</td>
        @endforeach
        <td>{{ __("Stock") }}</td>
        <td>{{ __("Price") }}</td>
        <td>{{ __("Discount") }}</td>
        <td>{{ __("Created at") }}</td>
        <td class="rounded-top"></td>
    </tr>
    </thead>

    <tbody>
    @foreach($variants as $variant)
        <tr wire:key="row-{{ $variant->id }}">
            <td>
                <x-bs::input.checkbox wire:model="selected" id="variant-{{ $variant->id }}" value="{{ $variant->id }}"/>
            </td>
            <td><em class="fa fa-{{ $variant->visible ? 'check-circle text-teal-500' : 'minus-circle text-warning' }}"></em></td>
            <td>
                <div class="d-grid gap-1 pt-1">
                    <em class="fa fa-{{ $variant->available ? 'check-circle text-teal-500' : 'minus-circle text-warning' }}"></em>
                    <small>{{ $variant->available_gt }}</small>
                </div>
            </td>
            <td>
                <div class="d-grid gap-1 pt-1">
                    <em class="fa fa-{{ $variant->display_stock ? 'check-circle text-teal-500' : 'minus-circle text-warning' }}"></em>
                    <small>{{ $variant->display_stock_lt }}</small>
                </div>
            </td>
            <td style="width: 80px">
                <div class="ratio ratio-1x1">
                    @if($variant->image && $src = $variant->image->url('sm'))
                        <img class="w-auto h-auto mw-100 mh-100 rounded" src="{{ $src }}" alt="{{ $variant->sku }}">
                    @endif
                </div>
            </td>
            <td>{{ $variant->sku }}</td>

            @foreach($variantTypes as $variantType)
                <td>{{ $variant->options->find($variantType->id)->pivot->value ?? '' }}</td>
            @endforeach

            <td>{{ format_number($variant->stock) }}</td>
            <td>{{ format_currency($variant->price) }}</td>
            <td>{{ format_percent($variant->discount) }}</td>
            <td>{{ $variant->created_at->format('d/m/y') }}</td>
            <td class="text-end">
                <x-bs::button.haze wire:click="edit({{ $variant->id }})" wire:loading.attr="disabled" wire:target="edit({{ $variant->id }})" size="sm">{{ __('Edit') }}</x-bs::button.haze>
                @if(variantRouteExists())
                    <a href="{{ variantRoute($variant, $product) }}" class="btn btn-sm btn-haze shadow-sm"><em class="fas fa-search text-secondary"></em></a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>

    <caption class="px-2">{{ $variants->count() }} {{ __("variants") }}</caption>
</x-bs::table>
