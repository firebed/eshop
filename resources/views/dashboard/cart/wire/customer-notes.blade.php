<div>
    <x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap {{ filled($notes) ? 'bg-yellow-100' : '' }}">
        <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100">{{ __('Notes') }}</x-bs::navbar.brand>

        <x-bs::navbar.toggler target="customer-notes"/>

        <x-bs::navbar.collapse id="customer-notes">
            <div class="vstack">
                <a href="#" class="text-decoration-none" wire:click="$toggle('showModal')">{{ __("Edit") }}</a>

                <div class="mt-3">
                    @if(filled($notes))
                        <strong>{{ $notes }}</strong>
                    @else
                        <span class="text-secondary">{{ __("No notes from customer") }}</span>
                    @endif
                </div>

                @if(filled($comments))
                    <div class="mt-3 pt-3 border-top">
                        <div class="fw-bold">Κρυφές σημειώσεις</div>
                        <div>{{ $comments }}</div>
                    </div>
                @endif
            </div>
        </x-bs::navbar.collapse>
    </x-bs::navbar>

    <form wire:submit.prevent="save">
        <x-bs::modal wire:model.defer="showModal">
            <x-bs::modal.header>{{ __('Customer notes') }}</x-bs::modal.header>
            <x-bs::modal.body>
                <x-bs::input.group for="details" label="{{ __('Customer notes') }}"/>
                <x-bs::input.textarea wire:model.defer="notes" id="details" cols="30" rows="5"/>

                <div class="mt-3">
                    <x-bs::input.group for="details" label="Κρυφές σημειώσεις"/>
                    <x-bs::input.textarea wire:model.defer="comments" id="comments" cols="30" rows="5"/>
                </div>
            </x-bs::modal.body>
            <x-bs::modal.footer>
                <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
                <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
            </x-bs::modal.footer>
        </x-bs::modal>
    </form>
</div>