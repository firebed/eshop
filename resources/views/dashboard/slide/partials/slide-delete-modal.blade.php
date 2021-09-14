<form wire:submit.prevent="delete">
    <x-bs::modal wire:model.defer="showConfirmDeleteModal">
        <x-bs::modal.header>Διαγραφή διαφάνειας</x-bs::modal.header>

        <x-bs::modal.body class="d-grid gap-3">
            Διαγραφή διαφάνειας;
        </x-bs::modal.body>

        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.danger type="submit">{{ __("Delete") }}</x-bs::button.danger>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>