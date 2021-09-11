@foreach($products as $product)
    <div class="col" wire:key="product-{{ $product->id }}">
        <x-bs::card class="h-100">
            <a @if($product->has_variants) wire:click.prevent="loadVariants({{ $product->id }})" @else wire:click.prevent="$emit('addProduct', {{ $product->id }})" @endif href="#" class="vstack gap-2 p-2 text-decoration-none text-dark list-group-item-action h-100 rounded">
                <div class="ratio ratio-16x9">
                    @if($product->image && $src = $product->image->url('sm'))
                        <img src="{{ $src }}" alt="{{ $product->name }}" class="img-middle rounded">
                    @else
                        <em class="fas fa-image fa-4x img-middle text-gray-500"></em>
                    @endif
                </div>

                <small class="fw-500">{{ $product->name }}</small>

                <div class="vstack gap-1 small justify-content-end">
                    <span class="text-secondary">{{ $product->sku }}</span>

                    <div class="d-flex align-items-center justify-content-between position-relative">
                        @if($product->has_variants)
                            <span class="color-wheel text-secondary">{{ $product->variants_count }}</span>

                            <span class="fw-500">
                                @if($product->variants_min_net_value !== $product->variants_max_net_value)
                                    {{ format_currency($product->variants_min_net_value) }} - {{ format_currency($product->variants_max_net_value) }}
                                @else
                                    {{ format_currency($product->variants_min_net_value) }}
                                @endif
                                </span>
                        @else
                            <span class="d-flex gap-2 align-items-center">
                                    <em class="fas fa-boxes text-secondary"></em>
                                    <span class="fw-normal text-secondary">{{ $product->variants_count }}</span>
                                </span>

                            <span class="fw-500">{{ format_currency($product->net_value) }}</span>
                        @endif
                    </div>
                </div>
            </a>
        </x-bs::card>
    </div>
@endforeach