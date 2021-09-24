<div class="row">
    <div class="col">
        <x-bs::input.floating-label for="country" label="{{ __('Country') }}">
            <x-bs::input.select wire:model="country_id" name="country_id" error="country_id" id="country">
                <option value="" disabled selected>{{ __("Country") }}</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </x-bs::input.select>
        </x-bs::input.floating-label>
    </div>

    <div class="col">
        @if($provinces->isEmpty())
            <x-bs::input.floating-label for="province-input" label="{{ __('Province') }}">
                <x-bs::input.text name="province" error="province" id="province-input" placeholder="{{ __('Province') }}"/>
            </x-bs::input.floating-label>
        @else
            <x-bs::input.floating-label for="province-select" label="{{ __('Province') }}">
                <x-bs::input.select wire:model.defer="province" name="province" error="province" id="province-select" placeholder="{{ __('Province') }}">
                    <option value="" disabled>{{ __("Select province") }}</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}" @if(old('province', $province ?? '') === $province) selected @endif>{{ $province }}</option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.floating-label>
        @endif
    </div>
</div>
