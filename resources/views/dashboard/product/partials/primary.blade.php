<x-bs::card>
    <x-bs::card.body class="d-grid gap-3">
        <x-bs::input.group for="name" label="{{ __('Name') }}">
            <x-bs::input.text x-on:input.debounce="$dispatch('product-name-updated', $el.value.trim())" :value="old('name', $product->name ?? '')" name="name" error="name" id="name" placeholder="{{ __('Name') }}" autofocus required/>
        </x-bs::input.group>

        <x-bs::input.group for="description" label="{{ __('Description') }}">
            <x-eshop::rich-text x-effect="$dispatch('product-description-updated', text.trim())" :value="old('description', $product->description ?? '')" name="description" error="description" id="description" rows="10" plugins="lists code" toolbar="fontselect | bold italic underline | forecolor | bullist numlist | removeformat | code"/>
        </x-bs::input.group>
    </x-bs::card.body>
</x-bs::card>
