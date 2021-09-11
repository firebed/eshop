<div wire:ignore.self class="offcanvas offcanvas-end shadow px-0" tabindex="-1" id="shipping-form" style="width: 500px">
    <div class="offcanvas-header border-bottom">
        <div class="fs-5 fw-500">Στοιχεία αποστολής</div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body scrollbar">
        <div class="row g-2">
            <div class="col">
                <x-bs::input.floating-label for="shipping-first-name" label="{{ __('Name') }}">
                    <x-bs::input.text wire:model.defer="shipping.first_name" name="shipping[first_name]" error="shipping[first_name]" autocomplete="new" id="shipping-first-name" placeholder="{{ __('Name') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col">
                <x-bs::input.floating-label for="shipping-last-name" label="{{ __('Surname') }}">
                    <x-bs::input.text wire:model.defer="shipping.last_name" name="shipping[last_name]" error="shipping.last_name" autocomplete="new" id="shipping-last-name" placeholder="{{ __('Surname') }}"/>
                </x-bs::input.floating-label>
            </div>

            <x-bs::input.floating-label for="email" label="{{ __('Email') }}" class="col-12">
                <x-bs::input.email wire:model.defer="email" name="email" error="email" autocomplete="new" id="email" placeholder="{{ __('Email') }}"/>
            </x-bs::input.floating-label>

            <x-bs::input.floating-label for="shipping-phone" label="{{ __('Phone') }}" class="col-12">
                <x-bs::input.text wire:model.defer="shipping.phone" name="shipping[phone]" error="shipping.phone" autocomplete="new" id="shipping-phone" placeholder="{{ __('Phone') }}"/>
            </x-bs::input.floating-label>

            <div class="col-12 fw-500 mt-3">Διεύθυνση αποστολής</div>

            <x-bs::input.floating-label for="shipping-country" label="{{ __('Country') }}" class="col-6">
                <x-bs::input.select wire:model="shipping.country_id" name="shipping[country_id]" error="shipping.country_id" id="shipping-country">
                    <option value="">{{ __('Select country') }}</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.floating-label>

            @if($provinces->isEmpty())
                <x-bs::input.floating-label for="shipping-province" label="{{ __('Province') }}" class="col-6">
                    <x-bs::input.text wire:model.defer="shipping.province" wire:loading.attr="disabled" wire:target="country_id" name="shipping[province]" error="shipping.province" autocomplete="new" id="shipping-province" placeholder="{{ __('Province') }}"/>
                </x-bs::input.floating-label>
            @else
                <x-bs::input.floating-label for="shipping-province" label="{{ __('Province') }}" class="col-6">
                    <x-bs::input.select wire:model.defer="shipping.province" wire:loading.attr="disabled" wire:target="country_id" name="shipping[province]" error="shipping.province" autocomplete="new" id="shipping-province">
                        <option value="">{{ __('Select province') }}</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province }}">{{ $province }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.floating-label>
            @endif

            <div class="col-8">
                <x-bs::input.floating-label for="shipping-city" label="{{ __('City') }}">
                    <x-bs::input.text wire:model.defer="shipping.city" name="shipping[city]" error="shipping.city" autocomplete="new" id="shipping-city" placeholder="{{ __('City') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="shipping-postcode" label="{{ __('Postcode') }}">
                    <x-bs::input.text wire:model.defer="shipping.postcode" name="shipping[postcode]" error="shipping.postcode" autocomplete="new" autofill="off" id="shipping-postcode" placeholder="{{ __('Postcode') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-8">
                <x-bs::input.floating-label for="shipping-street" label="{{ __('Street') }}">
                    <x-bs::input.text wire:model.defer="shipping.street" name="shipping[street]" error="shipping.street" autocomplete="new" id="shipping-street" placeholder="{{ __('Street') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="shipping-street-no" label="{{ __('Number') }}">
                    <x-bs::input.text wire:model.defer="shipping.street_no" name="shipping[street_no]" error="shipping.street_no" autocomplete="new" id="shipping-street-no" placeholder="{{ __('Number') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="fw-500 mt-3">Μέθοδος αποστολής</div>

            <x-bs::input.floating-label for="shipping-method" label="{{ __('Shipping method') }}" class="col-8">
                <x-bs::input.select wire:model.defer="method" name="shipping_method_id" error="shipping_method" id="shipping-method">
                    <option value="">{{ __('Select shipping method') }}</option>
                    @foreach($shippingMethods as $method)
                        <option value="{{ $method->id }}">{{ __('eshop::shipping.' . $method->name) }} ({{ format_currency($method->total_fee) }})</option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.floating-label>

            <x-bs::input.floating-label x-data="{fee: 0}" for="shipping-fee" label="{{ __('Shipping fee') }}" class="col-4">
                <x-bs::input.money wire:model.lazy="fee" x-effect="fee = value" error="shipping_fee" id="shipping-fee" placeholder="{{ __('Shipping fee') }}"/>
                <input type="hidden" x-model="fee" name="shipping_fee"/>
            </x-bs::input.floating-label>

            <div class="text-center py-3">
                <button wire:click.prevent="calculateShipping" wire:loading.attr="disabled" class="btn btn-warning">
                    <em wire:loading wire:target="calculateShipping" class="fas fa-spinner fa-spin me-2"></em>
                    Υπολογισμός μεταφορικών
                </button>
            </div>

            <hr>

            <div class="fw-500">Ανάλυση μεταφορικών</div>

            <div class="vstack gap-1">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td>Βασικό τέλος</td>
                            <td class="text-end">{{ format_currency($base_fee) }}</td>
                        </tr>

                        <tr @class(['fw-500 text-danger' => $inaccessible_area_fee > 0])>
                            <td>Δυσπρόσιτη περιοχή</td>
                            <td class="text-end">{{ format_currency($inaccessible_area_fee) }}</td>
                        </tr>

                        <tr @class(['fw-500 text-danger' => $excess_weight_fee > 0])>
                            <td>Υπέρβαση βάρους</td>
                            <td class="text-end">{{ format_currency($excess_weight_fee) }}</td>
                        </tr>
                    </tbody>

                    <tfoot>
                    <tr class="fw-bold">
                        <td>Σύνολο</td>
                        <td class="text-end">{{ format_currency($base_fee + $inaccessible_area_fee + $excess_weight_fee) }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
