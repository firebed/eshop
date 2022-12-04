@if($provinces->isEmpty())
    <x-bs::input.floating-label for="shipping-province" label="{{ __('Province') }}">
        <x-bs::input.text value="{{ old('shippingAddress.province', $shipping->province ?? '') ?? '' }}" name="shippingAddress[province]" error="shippingAddress.province" id="shipping-province" placeholder="{{ __('Province / Department') }}"/>
    </x-bs::input.floating-label>
@else
    <x-bs::input.floating-label for="shipping-province" label="{{ __('Province') }}">
        <x-bs::input.select name="shippingAddress[province]" error="shippingAddress.province" id="shipping-province" class="pb-2">
            <option disabled value="" @if($shipping?->province === null) selected @endif>{{ __('Select province') }}</option>
            @foreach($provinces as $province)
                <option value="{{ $province }}" @if(old('shippingAddress.province', $shipping?->province) === $province) selected @endif>{{ $province }}</option>
            @endforeach
        </x-bs::input.select>
    </x-bs::input.floating-label>
@endif

{{--<div x-data="{--}}
{{--    show: false,--}}
{{--    results: [],--}}
{{--    search() {--}}
{{--        term = $refs.input.value.trim();--}}
{{--        if (term.length === 0) {--}}
{{--            this.show = false;--}}
{{--            this.results = [];--}}
{{--            return;--}}
{{--        }--}}
{{--        --}}
{{--        axios.post('{{ route('checkout.details.areas', app()->getLocale()) }}', { term })--}}
{{--        .then(r => {--}}
{{--        console.log(r.data)--}}
{{--            this.results = r.data;--}}
{{--            this.show = this.results.length > 0;--}}
{{--        })--}}
{{--    },--}}
{{--    select(item) {--}}
{{--        $refs.input.value = item.region + ' ' + item.postcode;--}}
{{--        this.show = false;--}}
{{--    }--}}
{{--}" x-on:click.away="show = false" class="position-relative">--}}
{{--    <x-bs::input.floating-label for="shipping-province" label="{{ __('Area') }}">--}}
{{--        <x-bs::input.text x-ref="input" x-on:input.debounce="search()" x-on:focus="if (results.length > 0) show = true" placeholder="{{ __('Area') }}"/>--}}
{{--    </x-bs::input.floating-label>--}}

{{--    <div x-show="show" x-cloak class="position-absolute bg-white shadow-sm border list-group list-group-flush scrollbar overflow-auto w-100 rounded-3" style="z-index: 1500; max-height: 350px; top: 66px">--}}
{{--        <template x-for="r in results">--}}
{{--            <a href="#" @click.prevent="select(r)" class="list-group-item list-group-item-action" x-text="r.region + ' ' + r.postcode"></a>--}}
{{--        </template>--}}
{{--    </div>--}}
{{--</div>--}}