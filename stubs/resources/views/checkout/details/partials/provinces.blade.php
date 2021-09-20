@if(old('shippingAddress.country_id', $shipping->country_id ?? null) === null || $provinces->isEmpty())
    <x-bs::input.floating-label for="shipping-province" label="{{ __('Province') }}">
        <x-bs::input.text value="{{ old('shippingAddress.province', $shipping->province ?? '') ?? '' }}" name="shippingAddress[province]" error="shippingAddress.province" id="shipping-province" placeholder="{{ __('Province / Department') }}"/>
    </x-bs::input.floating-label>
@else
    <x-bs::input.floating-label for="shipping-province" label="{{ __('Province') }}">
        <x-bs::input.select name="shippingAddress[province]" error="shippingAddress.province" id="shipping-province" class="pb-2">
            <option disabled value="">{{ __('Select province') }}</option>
            @foreach($provinces as $province)
                <option value="{{ $province }}" @if(old('province', $shipping?->province) === $province) selected @endif>{{ $province }}</option>
            @endforeach
        </x-bs::input.select>
    </x-bs::input.floating-label>
@endif