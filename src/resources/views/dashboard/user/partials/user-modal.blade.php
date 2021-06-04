<x-bs::modal wire:model.defer="showEditingModal">
    <x-bs::modal.header>{{ __('Edit user') }}</x-bs::modal.header>
    <x-bs::modal.body>
        <div class="row g-4">
            <x-bs::input.group for="first-name" label="{{ __('First name') }}" class="col">
                <x-bs::input.text wire:model.defer="model.first_name" error="model.first_name" id="first-name" placeholder="{{ __('First name') }}" autofocus/>
            </x-bs::input.group>

            <x-bs::input.group for="last-name" label="{{ __('Last name') }}" class="col">
                <x-bs::input.text wire:model.defer="model.last_name" error="model.last_name" id="last-name" placeholder="{{ __('Last name') }}" autofocus/>
            </x-bs::input.group>

            <x-bs::input.group for="email" label="{{ __('Email') }}" class="col-12">
                <x-bs::input.email wire:model.defer="model.email" error="model.email" id="email" placeholder="{{ __('Email') }}" autofocus/>
            </x-bs::input.group>

            <x-bs::input.group for="password" label="{{ __('Password') }}" class="col-12">
                <x-bs::input.text wire:model.defer="model.password" error="model.password" id="password" placeholder="{{ __('Password') }}" autofocus/>
            </x-bs::input.group>
        </div>
    </x-bs::modal.body>
    <x-bs::modal.footer>
        <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
        <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
    </x-bs::modal.footer>
</x-bs::modal>
