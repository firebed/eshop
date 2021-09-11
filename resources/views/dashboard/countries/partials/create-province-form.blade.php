<div x-data="{province:{}}" x-on:edit-province.window="province = $event.detail" class="offcanvas offcanvas-end" tabindex="-1" id="create-province-form">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Προσθήκη νομού</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form x-data="{submitting: false }" x-on:submit="submitting = true" action="{{ route('countries.provinces.store', $country) }}" method="post" class="vstack gap-3">
            @csrf

            <x-bs::input.floating-label for="name" label="{{ __('Name') }}">
                <x-bs::input.text x-model="province.name" name="name" id="name" placeholder="{{ __('Name') }}"/>
            </x-bs::input.floating-label>

            <x-bs::input.checkbox x-model="province.shippable" id="shippable" name="shippable">
                {{ __('Visible') }}
            </x-bs::input.checkbox>

            <x-bs::button.primary x-bind:disabled="submitting" type="submit">{{ __("Save") }}</x-bs::button.primary>
        </form>
    </div>
</div>