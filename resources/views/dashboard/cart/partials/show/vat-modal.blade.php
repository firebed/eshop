<form wire:submit.prevent="saveVat">
    <x-bs::modal wire:model.defer="showVatModal">
        <x-bs::modal.header>Αλλαγή ΦΠΑ</x-bs::modal.header>
        <x-bs::modal.body>
            <x-bs::input.group label="ΦΠΑ" for="global-vat" inline>
                <x-bs::input.percentage wire:model.defer="global_vat" min="0" max="100" id="global-vat" error="global_vat" autofocus/>
            </x-bs::input.group>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
