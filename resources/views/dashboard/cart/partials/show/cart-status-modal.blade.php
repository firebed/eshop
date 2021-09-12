<x-bs::modal wire:model.defer="showStatusModal">
    <form wire:submit.prevent="saveStatus">
        <x-bs::modal.header>{{ __('Edit cart status') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="d-grid gap-3" x-data="{ notify: @entangle('notify_customer').defer }">
                @if($status)
                    <div class="d-flex align-items-center">
                        <x-bs::badge type="{{ $status->color }}" class="rounded-pill p-2 w-8r">{{ $status->name ?? '' }}</x-bs::badge>
                        <em class="fa fa-arrow-right mx-3"></em>
                        <x-bs::badge type="{{ $new_status->color ?? '' }}" class="rounded-pill p-2 w-8r">{{ $new_status->name ?? '' }}</x-bs::badge>
                    </div>
                @endif

                <x-bs::input.checkbox x-model="notify" id="notify-customer">
                    {{ __("Notify customer via email") }}
                </x-bs::input.checkbox>

                <x-bs::input.group for="notes-to-customer" label="{{ __('Notes to customer') }}">
                    <x-bs::input.textarea x-bind:disabled="!notify" wire:model.defer="notes_to_customer" :disabled="!$notify_customer" id="notes-to-customer" cols="30" rows="5" autofocus placeholder="{{ __('Enter message here') }}"/>
                </x-bs::input.group>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </form>
</x-bs::modal>
