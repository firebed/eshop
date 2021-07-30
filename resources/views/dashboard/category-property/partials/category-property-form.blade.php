<x-bs::card>
    <x-bs::card.body class="d-grid gap-3" x-data="{
        updateSlug() {
            $refs.slug.value = slugifyLower($refs.name.value)
        }
    }">
        <x-bs::input.group for="name" label="{{ __('eshop::category.name') }}">
            <x-bs::input.text x-ref="name" x-on:input="updateSlug()" value="{{ old('name', $property->name ?? '') }}" name="name" error="name" placeholder="{{ __('eshop::category.placeholders.name') }}" required/>
        </x-bs::input.group>

        <x-bs::input.group for="slug" label="{{ __('Slug') }}">
            <x-bs::input.text x-ref="slug" value="{{ old('slug', $property->slug ?? '') }}" name="slug" error="slug" required/>
        </x-bs::input.group>

        <x-bs::input.checkbox :checked="old('visible', $property->visible ?? true)" name="visible" error="visible" id="visible">
            {{ __('eshop::category.visible') }}
        </x-bs::input.checkbox>

        <div class="d-grid gap-1">
            <div class="small text-secondary">{{ __('eshop::category.property_type') }}</div>

            <x-bs::input.radio name="type" :checked="old('type', $property->type ?? 'checkbox') === 'checkbox'" value="checkbox" id="checkbox" required>{{ __('eshop::category.property_checkbox') }}</x-bs::input.radio>
            <div class="small text-secondary ps-4">{{ __('eshop::category.property_checkbox_info') }}</div>

            <x-bs::input.radio name="type" :checked="old('type', $property->type ?? '') === 'radio'" value="radio" id="radio" required>{{ __('eshop::category.property_radio') }}</x-bs::input.radio>
            <div class="small text-secondary ps-4">{{ __('eshop::category.property_radio_info') }}</div>
        </div>
    </x-bs::card.body>
</x-bs::card>