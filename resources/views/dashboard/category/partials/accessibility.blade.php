<x-bs::card>
    <x-bs::card.body>
        <div class="fw-500 mb-3">{{ __("Accessibility") }}</div>

        <div class="d-grid gap-1">
            <x-bs::input.checkbox name="visible" :checked="old('visible', $category->visible ?? true)" id="visible" error="visible">
                {{ __("Customers can view this category") }}
            </x-bs::input.checkbox>

            <x-bs::input.checkbox name="promote" :checked="old('promote', $category->promote ?? false)" id="promote" error="promote">
                {{ __("Promote this category") }}
            </x-bs::input.checkbox>
        </div>
    </x-bs::card.body>
</x-bs::card>