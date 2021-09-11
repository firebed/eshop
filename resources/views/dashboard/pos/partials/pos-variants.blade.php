@foreach($variants as $variant)
    <div class="col" wire:key="variant-{{ $variant->id }}" x-data>
        <x-bs::card class="h-100">
            <a href="#" wire:click.prevent="$emit('addProduct', {{ $variant->id }})" class="vstack gap-2 p-2 text-decoration-none text-dark list-group-item-action h-100 rounded">
                <div class="ratio ratio-16x9">
                    @if($variant->image && $src = $variant->image->url('sm'))
                        <img src="{{ $src }}" alt="{{ $variant->name }}" class="img-middle rounded">
                    @else
                        <em class="fas fa-image fa-4x img-middle text-gray-500"></em>
                    @endif
                </div>

                <div class="vstack">
                    <small class="fw-500">{{ $variant->parent->name }}</small>
                    <small class="fw-500 text-primary">{{ $variant->option_values }}</small>
                </div>

                <div class="vstack gap-1 mt-auto small justify-content-end">
                    <span class="text-secondary">{{ $variant->sku }}</span>

                    <div class="d-flex justify-content-between align-items-center gap-1">
                            <span class="d-flex gap-2 align-items-center">
                                <em class="fas fa-boxes text-teal-600"></em>
                                <span class="fw-normal text-secondary">{{ format_number($variant->stock) }}</span>
                            </span>

                        <span class="fw-500">{{ format_currency($variant->net_value) }}</span>
                    </div>
                </div>
            </a>
        </x-bs::card>
    </div>
@endforeach