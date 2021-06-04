<x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap">
    <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100">
        <span>{{ __('Notes') }}</span>
        <x-bs::button.link class="d-none d-xxl-block p-0" wire:click="$toggle('showModal')">{{ __("Edit") }}</x-bs::button.link>
    </x-bs::navbar.brand>

    <x-bs::navbar.toggler target="customer-notes"/>

    <x-bs::navbar.collapse id="customer-notes">
        <div class="text-secondary">{{ !empty($notes) ? $notes : __("No notes from customer") }}</div>

        <form wire:submit.prevent="save">
            <x-bs::modal wire:model.defer="showModal">
                <x-bs::modal.header>{{ __('Customer notes') }}</x-bs::modal.header>
                <x-bs::modal.body>
                    <x-bs::input.group for="details" label="{{ __('Customer notes') }}"/>
                    <x-bs::input.textarea wire:model.defer="notes" id="details" cols="30" rows="5"/>
                </x-bs::modal.body>
                <x-bs::modal.footer>
                    <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
                    <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
                </x-bs::modal.footer>
            </x-bs::modal>
        </form>
    </x-bs::navbar.collapse>
</x-bs::navbar>
