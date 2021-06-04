<div class="card shadow-sm">
    <div class="card-body">
        <x-bs::input.group for="name" label="{{ __('Name') }}">
            <x-bs::input.text wire:model.defer="name" id="name" error="name"/>
        </x-bs::input.group>
        @if($isCreating)
            <x-bs::input.radio wire:model="category.type" id="category-file" name="category_type" error="category.type">
                {{ __("This category contains only products") }}
            </x-bs::input.radio>
            <x-bs::input.radio wire:model="category.type" id="category-folder" name="category_folder" error="category.type">
                {{ __("This category contains only subcategories") }}
            </x-bs::input.radio>
        @endif
    </div>
</div>
