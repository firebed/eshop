<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showEditingModal">
        <x-bs::modal.header>Επεξεργασία διαφάνειας</x-bs::modal.header>

        <x-bs::modal.body class="d-grid gap-3">
            <input type="hidden" wire:model.defer="slide_id">

            <x-bs::input.group for="image" label="Εικόνα">
                <x-bs::input.file wire:model.defer="image" error="image" id="image" accept="image/*"/>
                <div class="small mt-1 text-secondary">Ελάχιστο μέγεθος: 960x540, Αναλογία: 16/9</div>
            </x-bs::input.group>

            <x-bs::input.group for="link" label="Σύνδεσμος">
                <x-bs::input.text wire:model.defer="link" error="link" id="link"/>
            </x-bs::input.group>
        </x-bs::modal.body>

        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>