<x-bs::modal wire:model.defer="showModal">
    <x-bs::modal.header>{{ __('Edit property') }}</x-bs::modal.header>
    <x-bs::modal.body>
        <div class="d-grid gap-3"
             x-data="{ disableIndex: true, vr: @entangle('property.value_restriction').defer }"
             x-init="
                $watch('vr', v => disableIndex = v === 'None')
                $watch('disableIndex', v => { if (v) { $refs.noIndex.checked = true; $refs.noIndex.dispatchEvent(new Event('change')) } })
             ">

            <x-bs::input.group for="property-name" label="{{ __('Name') }}">
                <x-bs::input.text wire:model="name" id="property-name" error="name"/>
            </x-bs::input.group>

            <x-bs::input.group for="property-slug" label="{{ __('Slug') }}">
                <x-bs::input.text wire:model.defer="property.slug" id="property-slug" error="property.slug"/>
            </x-bs::input.group>

            <div class="d-grid gap-2">
                <div class="fw-500">{{ __("Value management") }}</div>

                <div class="d-grid gap-1">
                    <x-bs::input.radio x-ref="noRestriction" @change="disableIndex = true" wire:model.defer="property.value_restriction" name="restriction" id="no-value-restriction" value="None">
                        {{ __('Don\'t restrict values.') }}
                    </x-bs::input.radio>

                    <x-bs::input.radio @change="disableIndex = false" wire:model.defer="property.value_restriction" name="restriction" id="single-value-restriction" value="Simple">
                        {{ __('Restrict values and let products to use 1 value at maximum.') }}
                    </x-bs::input.radio>

                    <x-bs::input.radio @change="disableIndex = false" wire:model.defer="property.value_restriction" name="restriction" id="multiple-value-restriction" value="Multiple">
                        {{ __('Restrict values and let products to use multiple values.') }}
                    </x-bs::input.radio>
                </div>
            </div>

            <div class="d-grid gap-2">
                <div class="fw-500">{{ __("Indexing") }} <em x-show="disableIndex" class="fa fa-exclamation-circle text-warning me-2"></em></div>

                <div class="d-grid gap-1">
                    <x-bs::input.radio x-ref="noIndex" wire:model.defer="property.index" name="selection" id="index" value="None">
                        {{ __('Prevent customers from filtering products.') }}
                    </x-bs::input.radio>

                    <x-bs::input.radio x-bind:disabled="disableIndex" wire:model.defer="property.index" name="selection" id="single-selection" value="Simple">
                        {{ __('Allow customers to filter products having 1 selection at maximum.') }}
                    </x-bs::input.radio>

                    <x-bs::input.radio x-bind:disabled="disableIndex" wire:model.defer="property.index" name="selection" id="multiple-selection" value="Multiple">
                        {{ __('Allow customers to filter products having multiple selections.') }}
                    </x-bs::input.radio>
                </div>
            </div>

            <div class="d-grid gap-2">
                <div class="fw-500">{{ __("Accessibility to customers") }}</div>

                <div class="d-grid">
                    <x-bs::input.checkbox wire:model.defer="property.visible" id="property-visible">{{ __('Show this property on the product page.') }}</x-bs::input.checkbox>
                    <x-bs::input.checkbox wire:model.defer="property.promote" id="property-promote">{{ __('Show this property on the category preview.') }}</x-bs::input.checkbox>
                    <x-bs::input.checkbox wire:model.defer="property.show_empty_value" id="show-empty-value">{{ __('Show empty values.') }}</x-bs::input.checkbox>
                    <x-bs::input.checkbox wire:model.defer="property.show_caption" id="show-label">{{ __('Show caption along with value.') }}</x-bs::input.checkbox>
                </div>
            </div>
        </div>
    </x-bs::modal.body>
    <x-bs::modal.footer>
        <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
        <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
    </x-bs::modal.footer>
</x-bs::modal>
