<x-bs::modal wire:model.defer="showOperatorsModal">
    <x-bs::modal.body>
        <div class="d-grid gap-3">
            <div class="fs-4 text-secondary">{{ __("Change operators") }}</div>
            <div class="vstack gap-1">
                @foreach($employees as $employee)
                    <x-bs::input.checkbox wire:model.defer="operator_ids" value="{{ $employee->id }}" id="operator-{{ $employee->id }}">
                        {{ $employee->full_name }}
                    </x-bs::input.checkbox>
                @endforeach
            </div>
            <div>
                <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                <x-bs::button.primary type="submit" class="ms-2 px-3">{{ __("Save") }}</x-bs::button.primary>
            </div>
        </div>
    </x-bs::modal.body>
</x-bs::modal>