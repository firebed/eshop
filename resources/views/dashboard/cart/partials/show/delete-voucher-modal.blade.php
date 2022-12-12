<form wire:submit.prevent="deleteVoucher({{ $currentVoucher->id }})">
    <x-bs::modal wire:model.defer="showDeleteVoucherModal">
        <x-bs::modal.header>Διαγραφή Voucher</x-bs::modal.header>

        <x-bs::modal.body class="bg-gray-100">
            <p>Πρόκειται να διαγράψετε τον κωδικό αποστολής;<br>Είστε σίγουροι;</p>
            @if($currentVoucher->is_manual)
                <div>
                    <x-bs::input.checkbox wire:model.defer="propagate_delete" id="propagate-delete-voucher">
                        Διαγραφή του voucher από τη βάση δεδομένων της courier. 
                    </x-bs::input.checkbox>
                </div>
            @endif
        </x-bs::modal.body>

        <x-bs::modal.footer>
            <x-bs::modal.close-button>Άκυρο</x-bs::modal.close-button>
            <x-bs::button.danger type="submit">Διαγραφή</x-bs::button.danger>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>