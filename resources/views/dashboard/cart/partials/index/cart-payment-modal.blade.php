<x-bs::modal wire:model.defer="showMarkAsPaidModal">
    <x-bs::modal.header>{{ __('Mark as paid') }}</x-bs::modal.header>
    <x-bs::modal.body>
        <div class="d-grid gap-3">
            Επισήμανση των επιλεγμένων παραγγελιών ως πληρωμένη;
            <div>
                <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                <x-bs::button.primary type="submit" class="ms-2 px-3">{{ __("Save") }}</x-bs::button.primary>
            </div>
        </div>
    </x-bs::modal.body>
</x-bs::modal>