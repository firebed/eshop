<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showModal">
        <x-bs::modal.header>{{ __('Customer') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="d-grid gap-3">
                <div class="row">
                    <x-bs::input.group for="contact-first-name" label="{{ __('First name') }}" class="col">
                        <x-bs::input.text wire:model.defer="contact.first_name" id="contact-first-name" error="contact.first_name"/>
                    </x-bs::input.group>

                    <x-bs::input.group for="contact-last-name" label="{{ __('Last name') }}" class="col">
                        <x-bs::input.text wire:model.defer="contact.last_name" id="contact-last-name" error="contact.last_name"/>
                    </x-bs::input.group>
                </div>

                <x-bs::input.group for="contact-email" label="{{ __('Email address') }}">
                    <x-bs::input.email wire:model.defer="contact.email" id="contact-email" error="contact.email"/>
                </x-bs::input.group>

                <x-bs::input.group for="contact-phone" label="{{ __('Phone number') }}">
                    <x-bs::input.text wire:model.defer="contact.phone" id="contact-phone" error="contact.phone"/>
                </x-bs::input.group>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
