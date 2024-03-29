<x-bs::table id="variants-table" class="table-hover" x-data="">
    <thead>
    <tr class="table-light">
        <td class="w-2r rounded-top">
            <x-bs::input.checkbox x-on:change="$refs.table.querySelectorAll('input').forEach(i => i.checked = $el.checked)" id="select-all"/>
        </td>
        <td class="w-5r rounded-top">{{ __('eshop::product.image') }}</td>
        <td>{{ __('eshop::product.variant_options') }}</td>
        <td>{{ __("Price") }}</td>
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
                <div class="ratio ratio-1x1 border rounded">
                    @if($src = $variant->image?->url('sm'))
                        <img class="rounded img-middle" src="{{ $src }}" alt="{{ $variant->sku }}">
                    @else
                        <em class="fas fa-image fa-3x text-gray-300 img-middle"></em>
                    @endif
                </div>
            </td>

            <td>
                <a href="{{ route('variants.edit', array_filter([$variant, 'search' => $search])) }}" class="d-grid text-decoration-none">
                    <div class="text-dark">
                        @if($variant->recent)
                            <em class="fas fa-star text-warning fa-xs"></em>
                        @endif
                        {{ $variant->options->pluck('pivot.name')->join(' / ') }}
                    </div>

                    <div class="flex">
                        <small class="text-secondary lh-sm">{{ $variant->sku }}</small>

                        @if($variant->stock === 0)
                            <span class="badge bg-warning text-dark me-auto">({{ __("Sold out") }})</span>
                        @elseif($variant->stock < 0)
                            <span class="badge bg-danger me-auto">({{ __('eshop::product.in_stock', ['stock' => format_number($variant->stock)]) }})</span>
                        @else
                            <small class="text-secondary lh-sm">({{ __('eshop::product.in_stock', ['stock' => format_number($variant->stock)]) }})</small>
                        @endif
                    </div>


                    <div class="flex" style="font-size: 0.75rem">
                        @foreach($variant->channels as $channel)
                            <small class="rounded-pill fw-500 px-2" style="{{ $channel->style }}">{{ $channel->name }}</small>
                        @endforeach
                    </div>
                </a>
            </td>

            <td>
                <div>
                    <div>{{ format_currency($variant->net_value) }}</div>
                    @if($variant->isOnSale())
                        <div class="fw-bold text-danger">-{{ format_percent($variant->discount) }}</div>
                    @endif
                </div>
            </td>

            <td>
                <div class="d-flex gap-2 justify-content-end">
                    <em class="fas fa-eye {{ $variant->visible ? 'text-teal-500' : 'text-gray-400' }}"></em>

                    <div class="d-grid gap-1">
                        <em class="fas fa-shopping-cart {{ $variant->available ? 'text-teal-500' : 'text-gray-400' }}"></em>
                        @if($variant->available_gt !== "" && $variant->available_gt !== null)
                            <span class="border-top"></span>
                            <small class="fw-500 text-center lh-sm" style="font-size: .7rem">{{ $variant->available_gt }}</small>
                        @endif
                    </div>

                    <div class="d-grid gap-1">
                        <em class="fas fa-list-ol {{ $variant->display_stock ? 'text-teal-500' : 'text-gray-400' }}"></em>
                        @if($variant->display_stock_lt !== "" && $variant->display_stock_lt !== null)
                            <span class="border-top"></span>
                            <small class="fw-500 text-center lh-sm" style="font-size: .7rem">{{ $variant->display_stock_lt }}</small>
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
