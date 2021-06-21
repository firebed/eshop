<x-bs::modal wire:model.defer="showConfirmDelete">
    <x-bs::modal.body>
        <div class="d-grid gap-3 text-center">
            <div><em class="far fa-trash-alt fa-5x text-red-400"></em></div>
            <div class="fs-4 text-secondary">{{ __("Are you sure?") }}</div>
            <div class="text-secondary">{{ __("Are you sure you want to delete the selected users? This action cannot be undone.") }}</div>
            <div>
                <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                <x-bs::button.danger type="submit" class="ms-2 px-3">{{ __("Delete") }}</x-bs::button.danger>
            </div>
        </div>
    </x-bs::modal.body>
</x-bs::modal>
