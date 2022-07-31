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

            <div class="fw-500 mt-2">Μέθοδος αποστολής</div>

            <x-bs::input.floating-label for="shipping-method" label="{{ __('Shipping method') }}" class="col-8">
                <x-bs::input.select wire:model="shipping_method" wire:loading.attr="disabled" wire:target="shipping.country_id" name="country_shipping_method_id" error="shipping_method" id="shipping-method">
                    <option value="">{{ __('Select shipping method') }}</option>
                    @foreach($shippingOptions as $option)
                        <option value="{{ $option->id }}">
                            {{ __('eshop::shipping.' . $option->shippingMethod->name) }}
                            @if($option->total_fee > 0) ({{ format_currency($option->total_fee) }}) @endif
                        </option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.floating-label>

            <div x-data="{ fee: 0 }" class="form-floating col-4">
                <x-eshop::money wire:model.lazy="shipping_fee" x-effect="fee = value" error="shipping_fee" id="shipping-fee" placeholder="{{ __('Shipping fee') }}"/>
                <input type="hidden" x-model="fee" name="shipping_fee"/>
                <label for="shipping-fee">{{ __("Shipping fee") }}</label>
            </div>

            <div class="fw-500 mt-2">Μέθοδος πληρωμής</div>

            <x-bs::input.floating-label for="payment-method" label="{{ __('Payment method') }}" class="col-8">
                <x-bs::input.select wire:model="payment_method" wire:loading.attr="disabled" wire:target="shipping.country_id" name="country_payment_method_id" error="payment_method" id="payment-method">
                    <option value="">{{ __('Select payment method') }}</option>
                    @foreach($paymentOptions as $option)
                        <option value="{{ $option->id }}">
                            {{ __('eshop::payment.' . $option->paymentMethod->name) }}
                            @if($option->shippingMethod) ({{ __("eshop::shipping.abbr.{$option->shippingMethod->name}") }}) @endif
                            @if($option->fee > 0) ({{ format_currency($option->fee) }}) @endif
                        </option> 
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.floating-label>

            <x-bs::input.floating-label x-data="{ fee: 0 }" for="payment-fee" label="{{ __('Payment fee') }}" class="col-4">
                <x-eshop::money wire:model.lazy="payment_fee" x-effect="fee = value" error="payment_fee" id="payment-fee" placeholder="{{ __('Payment fee') }}"/>
                <input type="hidden" x-model="fee" name="payment_fee"/>
            </x-bs::input.floating-label>

{{--            <div class="text-center py-3">--}}
{{--                <button wire:click.prevent="save" wire:loading.attr="disabled" class="btn btn-warning">--}}
{{--                    <em wire:loading wire:target="save" class="fas fa-spinner fa-spin me-2"></em>--}}
{{--                    Αποθήκευση--}}
{{--                </button>--}}
{{--            </div>--}}
            
            <div class="fw-500">Ανάλυση μεταφορικών</div>

            <div class="vstack gap-1">
                <table class="table table-sm">
                    <tbody>
                    <tr>
                        <td>Έξοδα αποστολής</td>
                        <td class="text-end">{{ format_currency($base_shipping_fee) }}</td>
                    </tr>

                    <tr>
                        <td>Έξοδα πληρωμής</td>
                        <td class="text-end">{{ format_currency($payment_fee) }}</td>
                    </tr>

                    @if($inaccessible_area_fee > 0)
                        <tr>
                            <td>Δυσπρόσιτη περιοχή</td>
                            <td class="text-end">+{{ format_currency($inaccessible_area_fee) }}</td>
                        </tr>
                    @endif

                    @if($excess_weight_fee > 0)
                        <tr>
                            <td>Υπέρβαση βάρους</td>
                            <td class="text-end">+{{ format_currency($excess_weight_fee) }}</td>
                        </tr>
                    @endif
                    </tbody>

                    <tfoot>
                    <tr class="fw-bold">
                        <td>Σύνολο</td>
                        <td class="text-end">{{ format_currency(($shipping_fee ?? 0) + ($payment_fee ?? 0)) }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
