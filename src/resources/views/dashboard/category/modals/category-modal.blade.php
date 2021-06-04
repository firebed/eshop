<x-bs::modal wire:model.defer="showCategoryModal">
    <x-bs::modal.header>{{ __('Edit category') }}</x-bs::modal.header>
    <x-bs::modal.body>
        <div class="d-grid gap-2">
            <x-bs::input.group for="type" label="{{ __('Type') }}" labelCol="3" inline>
                <x-bs::input.select wire:model="editing.type">
                    <option value="" disabled>{{ __('Select type') }}</option>
                    <option value="{{ \App\Models\Product\Category::FILE }}">{{ __('Category') }}</option>
                    <option value="{{ \App\Models\Product\Category::FOLDER }}" >{{ __('Category group') }}</option>
                </x-bs::input.select>
            </x-bs::input.group>

            <x-bs::input.group for="name" label="{{ __('Name') }}" labelCol="3" inline>
                <x-bs::input.text wire:model="name" id="name" error="name"/>
            </x-bs::input.group>

            <x-bs::input.group for="slug" label="{{ __('Slug') }}" labelCol="3" inline>
                <x-bs::input.text wire:model.defer="editing.slug" id="slug" error="editing.slug"/>
            </x-bs::input.group>

            <x-bs::input.group for="image" label="{{ __('Image') }}" labelCol="3" inline>
                <x-bs::input.file wire:model.defer="image" id="image" error="image"/>
            </x-bs::input.group>

            <div class="d-grid gap-1">
                <x-bs::input.checkbox wire:model.defer="editing.visible" id="visible" error="editing.visible">
                    {{ __("Customers can view this category") }}
                </x-bs::input.checkbox>

                <x-bs::input.checkbox wire:model.defer="editing.promote" id="promote" error="editing.promote">
                    {{ __("Promote this category") }}
                </x-bs::input.checkbox>
            </div>

            <x-bs::input.group for="description" label="{{ __('Description') }}">
                <x-bs::input.rich-text wire:model.defer="description" error="description" plugins="lists" toolbar="fontselect | bold italic underline | forecolor | bullist numlist"/>
            </x-bs::input.group>
        </div>
    </x-bs::modal.body>
    <x-bs::modal.footer>
        <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
        <x-bs::button.primary type="submit" wire:loading.attr="disabled" wire:target="image">
            <span wire:loading wire:target="image"><em class="fa fa-spinner fa-spin me-2"></em>{{ __('Uploading') }}</span>
            <span wire:loading.remove wire:target="image">{{ __("Save") }}</span>
        </x-bs::button.primary>
    </x-bs::modal.footer>
</x-bs::modal>
