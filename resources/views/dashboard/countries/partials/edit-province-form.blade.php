<div x-data="{action: '', province:{}}" x-on:edit-province.window="action = $event.detail.action; province = $event.detail.province" class="offcanvas offcanvas-end" tabindex="-1" id="edit-province-form">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Επεξεργασία νομού</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form x-data="{submitting: false }" x-on:submit="submitting = true" x-bind:action="action" method="post" class="vstack gap-3">
            @csrf
            @method('put')

            <x-bs::input.floating-label for="edit-name" label="{{ __('Name') }}">
                <x-bs::input.text x-model="province.name" name="name" id="edit-name" placeholder="{{ __('Name') }}"/>
            </x-bs::input.floating-label>

            <x-bs::input.checkbox x-model="province.shippable" id="edit-shippable" name="shippable">
                {{ __('Visible') }}
            </x-bs::input.checkbox>

            <x-bs::button.primary x-bind:disabled="submitting" type="submit">{{ __("Save") }}</x-bs::button.primary>
        </form>
    </div>
</div>