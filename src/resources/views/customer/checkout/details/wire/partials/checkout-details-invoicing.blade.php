<x-bs::card class="shadow-none bg-gray-100">
    <x-bs::card.body class="px-4 py-3">
        <x-bs::input.checkbox wire:model.defer="invoicing" lbl-class="d-block" value="1" id="invoicing" data-bs-toggle="collapse" data-bs-target="#invoice-collapse">
            {{ __("Invoicing") }}
        </x-bs::input.checkbox>

        <div id="invoice-collapse" class="collapse row row-cols-2 g-3 mt-0 @if($invoicing) show @endif">
            <div class="col-6">
                <x-bs::input.floating-label for="invoice-name" label="{{ __('Name') }}">
                    <x-bs::input.text wire:model.defer="invoice.name" error="invoice.name" id="invoice-name" placeholder="{{ __('Name') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-6">
                <x-bs::input.floating-label for="invoice-job" label="{{ __('Job') }}">
                    <x-bs::input.text wire:model.defer="invoice.job" error="invoice.job" id="invoice-job" placeholder="{{ __('Job') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="invoice-vat" label="{{ __('Vat number') }}">
                    <x-bs::input.text wire:model.defer="invoice.vat_number" maxlength="20" error="invoice.vat_number" id="invoice-vat" placeholder="{{ __('Vat number') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="invoice-tax-authority" label="{{ __('Tax authority') }}">
                    <x-bs::input.text wire:model.defer="invoice.tax_authority" error="invoice.tax_authority" id="invoice-tax-authority" placeholder="{{ __('Tax authority') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="invoice-phone" label="{{ __('Phone') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.phone" error="invoiceAddress.phone" id="invoice-phone" placeholder="{{ __('Phone') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-6">
                <x-bs::input.floating-label for="invoice-country" label="{{ __('Country') }}">
                    <x-bs::input.select wire:model.defer="invoiceAddress.country_id" class="pb-1" error="invoiceAddress.country_id" id="invoice-country">
                        <option value="">{{ __('Select country') }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.floating-label>
            </div>

            <div class="col-6">
                <x-bs::input.floating-label for="invoice-province" label="{{ __('Province / Department') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.province" error="invoiceAddress.province" id="invoice-province" placeholder="{{ __('Province / Department') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-8">
                <x-bs::input.floating-label for="invoice-street" label="{{ __('Street') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.street" error="invoiceAddress.street" id="invoice-street" placeholder="{{ __('Street') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="invoice-street-no" label="{{ __('Number') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.street_no" error="invoiceAddress.street_no" id="invoice-street-no" placeholder="{{ __('Number') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-8">
                <x-bs::input.floating-label for="invoice-city" label="{{ __('City') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.city" error="invoiceAddress.city" id="invoice-city" placeholder="{{ __('City') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="invoice-postcode" label="{{ __('Postcode') }}">
                    <x-bs::input.text wire:model.defer="invoiceAddress.postcode" error="invoiceAddress.postcode" id="invoice-postcode" placeholder="{{ __('Postcode') }}"/>
                </x-bs::input.floating-label>
            </div>
        </div>
    </x-bs::card.body>
</x-bs::card>
