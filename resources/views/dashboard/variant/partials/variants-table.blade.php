<x-bs::table class="table-hover" x-data="">
    <thead>
    <tr class="table-light">
        <td class="w-2r rounded-top">
            <x-bs::input.checkbox x-on:change="$refs.table.querySelectorAll('input').forEach(i => i.checked = $el.checked)" id="select-all"/>
        </td>
        <td class="w-5r rounded-top">{{ __('eshop::product.image') }}</td>
        <td>{{ __('eshop::product.variant_options') }}</td>
        <td class="rounded-top">&nbsp;</td>
    </tr>
    </thead>

    <tbody x-ref="table">
    @foreach($variants as $variant)
        <tr wire:key="variant-row-{{ $variant->id }}">
            <td>
                <x-bs::input.checkbox :checked="in_array($variant->id, old('bulk_ids', []))" class="variant" id="variant-{{ $variant->id }}" value="{{ $variant->id }}"/>
            </td>

            <td>
                <div class="ratio ratio-1x1 border rounded bg-gray-100">
                    @if($src = $variant->image?->url('sm'))
                        <img class="w-auto h-auto mw-100 mh-100 rounded" src="{{ $src }}" alt="{{ $variant->sku }}">
                    @else
                        <em class="fas fa-image fa-3x text-gray-300 img-middle"></em>
                    @endif
                </div>
            </td>

            <td>
                <a href="{{ route('variants.edit', array_filter([$variant, 'search' => $search])) }}" class="d-grid gap-1 text-decoration-none">
                    <div class="text-dark d-flex gap-3 align-items-center">{{ $variant->optionValues(' / ') }}@if($variant->recent) <span class="badge bg-danger">New</span> @endif</div>
                    <small class="text-secondary lh-sm">{{ $variant->sku }}</small>
                    <small class="text-secondary lh-sm">{{ __('eshop::product.in_stock', ['stock' => format_number($variant->stock)]) }}</small>
                </a>
            </td>

            <td>
                <div class="d-flex gap-2 justify-content-end">
                    <em class="fas fa-eye {{ $variant->visible ? 'text-teal-500' : 'text-gray-400' }}"></em>
                    <div class="d-grid gap-1">
                        <em class="fas fa-shopping-cart {{ $variant->available ? 'text-teal-500' : 'text-gray-400' }}"></em>
                        @if($variant->available_gt !== "" && $variant->display_stock_lt !== null) 
                            <small class="fw-500 text-center lh-sm bg-secondary rounded-pill text-light px-1" style="font-size: .7rem">{{ $variant->available_gt }}</small> 
                        @endif
                    </div>

                    <div class="d-grid gap-1">
                        <em class="fas fa-list-ol {{ $variant->display_stock ? 'text-teal-500' : 'text-gray-400' }}"></em>
                        @if($variant->display_stock_lt !== "" && $variant->display_stock_lt !== null) 
                            <small class="fw-500 text-center lh-sm bg-secondary rounded-pill text-light px-1" style="font-size: .7rem">{{ $variant->display_stock_lt }}</small> 
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>

    <caption>
        <x-eshop::pagination :paginator="$variants"/>
    </caption>
</x-bs::table>
