<x-bs::modal>
    <x-bs::modal.header>{{ __('Move categories') }}</x-bs::modal.header>
    <x-bs::modal.body>
        <x-bs::input.group for="move-destination" label="{{ __('Move to folder') }}">
            <x-bs::input.select wire:model.defer="moveDestination">
                <option value="" disabled>{{ __("Select destination") }}</option>
                @foreach($moveDestinations as $destination)

                @endforeach
            </x-bs::input.select>
        </x-bs::input.group>
    </x-bs::modal.body>
    <x-bs::modal.footer>
        <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
        <x-bs::button.primary type="submit">{{ __("Move") }}</x-bs::button.primary>
    </x-bs::modal.footer>
</x-bs::modal>
