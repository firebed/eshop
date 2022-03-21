<div class="col-12 col-xxl-10 p-4 mx-auto d-grid gap-3" xmlns:x-bs="http://www.w3.org/1999/html">

    <div class="d-flex flex-wrap justify-content-between">
        <div class="col d-grid d-sm-flex gap-2">
            <input type="search" wire:model="search" class="form-control" placeholder="Αναζήτηση">
        </div>

        <div class="col d-flex justify-content-end gap-2">
            <x-bs::button.primary wire:click="create()" wire:loading.attr="disabled" wire:target="create">
                <em class="fa fa-plus me-2"></em> {{ __("New") }}
            </x-bs::button.primary>
        </div>
    </div>

    <x-bs::card>
        <div class="table-responsive">
            <x-bs::table>
                <thead>
                <tr>
                    <th>Επωνυμία</th>
                    <th>ΑΦΜ</th>
                    <th>Χώρα</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->vat_number }}</td>
                        <td>{{ $client->country }}</td>
                        <td class="text-end"><a href="#" wire:click="edit({{ $client->id }})" class="text-decoration-none">Επεξεργασία</a></td>
                    </tr>
                @endforeach
                </tbody>

                <caption>
                    <x-eshop::pagination :paginator="$clients"/>
                </caption>
            </x-bs::table>
        </div>
    </x-bs::card>

    <form wire:submit.prevent="save">
        <x-bs::modal wire:model.defer="showEditingModal">
            <x-bs::modal.body class="d-grid gap-3">
                <div>
                    <label for="name" class="form-label">Επωνυμία <span class="text-danger">*</span></label>
                    <x-bs::input.text wire:model.defer="model.name" id="name" error="model.name"/>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="vat-number" class="form-label">ΑΦΜ <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <x-bs::input.text wire:model.defer="model.vat_number" id="vat-number" error="model.vat_number"/>
                            <button wire:click="searchVat()" class="btn btn-outline-secondary" type="button" id="taxis"><em class="fas fa-building"></em></button>
                        </div>
                    </div>

                    <div class="col">
                        <label for="tax-authority" class="form-label">ΔΟΥ</label>
                        <x-bs::input.text wire:model.defer="model.tax_authority" id="tax-authority" error="model.tax_authority"/>
                    </div>
                </div>

                <div>
                    <label for="job" class="form-label">Επάγγελμα <span class="text-danger">*</span></label>
                    <x-bs::input.text wire:model.defer="model.job" id="job" error="model.job"/>
                </div>

                <div class="row">
                    <div class="col-4">
                        <label for="country" class="form-label">Χώρα <span class="text-danger">*</span></label>
                        <x-bs::input.select wire:model.defer="model.country" id="country" error="model.country">
                            <option value="GR">Ελλάδα</option>
                            <option value="CY">Κύπρο</option>
                        </x-bs::input.select>
                    </div>

                    <div class="col">
                        <label for="city" class="form-label">Πόλη <span class="text-danger">*</span></label>
                        <x-bs::input.text wire:model.defer="model.city" id="city" error="model.city"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <label for="street" class="form-label">Οδός <span class="text-danger">*</span></label>
                        <x-bs::input.text wire:model.defer="model.street" id="street" error="model.street"/>
                    </div>

                    <div class="col">
                        <label for="street_no" class="form-label">Αριθμός οδού</label>
                        <x-bs::input.text wire:model.defer="model.street_number" id="street_no" error="model.street_number"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="postcode" class="form-label">Ταχυδρομικό κώδικας <span class="text-danger">*</span></label>
                        <x-bs::input.text wire:model.defer="model.postcode" id="postcode" error="model.postcode"/>
                    </div>
                    
                    <div class="col">
                        <label for="phone-number" class="form-label">Τηλέφωνο <span class="text-danger">*</span></label>
                        <x-bs::input.text wire:model.defer="model.phone_number" id="phone-number" error="model.phone_number"/>
                    </div>
                </div>
            </x-bs::modal.body>

            <x-bs::modal.footer>
                <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
                <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
            </x-bs::modal.footer>
        </x-bs::modal>
    </form>
</div>
