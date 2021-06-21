<div class="card shadow-sm">
    <div class="card-body">
        <div class="fs-5 mb-3">{{ __("Accessibility") }}</div>
        <div class="d-grid gap-2">
            <x-bs::input.checkbox wire:model.defer="category.visible" id="visible" error="category.visible">
                {{ __("Customers can view this category") }}
            </x-bs::input.checkbox>

            <x-bs::input.checkbox wire:model.defer="category.promote" id="promote" error="category.promote">
                {{ __("Promote this category") }}
            </x-bs::input.checkbox>
        </div>
    </div>
</div>
