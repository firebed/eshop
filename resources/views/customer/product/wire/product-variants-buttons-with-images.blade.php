<div>
    <h2 class="visually-hidden">{{ __("Variants") }}</h2>

    @foreach($this->variantTypes as $type)
        <div class="fw-500 mb-2">{{ __("Select") }} {{ __($type->name) }}</div>

        <ul class="row row-cols-2 row-cols-sm-3 row-cols-xl-4 g-2 mb-3 list-unstyled" style="overflow-y: auto; max-height: 360px">
            @isset($uniqueOptions[$type->id])
                @foreach($uniqueOptions[$type->id] as $option)
                    <li class="col d-grid">
                        @if(!$this->isAvailable($type->id, $option->pivot->slug))
                            @if(in_array($option->pivot->slug, $filters, true))
                                <button type="button"
                                        wire:click="select({{ $type->id }}, '{{ $option->pivot->slug }}')"
                                        class="w-100 btn btn-primary">
                                    <img src="{{ asset($product->variants->find($option->pivot->product_id)->image->url('sm')) }}" class="img-fluid w-50" alt="">
                                    <div class="small text-start text-dark">{{ format_currency($product->variants->firstWhere('id', $option->pivot->product_id)->price) }}</div>
{{--                                    {{ $option->pivot->name }}--}}
                                </button>
                            @else
                                <button type="button"
                                        wire:click="select({{ $type->id }}, '{{ $option->pivot->slug }}')"
                                        class="w-100 btn btn-outline-light text-gray-500"
                                        style="border-color: lightgray"
                                >
                                    <img src="{{ asset($product->variants->find($option->pivot->product_id)->image->url('sm')) }}" class="img-fluid w-50" alt="">
                                    <div class="small text-start text-dark">{{ format_currency($product->variants->firstWhere('id', $option->pivot->product_id)->price) }}</div>
                                    {{--                                    {{ $option->pivot->name }}--}}
                                </button>
                            @endif
                        @else
                            <button type="button"
                                    wire:click="select({{ $type->id }}, '{{ $option->pivot->slug }}')"
                                    class="btn @if(in_array($option->pivot->slug, $filters, true)) btn-primary @else btn-outline-primary @endif">

                                <img src="{{ asset($product->variants->find($option->pivot->product_id)->image->url('sm')) }}" class="img-fluid w-50" alt="">
                                <div class="small text-start text-dark">{{ format_currency($product->variants->firstWhere('id', $option->pivot->product_id)->price) }}</div>
{{--                                {{ $option->pivot->name }}--}}
                            </button>
                        @endif
                    </li>
                @endforeach
            @endisset
        </ul>
    @endforeach

    <form wire:submit.prevent="addToCart" class="vstack gap-3">
        @if($variant && $variant->canDisplayStock())
            <div class="fw-500 text-success">@choice("eshop::product.availability", $variant->available_stock, ['count' => format_number($variant->available_stock)])</div>
        @endif

        <div class="row row-cols-1 row-cols-sm-2 gy-2">
            <div class="col d-grid gap-1">
                <div class="input-group" x-data="{ quantity: $wire.entangle('quantity').defer }">
                    <x-bs::button.light x-on:click="if(quantity > 0) quantity--" class="border shadow-none" aria-label="{{ __('Decrease quantity') }}"><em class="fa fa-minus"></em></x-bs::button.light>
                    <label for="quantity" class="visually-hidden">{{ __("Quantity") }}</label>
                    <input x-model="quantity" placeholder="0" name="quantity"
                           type="number" min="1" step="1" class="form-control text-center"
                           x-on:keydown="if($event.key === '.') $event.preventDefault()"
                           title="{{ __("Quantity") }}">
                    <x-bs::button.light x-on:click="quantity++" class="border shadow-none" aria-label="{{ __('Increase quantity') }}"><em class="fa fa-plus"></em></x-bs::button.light>
                </div>

                <div id="errors" class="fw-500 small text-danger">
                    @error('quantity') {{ $message }} @enderror
                </div>
            </div>

            <div class="col">
                @if($variant !== null && $variant->canBeBought())
                    <button type="submit" class="btn btn-green w-100">
                        <em class="fa fa-shopping-cart"></em>
                        <span class="ms-3">{{ __("Add to cart") }}</span>
                    </button>
                @else
                    <button disabled class="btn btn-danger w-100">{{ __("Out of stock") }}</button>
                @endif
            </div>
        </div>
    </form>
</div>