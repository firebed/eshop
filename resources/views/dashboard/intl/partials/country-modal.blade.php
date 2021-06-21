<x-bs::modal wire:model.defer="showEditingModal">
    <x-bs::modal.header>{{ __('Edit country') }}</x-bs::modal.header>
    <x-bs::modal.body>
        <div class="d-grid gap-2">
            <x-bs::input.group for="name" label="{{ __('Name') }}" inline>
                <x-bs::input.text wire:model.defer="model.name" error="model.name" autofocus/>
            </x-bs::input.group>

            <x-bs::input.group for="code" label="{{ __('Code') }}" inline>
                <x-bs::input.text wire:model.defer="model.code" error="model.code"/>
            </x-bs::input.group>

            <x-bs::input.group for="timezone" label="{{ __('Timezone') }}" inline>
                <x-bs::input.text wire:model.defer="model.timezone" error="model.timezone"/>
            </x-bs::input.group>

            <div class="row">
                <div class="col-4"></div>
                <div class="col">
                    <x-bs::input.checkbox wire:model.defer="model.visible" id="visible">
                        {{ __('Visible') }}
                    </x-bs::input.checkbox>
                </div>
            </div>
        </div>
    </x-bs::modal.body>
    <x-bs::modal.footer>
        <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
        <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
    </x-bs::modal.footer>
</x-bs::modal>
