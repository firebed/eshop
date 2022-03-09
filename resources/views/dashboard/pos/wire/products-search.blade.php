<div class="input-group position-relative" x-data="{ show: false }" x-on:click.outside="show = false">
    <input x-on:click="show = true" wire:model="search" type="search" class="form-control" placeholder="Κωδικός, όνομα, sku" aria-label="Αναζήτηση">

    <div x-cloak x-show="show" class="position-absolute bg-white border shadow-sm start-0 w-100 top-100 rounded vstack overflow-auto scrollbar" style="min-height: 100px; max-height: 300px; z-index: 2000">
        @foreach($products as $product)
            <a wire:key="result-{{ $product->id }}" href="#" x-on:click.prevent="Livewire.emit('addProduct', {{ $product->id }}); show = false" class="small text-decoration-none list-group-item-action text-dark px-3 py-2 d-flex justify-content-between gap-3">
                <div class="d-flex gap-3">
                    <div class="ratio ratio-1x1 w-3r">
                        @if($src = $product->image?->url('sm'))
                            <img src="{{ $src }}" alt="" class="img-top rounded">
                        @endif
                    </div>

                    <div class="vstack">
                        @if($product->isVariant())
                            <div class="fw-500 lh-sm">{{ $product->parent->name }}</div>
                            <div class="text-secondary lh-sm">{{ $product->option_values }}</div>
                        @else
                            <div class="fw-500">{{ $product->name }}</div>
                        @endif
                    </div>
                </div>

                <div class="fw-500">{{ format_currency($product->price) }}</div>
            </a>
        @endforeach
    </div>

    <button class="btn btn-outline-secondary" type="button"><em class="fas fa-search"></em></button>
</div>