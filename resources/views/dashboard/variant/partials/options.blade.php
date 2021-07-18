<div class="d-grid gap-2">
    <div class="fw-500 mb-3">{{ __("eshop::product.variant_options") }}</div>

    <div class="d-grid gap-3" x-ref="options">
        @isset($variantTypes)
            @foreach($variantTypes as $id => $name)
                <x-bs::input.group for="option-{{ $id }}" label="{{ $name }}">
                    <x-bs::input.text
                            x-on:input.debounce="$dispatch('variant-options-updated', [...$refs.options.querySelectorAll('input')].map(i => i.value))"
                            name="options[{{ $id }}]"
                            value="{{ old('options.' . $id, $options[$id] ?? '') }}"
                            id="option-{{ $id }}"
                            error="options.{{ $id }}"
                            required
                    />
                </x-bs::input.group>
            @endforeach
        @endisset
    </div>
</div>
