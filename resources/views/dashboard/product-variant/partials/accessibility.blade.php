<div class="d-grid gap-2">
    <div class="fw-500">{{ __("Accessibility") }}</div>

    <x-bs::input.checkbox wire:model.defer="variant.visible" id="visible">
        {{ __("Customers can view this variant") }}
    </x-bs::input.checkbox>

    <x-bs::input.checkbox wire:model.defer="variant.available" id="available">
        {{ __("Customers can purchase this variant") }}
    </x-bs::input.checkbox>
</div>
