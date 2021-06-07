<x-bs::card>
    <x-bs::card.body class="d-grid gap-3">
        <x-bs::input.group for="name" label="{{ __('Name') }}">
            <x-bs::input.text wire:model="name" id="name" error="name" placeholder="{{ __('Name') }}" autofocus/>
        </x-bs::input.group>

        <x-bs::input.group for="slug" label="{{ __('Slug') }}">
            <x-bs::input.text wire:model.defer="product.slug" id="slug" error="product.slug" placeholder="{{ __('Slug') }}"/>
        </x-bs::input.group>

        <x-bs::input.group for="description" label="{{ __('Description') }}">
            <x-bs::input.rich-text wire:model.defer="description" id="description" rows="8" error="description"  plugins="lists" toolbar="fontselect | bold italic underline | forecolor | bullist numlist | removeformat"/>
        </x-bs::input.group>
    </x-bs::card.body>
</x-bs::card>
