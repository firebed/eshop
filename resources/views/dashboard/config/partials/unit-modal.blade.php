<x-bs::modal wire:model.defer="showEditingModal">
    <x-bs::modal.header>{{ __('Edit unit') }}</x-bs::modal.header>

    <x-bs::modal.body>
        <div class="d-grid gap-2">
            <x-bs::input.group for="name" label="{{ __('Name') }}" inline>
                <x-bs::input.text wire:model.defer="model.name" error="model.name" autofocus/>
            </x-bs::input.group>
        </div>
    </x-bs::modal.body>

    <x-bs::modal.footer>
        <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
        <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
    </x-bs::modal.footer>
</x-bs::modal>
