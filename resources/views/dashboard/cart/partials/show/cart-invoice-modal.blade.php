<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showModal">
        <x-bs::modal.header>{{ __('Invoice') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="d-grid gap-3">
                @if($isInvoice)
                    <x-bs::input.group for="invoice-name" label="{{ __('Company name') }}">
                        <x-bs::input.text wire:model.defer="invoice.name" id="invoice-name" error="invoice.name"/>
                    </x-bs::input.group>

                    <x-bs::input.group for="invoice-job" label="{{ __('Company job') }}">
                        <x-bs::input.text wire:model.defer="invoice.job" id="invoice-job" error="invoice.job"/>
                    </x-bs::input.group>

                    <div class="row row-cols-2">
                        <x-bs::input.group for="invoice-vat" label="{{ __('Vat number') }}" class="col">
                            <x-bs::input.text wire:model.defer="invoice.vat_number" id="invoice-vat" error="invoice.vat"/>
                        </x-bs::input.group>

                        <x-bs::input.group for="tax-office" label="{{ __('Tax office') }}" class="col">
                            <x-bs::input.text wire:model.defer="invoice.tax_authority" id="tax-office" error="invoice.tax_office"/>
                        </x-bs::input.group>
                    </div>

                    <div class="row">
                        <x-bs::input.group for="invoice-billing-street" label="{{ __('Street') }}" class="col-8">
                            <x-bs::input.text wire:model.defer="invoiceBilling.street" id="invoice-billing-street" error="invoiceBilling.street"/>
                        </x-bs::input.group>

                        <x-bs::input.group for="invoice-billing-street-no" label="{{ __('Street no') }}" class="col-4">
                            <x-bs::input.text wire:model.defer="invoiceBilling.street_no" id="invoice-billing-street-no" error="invoiceBilling.street_no"/>
                        </x-bs::input.group>
                    </div>

                    <div class="row row-cols-2">
                        <x-bs::input.group for="invoice-billing-city" label="{{ __('City') }}" class="col">
                            <x-bs::input.text wire:model.defer="invoiceBilling.city" id="invoice-billing-city" error="invoiceBilling.city"/>
                        </x-bs::input.group>

                        <x-bs::input.group for="invoice-billing-postcode" label="{{ __('Postcode') }}" class="col">
                            <x-bs::input.text wire:model.defer="invoiceBilling.postcode" id="invoice-billing-postcode" error="invoiceBilling.postcode"/>
                        </x-bs::input.group>
                    </div>

                    <div class="row row-cols-2">
                        <x-bs::input.group for="invoice-billing-province" label="{{ __('State/Province') }}" class="col">
                            <x-bs::input.text wire:model.defer="invoiceBilling.province" id="invoice-billing-province" error="invoiceBilling.province"/>
                        </x-bs::input.group>

                        <x-bs::input.group for="invoice-billing-country" label="{{ __('Country') }}" class="col">
                            <x-bs::input.select wire:model.defer="invoiceBilling.country_id" id="invoice-billing-country" error="invoiceBilling.country_id">
                                <option value="">{{ __("Select country") }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </x-bs::input.select>
                        </x-bs::input.group>
                    </div>
                @endif
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
