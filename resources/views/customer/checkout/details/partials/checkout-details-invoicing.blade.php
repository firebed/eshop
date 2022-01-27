<x-bs::card class="shadow-none bg-gray-100">
    <x-bs::card.body class="px-4 py-3">
        <x-bs::input.checkbox :checked="old('invoicing', $invoicing)" name="invoicing" lbl-class="d-block" id="invoicing" data-bs-toggle="collapse" data-bs-target="#invoice-collapse">
            {{ __("Invoicing") }}
        </x-bs::input.checkbox>

        <div id="invoice-collapse" class="collapse row row-cols-2 g-3 mt-0 @if(old('invoicing', $invoicing)) show @endif">
            <div class="col-12 col-sm-6">
                <x-bs::input.floating-label for="invoice-name" label="{{ __('Company name') }}">
                    <x-bs::input.text value="{{ old('invoice.name', $invoice?->name) ?? '' }}" name="invoice[name]" error="invoice.name" id="invoice-name" placeholder="{{ __('Company name') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 col-sm-6">
                <x-bs::input.floating-label for="invoice-job" label="{{ __('Job') }}">
                    <x-bs::input.text value="{{ old('invoice.job', $invoice?->job)  ?? ''}}" name="invoice[job]" error="invoice.job" id="invoice-job" placeholder="{{ __('Job') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 col-sm-4">
                <x-bs::input.floating-label for="invoice-vat" label="{{ __('Vat number') }}">
                    <x-bs::input.text value="{{ old('invoice.vat_number', $invoice?->vat_number)  ?? ''}}" name="invoice[vat_number]" maxlength="20" error="invoice.vat_number" id="invoice-vat" placeholder="{{ __('Vat number') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 col-sm-4">
                <x-bs::input.floating-label for="invoice-tax-authority" label="{{ __('Tax authority') }}">
                    <x-bs::input.text value="{{ old('invoice.tax_authority', $invoice?->tax_authority) ?? '' }}" name="invoice[tax_authority]" error="invoice.tax_authority" id="invoice-tax-authority" placeholder="{{ __('Tax authority') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 col-sm-4">
                <x-bs::input.floating-label for="invoice-phone" label="{{ __('Phone') }}">
                    <x-bs::input.text value="{{ old('invoiceAddress.phone', $invoice?->billingAddress?->phone) ?? '' }}" name="invoiceAddress[phone]" error="invoiceAddress.phone" id="invoice-phone" placeholder="{{ __('Phone') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 col-sm-6">
                <x-bs::input.floating-label for="invoice-country" label="{{ __('Country') }}">
                    <x-bs::input.select name="invoiceAddress[country_id]" error="invoiceAddress.country_id" id="invoice-country">
                        <option value="">{{ __('Select country') }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" @if(old('invoiceAddress.country_id', $invoice?->billingAddress?->country_id) == $country->id) selected @endif>{{ $country->name }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 col-sm-6">
                <x-bs::input.floating-label for="invoice-province" label="{{ __('Province / Department') }}">
                    <x-bs::input.text value="{{ old('invoiceAddress.province', $invoice?->billingAddress?->province) ?? '' }}" name="invoiceAddress[province]" error="invoiceAddress.province" id="invoice-province" placeholder="{{ __('Province / Department') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 col-sm-8">
                <x-bs::input.floating-label for="invoice-street" label="{{ __('Street') }}">
                    <x-bs::input.text value="{{ old('invoiceAddress.street', $invoice?->billingAddress?->street) ?? '' }}" name="invoiceAddress[street]" error="invoiceAddress.street" id="invoice-street" placeholder="{{ __('Street') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 col-sm-4">
                <x-bs::input.floating-label for="invoice-street-no" label="{{ __('Number') }}">
                    <x-bs::input.text value="{{ old('invoiceAddress.street_no', $invoice?->billingAddress?->street_no) ?? '' }}" name="invoiceAddress[street_no]" error="invoiceAddress.street_no" id="invoice-street-no" placeholder="{{ __('Number') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 col-sm-8">
                <x-bs::input.floating-label for="invoice-city" label="{{ __('City') }}">
                    <x-bs::input.text value="{{ old('invoiceAddress.city', $invoice?->billingAddress?->city) ?? '' }}" name="invoiceAddress[city]" error="invoiceAddress.city" id="invoice-city" placeholder="{{ __('City') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-12 col-sm-4">
                <x-bs::input.floating-label for="invoice-postcode" label="{{ __('Postcode') }}">
                    <x-bs::input.text value="{{ old('invoiceAddress.postcode', $invoice?->billingAddress?->postcode) ?? '' }}" name="invoiceAddress[postcode]" error="invoiceAddress.postcode" id="invoice-postcode" placeholder="{{ __('Postcode') }}"/>
                </x-bs::input.floating-label>
            </div>
        </div>
    </x-bs::card.body>
</x-bs::card>
