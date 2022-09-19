@push('header_scripts')
    <style>
        .img-btn {
            border-color: lightgray;
            position: relative;
        }

        .img-btn:focus {
            box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 25%);
        }

        .img-btn.unavailable {
            opacity: 0.3;
        }

        .img-btn.unavailable:after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: .3;
            background: linear-gradient(to top left,
            rgba(0, 0, 0, 0) 0%,
            rgba(0, 0, 0, 0) calc(50% - 0.8px),
            rgba(0, 0, 0, 1) 50%,
            rgba(0, 0, 0, 0) calc(50% + 0.8px),
            rgba(0, 0, 0, 0) 100%)
        }

        .img-btn:hover {
            border-color: #6c757d;
        }

        .img-btn:hover {
            border-color: #6c757d;
        }

        .img-btn.selected {
            border-color: rgb(13 110 253) !important;
            border-width: 2px;
        }
    </style>
@endpush

<div wire:loading.class="opacity-50 pe-none">
    <h2 class="visually-hidden">{{ __("Variants") }}</h2>

    @foreach($this->variantTypes as $type)
        <div class="fw-500 mb-3 d-flex">
            <span>{{ __("Select") }} {{ __($type->name) }}:</span>
            @if($type->slug === 'xrwma')
                <span class="ms-2 text-blue-500">{{ $color }}</span>
            @elseif($type->slug === 'megethos')
                <span class="ms-2 text-blue-500">{{ $size }}</span>
            @endif
        </div>

        <ul class="row row-cols-2 row-cols-sm-3 row-cols-xl-4 g-2 mb-3 list-unstyled scrollbar pb-1" style="overflow-y: auto; max-height: 360px">
            @isset($uniqueOptions[$type->id])
                @foreach($uniqueOptions[$type->id] as $option)
                    @php($var = $this->variants->find($option->pivot->product_id))
                    @php($image = $var->image)
                    @php($price = $var->net_value)
                    @php($selected = in_array($option->pivot->slug, $filters, true))
                    <li wire:key="{{ $type->id }}-{{ $option->pivot->slug }}" class="col d-grid" title="{{ $option->pivot->name }}">
                        @if(!$this->isAvailable($type->id, $option->pivot->slug))
                            @if($selected)
                                <button type="button" class="d-grid w-100 btn img-btn selected unavailable"
                                        wire:click="select({{ $type->id }}, '{{ $option->pivot->slug }}')"
                                >
                                    @if($type->slug === 'xrwma')
                                        <img src="{{ $image }}" alt="" style="width: 100%; height: 100px; object-fit: cover" class="rounded">
                                        {{--                                        <span class="small text-start text-dark">από {{ format_currency($price) }}</span>--}}
                                    @else
                                        {{ $option->pivot->name }}
                                    @endif
                                </button>
                            @else
                                <button type="button" class="d-grid w-100 btn img-btn unavailable"
                                        wire:click="select({{ $type->id }}, '{{ $option->pivot->slug }}')"
                                >
                                    @if($type->slug === 'xrwma')
                                        <img src="{{ $image }}" alt="" style="width: 100%; height: 100px; object-fit: cover" class="rounded">
                                        {{--                                        <span class="small text-start text-dark">από {{ format_currency($price) }}</span>--}}
                                    @else
                                        {{ $option->pivot->name }}
                                    @endif
                                </button>
                            @endif
                        @else
                            <button type="button" class="d-grid w-100 btn img-btn @if($selected) selected @endif"
                                    wire:click="select({{ $type->id }}, '{{ $option->pivot->slug }}')"
                            >
                                @if($type->slug === 'xrwma')
                                    <img src="{{ $image }}" alt="" style="width: 100%; height: 100px; object-fit: cover" class="rounded">
                                    {{--                                    <span class="small text-start text-dark">από {{ format_currency($price) }}</span>--}}
                                @else
                                    {{ $option->pivot->name }}
                                @endif
                            </button>
                        @endif
                    </li>
                @endforeach
            @endisset
        </ul>
    @endforeach

    <div class="fs-3 fw-500 mb-3">
        @if($variant)
            {{ format_currency($variant->net_value) }}
        @else
            {{ format_currency($min = $this->variants->min('net_value')) }}

            @if($min !== ($max = $this->variants->max('net_value')))
                - {{ format_currency($max) }}
            @endif
        @endif
    </div>

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
                @elseif(count($filters) === $this->variantTypes->count())
                    <button disabled class="btn btn-danger w-100">{{ __("Out of stock") }}</button>
                @else
                    <button disabled class="btn btn-danger w-100">Παρακαλούμε επιλέξτε {{ $this->variantTypes->whereNotIn('id', array_keys($filters))->pluck('name')->join(' + ') }}</button>
                @endif
            </div>
        </div>
    </form>
</div>
