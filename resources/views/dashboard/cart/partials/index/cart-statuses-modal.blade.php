<x-bs::modal wire:model.defer="showStatusModal">
    <x-bs::modal.header>{{ __('Change status') }}</x-bs::modal.header>
    <x-bs::modal.body>
        <div class="d-grid gap-3">
            <x-bs::input.group for="status" label="{{ __('Change status') }}">
                <x-bs::input.select wire:model.defer="editing_status">
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ __("eshop::cart.status." . $status->name) }}</option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.group>
            <div>
                <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                <x-bs::button.primary type="submit" class="ms-2 px-3">{{ __("Save") }}</x-bs::button.primary>
            </div>
        </div>
    </x-bs::modal.body>
</x-bs::modal>