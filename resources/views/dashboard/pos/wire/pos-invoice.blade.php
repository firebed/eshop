<div wire:ignore.self class="offcanvas offcanvas-end px-0" tabindex="-1" id="invoice-form" style="width: 500px">
    <div class="offcanvas-header border-bottom">
        <div class="fs-5 fw-500">Τιμολόγιο</div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <div class="row g-2">
            <div class="col-12">
                <x-bs::input.floating-label for="invoice-name" label="{{ __('Name') }}">
                    <x-bs::input.text wire:model.defer="invoice.name" name="invoice[name]" error="invoice.name" id="invoice-name" placeholder="{{ __('Name') }}" autocomplete="new"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12">
                <x-bs::input.floating-label for="invoice-job" label="{{ __('Job') }}">
                    <x-bs::input.text wire:model.defer="invoice.job" name="invoice[job]" error="invoice.job" id="invoice-job" placeholder="{{ __('Job') }}" autocomplete="new"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-6">
                <x-bs::input.floating-label for="invoice-vat" label="{{ __('Vat number') }}">
                    <x-bs::input.text wire:model.defer="invoice.vat_number" name="invoice[vat_number]" maxlength="20" error="invoice.vat_number" id="invoice-vat" placeholder="{{ __('Vat number') }}" autocomplete="new"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-6">
                <x-bs::input.floating-label for="invoice-tax-authority" label="{{ __('Tax authority') }}">
                    <x-bs::input.text wire:model.defer="invoice.tax_authority" name="invoice[tax_authority]" error="invoice.tax_authority" id="invoice-tax-authority" placeholder="{{ __('Tax authority') }}" autocomplete="new"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12">
                <x-bs::input.floating-label for="invoice-phone" label="{{ __('Phone') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.phone" name="invoiceAddress[phone]" error="invoiceAddress.phone" id="invoice-phone" placeholder="{{ __('Phone') }}" autocomplete="new"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 fw-500 mt-3 mb-2">Διεύθυνση</div>

            <div class="col-12">
                <x-bs::input.floating-label for="invoice-country" label="{{ __('Country') }}">
                    <x-bs::input.select wire:model="invoiceAddress.country_id" name="invoiceAddress[country_id]" error="invoiceAddress.country_id" id="invoice-country">
                        <option value="">{{ __('Select country') }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" @if(old('invoiceAddress.country_id', $invoice->billingAddress->id ?? '') == $country->id) selected @endif>{{ $country->name }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.floating-label>
            </div>

            @if($provinces->isEmpty())
                <x-bs::input.floating-label for="invoice-address-province" label="{{ __('Province') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.province" wire:loading.attr="disabled" wire:target="country_id" name="invoiceAddress[province]" error="invoiceAddress.province" id="invoice-address-province" placeholder="{{ __('Province') }}" autocomplete="new"/>
                </x-bs::input.floating-label>
            @else
                <x-bs::input.floating-label for="invoice-address-province" label="{{ __('Province') }}">
                    <x-bs::input.select wire:model.defer="invoiceAddress.province" wire:loading.attr="disabled" wire:target="country_id" name="invoiceAddress[province]" error="invoiceAddress.province" autocomplete="new" id="invoice-address-province">
                        <option value="">{{ __('Select province') }}</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province }}">{{ $province }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.floating-label>
            @endif

            <div class="col-8">
                <x-bs::input.floating-label for="invoice-city" label="{{ __('City') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.city" name="invoiceAddress[city]" error="invoiceAddress.city" id="invoice-city" placeholder="{{ __('City') }}" autocomplete="new"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="invoice-postcode" label="{{ __('Postcode') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.postcode" name="invoiceAddress[postcode]" error="invoiceAddress.postcode" id="invoice-postcode" placeholder="{{ __('Postcode') }}" autocomplete="new"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-8">
                <x-bs::input.floating-label for="invoice-street" label="{{ __('Street') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.street" name="invoiceAddress[street]" error="invoiceAddress.street" id="invoice-street" placeholder="{{ __('Street') }}" autocomplete="new"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="invoice-street-no" label="{{ __('Number') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.street_no" name="invoiceAddress[street_no]" error="invoiceAddress.street_no" id="invoice-street-no" placeholder="{{ __('Number') }}" autocomplete="new"/>
                </x-bs::input.floating-label>
            </div>
        </div>
    </div>
</div>