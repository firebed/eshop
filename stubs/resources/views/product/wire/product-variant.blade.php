<div class="col">
    <div class="card h-100 position-relative">
        <div class="card-body d-grid gap-3">
            <a class="text-decoration-none text-dark d-grid gap-2" href="{{ productRoute($variant, $category) }}">
                @if($variant->image)
                    <div class="ratio ratio-1x1">
                        <img src="{{ $variant->image->url('sm') }}" alt="{{ $variant->trademark }}" class="rounded img-middle">
                    </div>
                @endif

                <div class="d-grid">
                    <div class="fw-500">{{ $variant->option_values }}@can('Manage products') <span class="text-secondary">({{ format_number($variant->stock) }})</span> @endcan</div>

                    @if($variant->sku !== null && $variant->variant_values !== $variant->sku)
                        <small class="text-secondary">{{ __('Code') }}: {{ $variant->sku }}</small>
                    @endif
                </div>

                @if($variant->canDisplayStock())
                    <div class="fw-500 small text-success">@choice("eshop::product.availability", $variant->available_stock, ['count' => format_number($variant->available_stock)])</div>
                @endif

                <div class="d-flex align-items-baseline mt-auto">
                    <div class="fs-5 fw-500">{{ format_currency($variant->netValue) }}</div>
                    @if($variant->discount > 0)
                        <span class="text-secondary ms-3 text-decoration-line-through">{{ format_currency($variant->price) }}</span>
                    @endif
                </div>
            </a>

            <div class="d-grid mt-auto">
                @if($variant->canBeBought())
                    <form wire:submit.prevent="addToCart" class="d-flex align-items-center gap-1">
                        <input wire:model.defer="quantity" placeholder="0" name="quantity"
                               type="number" min="1" step="1" class="form-control text-center"
                               x-on:keydown="if($event.key === '.') $event.preventDefault()"
                               value="1"
                               title="{{ __("Quantity") }}">

                        <button type="submit" wire:loading.attr="disabled" class="btn btn-green text-nowrap col-8">
                            <em wire:loading.remove wire:target="addToCart({{ $variant->id }})" class="fa fa-shopping-basket"></em>
                            <em wire:loading wire:target="addToCart({{ $variant->id }})" class="fa fa-spinner fa-spin"></em>
                            <span>{{ __("Purchase") }}</span>
                        </button>
                    </form>
                @else
                    <button class="btn btn-danger" disabled>{{ __("Out of stock") }}</button>
                @endif
            </div>
        </div>

        @if($variant->recent)
            <img src="{{ asset('storage/images/new-ribbon.png') }}" alt="New ribbon" class="position-absolute" style="width: 100px; height: 100px; left: -13px; top: -12px">
        @endif

        @if($variant->discount > 0)
            <div class="position-absolute p-2 fs-6 badge bg-yellow-500" style="z-index: 2000; top:10px; right: 10px;">{{ format_percent(-$variant->discount) }}</div>
        @endif
    </div>
</div>
