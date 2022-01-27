<div class="d-flex flex-column card p-3 shadow-sm">
    <div class="mb-3 d-flex gap-3">
        <button type="button" wire:click.prevent="editRow()" class="btn btn-sm btn-outline-alt">
            <em class="fas fa-plus"></em> Προσθήκη
        </button>

        <button type="button" wire:click.prevent="$emit('addRow')" class="btn btn-sm btn-outline-alt">
            <em class="fas fa-plus"></em> Προσθήκη προϊόντος
        </button>
    </div>

    <div class="table-responsive overflow-auto scrollbar mb-3" style="height: calc(100vh - 30.5rem)">
        <table class="table table-sm mb-0 table-hover">
            <thead>
            <tr>
                <td class="fw-500">#</td>
                <td class="fw-500">Κωδικός</td>
                <td class="fw-500">Περιγραφή</td>
                <td class="fw-500">ΜΜ</td>
                <td class="fw-500">Ποσότητα</td>
                <td class="fw-500">Τιμή</td>
                <td class="fw-500">Έκπτωση</td>
                <td class="fw-500">ΦΠΑ</td>
                <td class="fw-500"></td>
            </tr>
            </thead>

            <tbody>
            @foreach($rows as $i => $row)
                <tr wire:key="{{ $i }}">
                    <td class="align-middle">{{ $loop->iteration }}</td>

                    <td class="align-middle">
                        {{ $row['code'] }}
                        <input type="hidden" name="rows[{{ $i }}][id]" value="{{ $row['id'] }}">
                        <input type="hidden" name="rows[{{ $i }}][code]" value="{{ $row['code'] }}">
                    </td>

                    <td class="align-middle">
                        {{ $row['description'] }}
                        <input type="hidden" name="rows[{{ $i }}][description]" value="{{ $row['description'] }}">
                    </td>

                    <td class="align-middle">
                        {{ \Eshop\Models\Invoice\UnitMeasurement::from($rows[$i]['unit'])->abbr() }}
                        <input type="hidden" name="rows[{{ $i }}][unit]" value="{{ $row['unit'] }}">
                    </td>

                    <td class="align-middle">
                        {{ format_number($row['quantity'], 2) }}
                        <input type="hidden" name="rows[{{ $i }}][quantity]" value="{{ $row['quantity'] }}">
                    </td>

                    <td class="align-middle">
                        {{ format_number($row['price'], 2) }}
                        <input type="hidden" name="rows[{{ $i }}][price]" value="{{ $row['price'] }}">
                    </td>

                    <td class="align-middle">
                        {{ format_percent($row['discount']) }}
                        <input type="hidden" name="rows[{{ $i }}][discount]" value="{{ $row['discount'] }}">
                    </td>

                    <td class="align-middle">
                        {{ format_percent($row['vat_percent']) }}
                        <input type="hidden" name="rows[{{ $i }}][vat_percent]" value="{{ $row['vat_percent'] }}">
                    </td>

                    <td class="align-middle">
                        <div class="d-flex gap-1 justify-content-end">
                            <button wire:click.prevent="editRow({{ $i }})" class="btn btn-sm btn-outline-alt">
                                <em class="fas fa-edit"></em>
                            </button>

                            <button wire:click.prevent="deleteRow({{ $i }})" class="btn btn-sm btn-outline-secondary">
                                <em class="far fa-trash-alt"></em>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end gap-5">
        <div><span class="text-secondary">Καθαρό σύνολο: </span> <strong>{{ format_currency($total_net_value) }}</strong></div>
        <div><span class="text-secondary">Σύνολο ΦΠΑ: </span> <strong>{{ format_currency($total_vat_amount) }}</strong></div>
        <div><span class="text-secondary">Τελικό σύνολο: </span> <strong>{{ format_currency($total) }}</strong></div>
    </div>

    <x-bs::modal wire:model.defer="showEditingModal" x-data>
        <x-bs::modal.body>
            <div class="row align-items-baseline mb-3">
                <label for="edit-code" class="col-3 text-secondary">Κωδικός</label>
                <div class="col">
                    <input wire:model.defer="editing_row.code" type="text" id="edit-code" class="form-control" @keydown.enter.prevent="">
                </div>
            </div>

            <div class="row align-items-baseline mb-3">
                <label for="edit-description" class="col-3 text-secondary">Περιγραφή</label>
                <div class="col">
                    <input wire:model.defer="editing_row.description" type="text" id="edit-description" class="form-control" @keydown.enter.prevent="">
                </div>
            </div>

            <div class="row align-items-baseline mb-3">
                <label for="edit-unit" class="col-3 text-secondary">ΜΜ</label>
                <div class="col">
                    <select wire:model.defer="editing_row.unit" type="number" id="edit-unit" class="form-select">
                        @foreach(\Eshop\Models\Invoice\UnitMeasurement::cases() as $unit)
                            <option value="{{ $unit->value }}">{{ $unit->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row align-items-baseline mb-3">
                <label for="edit-quantity" class="col-3 text-secondary">Ποσότητα</label>
                <div class="col">
                    <input wire:model.defer="editing_row.quantity" type="number" id="edit-quantity" class="form-control" @keydown.enter.prevent="">
                </div>
            </div>

            <div class="row align-items-baseline mb-3">
                <label for="edit-price" class="col-3 text-secondary">Τιμή</label>
                <div class="col">
                    <input wire:model.defer="editing_row.price" type="number" step="0.0001" id="edit-price" class="form-control" @keydown.enter.prevent="">
                </div>
            </div>

            <div class="row align-items-baseline mb-3">
                <label for="edit-discount" class="col-3 text-secondary">Έκπτωση</label>
                <div class="col">
                    <input wire:model.defer="editing_row.discount" type="number" id="edit-discount" class="form-control" @keydown.enter.prevent="">
                </div>
            </div>

            <div class="row align-items-baseline">
                <label for="edit-vat-percent" class="col-3 text-secondary">ΦΠΑ</label>
                <div class="col">
                    <input wire:model.defer="editing_row.vat_percent" type="number" id="edit-vat-percent" class="form-control" @keydown.enter.prevent="">
                </div>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary wire:click.prevent="updateRow()">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</div>