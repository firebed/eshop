<x-bs::modal wire:model.defer="showVoucherModal">
    <form wire:submit.prevent="saveVoucher">
        <x-bs::modal.header>Διαχείριση κωδικού αποστολής</x-bs::modal.header>
        <x-bs::modal.body>
            @if(isset($editingVoucher) && $editingVoucher->exists)
                <div class="alert alert-warning">
                    <strong>Προσοχή!</strong> Βεβαιωθείτε πως ο κωδικός που επεξεργάζεστε δεν ισχύει και δεν έχει καταχωρηθεί στη μεταφορική εταιρεία. Διαφορετικά, εάν δεν ακυρωθεί θα επιβαρυνθείτε με επιπλέον μεταφορικά έξοδα.
                </div>
            @endif

            <x-bs::input.group for="cart-voucher-courier" label="Εταιρεία" inline class="mb-2">
                <x-bs::input.select wire:model.defer="editingVoucher.courier_id" error="editingVoucher.courier_id" id="cart-voucher-courier">
                    @foreach(\Eshop\Services\Courier\Courier::cases() as $c)
                        <option value="{{ $c->value }}">{{ $c->label() }}</option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.group>

            <x-bs::input.group for="cart-voucher-alone" label="{{ __('Voucher') }}" inline class="mb-3">
                <x-bs::input.text wire:model.defer="editingVoucher.number" error="editingVoucher.number" id="cart-voucher-alone" autofocus/>
            </x-bs::input.group>

            <x-bs::input.checkbox wire:model.defer="saveOnMyShipping" id="saveOnMyShipping">Αποθήκευση voucher στο myShipping.gr</x-bs::input.checkbox>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </form>
</x-bs::modal>
