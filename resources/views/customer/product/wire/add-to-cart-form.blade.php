<form wire:submit.prevent="addToCart">
    <div class="row mb-3 g-4">
        @if($product->canDisplayStock())
            <div class="col-12 fw-500 text-success">@choice("eshop::product.availability", $product->available_stock, ['count' => format_number($product->available_stock)])</div>
        @endif

        <div class="col-12 hstack gap-3 fw-500">
            @can('Is merchant')
                <div class="h3 mb-0">{{ format_currency($product->getNetValueForUser(auth()->user())) }}</div>
            @else
                <div class="h3 mb-0">{{ format_currency($product->netValue) }}</div>
                @if($product->discount > 0) <s class="text-secondary">{{ format_currency($product->price) }}</s>@endif
            @endcan
        </div>

        <div class="col-12 col-sm d-grid gap-1">
            <div class="input-group" x-data="{ quantity: $wire.entangle('quantity').defer }">
                <x-bs::button.secondary x-on:click="if(quantity > 1) quantity--" class="border shadow-none" aria-label="{{ __('Decrease quantity') }}"><em class="fa fa-minus"></em></x-bs::button.secondary>
                <label for="quantity" class="visually-hidden">{{ __("Quantity") }}</label>
                <input x-model="quantity" placeholder="0" name="quantity"
                       type="number" min="1" step="1" class="form-control text-center"
                       x-on:keydown="if($event.key === '.') $event.preventDefault()"
                       title="{{ __("Quantity") }}">
                <x-bs::button.secondary x-on:click="quantity++" class="border shadow-none" aria-label="{{ __('Increase quantity') }}"><em class="fa fa-plus"></em></x-bs::button.secondary>
            </div>

            <div id="errors" class="fw-500 small text-danger">
                @error('quantity') {{ $message }} @enderror
            </div>
        </div>

        <div class="col-12 col-sm">
            <button type="submit" class="btn btn-green w-100">
                <em class="fa fa-shopping-cart"></em>
                <span class="ms-3">{{ __("Add to cart") }}</span>
            </button>
        </div>
    </div>

    <div class="text-success fw-500">
        @if (session()->has('quantity'))
            <em class="fa fa-check-circle me-2"></em> {{ session('quantity') }}
        @endif
    </div>
</form>
