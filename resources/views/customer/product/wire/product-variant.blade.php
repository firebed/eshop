<li class="col">
    <div class="card h-100 position-relative">
        <div @class(["card-body d-grid gap-3", "new-product" => $variant->recent])>
            <a class="text-decoration-none text-dark d-grid gap-2" href="{{ productRoute($variant, $category) }}">
                @if($variant->image)
                    <div class="ratio ratio-1x1">
                        <img src="{{ $variant->image->url('sm') }}" alt="{{ $variant->trademark }}" class="rounded {{ eshop('product.image.cover') ? '' : 'img-middle' }}">
                    </div>
                @endif

                <div class="d-grid">
                    <h3 class="fs-6 fw-500">{{ $variant->option_values }}@can('Manage products') <span class="text-secondary">({{ format_number($variant->stock) }})</span> @endcan</h3>

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
                        <del class="text-danger ms-3 small">{{ format_currency($variant->price) }}</del>
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

        @if($variant->isOnSale())
            <div class="product-discount">{{ format_percent(-$variant->discount) }}</div>
        @endif
    </div>
</li>
