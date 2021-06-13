<x-bs::modal wire:model.defer="showEditingModal">
    <x-bs::modal.header>{{ __('Edit manufacturer') }}</x-bs::modal.header>
    <x-bs::modal.body>
        <div class="d-grid gap-2">
            <x-bs::input.group for="name" label="{{ __('Name') }}" inline>
                <x-bs::input.text wire:model.defer="model.name" error="model.name" id="model-name" autofocus/>
            </x-bs::input.group>

            <x-bs::input.group for="slug" label="{{ __('Slug') }}" inline>
                <x-bs::input.text wire:model.defer="model.slug" error="model.slug" id="model-slug"/>
            </x-bs::input.group>

            <x-bs::input.group for="image" label="{{ __('Image') }}" inline>
                <x-bs::input.image wire:model.defer="image" error="image" id="image"/>
            </x-bs::input.group>
        </div>
    </x-bs::modal.body>
    <x-bs::modal.footer>
        <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
        <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
    </x-bs::modal.footer>
</x-bs::modal>
