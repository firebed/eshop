<form wire:submit.prevent="save">
    <x-bs::modal size="lg" wire:model.defer="showCashPaymentsModal">
        <x-bs::modal.body>
            <div class="d-grid gap-3">
                <div class="fs-4 text-secondary">{{ __("Cash payments") }}</div>

                <x-bs::input.select wire:model="shipping_method_id">
                    @foreach($shippingMethods as $sp)
                        <option value="{{ $sp->id }}">{{ __("eshop::shipping.$sp->name") }}</option>
                    @endforeach
                </x-bs::input.select>

                <x-bs::input.file wire:model="files" multiple accept=".csv, .xls, .xlsx"/>

                <div>
                    @error('files')
                    <div class="fw-bold text-danger text-center border border-danger rounded p-2">{{ $message }}</div>
                    @enderror
                    @error('error')
                    <div class="fw-bold text-danger text-center border border-danger rounded p-2">{{ $message }}</div>
                    @enderror
                    @if(empty($this->files))
                        <div class="text-secondary text-center border border-secondary rounded p-2">
                            Παρακαλώ επιλέξτε τα αρχεία για την απόδοση αντικαταβολών.
                        </div>
                    @elseif($errors->isEmpty())
                        <div class="fw-bold text-success text-center border border-success rounded p-2">
                            {{ sprintf("Βρέθηκαν %d voucher σε %d αρχεία", $total_vouchers, $total_files) }}
                        </div>
                    @endif
                </div>

                <div class="table-responsive scrollbar" style="min-height: 30px; max-height: 200px;">
                    <x-bs::table size="sm" class="small">
                        <thead>
                        <tr>
                            <th>Voucher</th>
                            <th class="text-end">Σύνολο</th>
                            <th class="text-end">Κατάσταση</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($vouchers as $voucher => $total)
                            @php($cart = $carts[$voucher] ?? null)

                            <tr>
                                <td>
                                    @if($cart)
                                        <a href="{{ route('carts.show', $cart->id) }}" target="_blank">{{ $voucher }}</a>
                                    @else
                                        {{ $voucher }}
                                    @endif
                                </td>

                                <td class="text-end">
                                    @if($cart === null)
                                        {{ format_currency($total) }}
                                    @elseif($this->equalFloats($cart->total, $total))
                                        {{ format_currency($total) }}
                                    @else
                                        {{ format_currency($total) }} <span class="fw-bold text-danger">({{ format_currency($cart->total) }})</span>
                                    @endif
                                </td>

                                @if($cart !== null && $cart->payment === null && $this->equalFloats($cart->total, $total))
                                    <td class="text-end"><em class="fas fa-arrow-right text-secondary" title="Προς απόδοση"></em></td>
                                @elseif($cart !== null && $cart->payment !== null && $this->equalFloats($cart->total, $total))
                                    <td class="text-end"><em class="fas fa-check-circle text-success" title="Ήδη πληρωμένο"></em></td>
                                @elseif($cart === null)
                                    <td class="text-end"><em class="fas fa-times-circle text-danger" title="Δεν βρέθηκε στο eshop"></em></td>
                                @elseif(!$this->equalFloats($cart->total ?? 0, $total))
                                    <td class="text-end"><em class="fas fa-times-circle text-danger" title="Τα σύνολα δεν ταιριάζουν"></em></td>
                                @else
                                    <td class="text-end">-</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </x-bs::table>
                </div>

                <x-bs::table size="sm" class="small">
                    <tr>
                        <td class="fw-bold">Αναγνωρισμένα από το σύστημα:</td>
                        <td colspan="3" class="text-end">
                            @if(count($files) === 0)
                                -
                            @else
                                {{ sprintf("%d από %d", $carts->count(), $total_vouchers) }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-bold">Προς απόδοση:</td>
                        <td colspan="3" class="text-end">
                            @if(count($files) === 0)
                                -
                            @else
                                {{ sprintf("%d από %d", $valid_carts->count(), $total_vouchers) }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-bold">Συνολική αξία προς απόδοση:</td>
                        <td colspan="3" class="text-end">{{ format_currency($valid_carts->sum('total')) }}</td>
                    </tr>
                </x-bs::table>

                <div class="d-flex justify-content-between">
                    <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                    <x-bs::button.primary wire:loading.attr="disabled" type="submit" class="ms-2 px-3">
                        <span wire:loading.remove>{{ __("Save") }}</span>
                        <span wire:loading><em class="fas fa-spinner fa-spin"></em></span>
                    </x-bs::button.primary>
                </div>
            </div>
        </x-bs::modal.body>
    </x-bs::modal>
</form>
