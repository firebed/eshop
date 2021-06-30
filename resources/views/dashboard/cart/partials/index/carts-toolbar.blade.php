<div class="row">
    <div class="col d-flex gap-2">
        <x-bs::input.search wire:model="filter" placeholder="{{ __('Filter orders') }}"/>

        <x-bs::input.select wire:model="shipping_method_id">
            <option value="">{{ __("Shipping") }}</option>
            @foreach($shippingMethods as $method)
                <option value="{{ $method->id }}">{{ __($method->name) }}</option>
            @endforeach
        </x-bs::input.select>

        <x-bs::input.select wire:model="payment_method_id">
            <option value="">{{ __("Payment") }}</option>
            @foreach($paymentMethods as $method)
                <option value="{{ $method->id }}">{{ __($method->name) }}</option>
            @endforeach
        </x-bs::input.select>

        <x-bs::input.select wire:model="per_page" class="w-10r">
            @for($i = 20; $i <= 100; $i += 20)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </x-bs::input.select>
    </div>

    <div class="col d-flex gap-2 align-items-center">
        <x-bs::button.white wire:click="exportSelected" wire:loading.attr="disabled" wire:target="exportSelected" class="ms-auto">
            <em class="fa fa-file-excel text-green-500 me-2"></em> {{ __("Excel") }}
        </x-bs::button.white>

        <x-bs::dropdown>
            <x-bs::dropdown.button id="bulk-actions" class="btn-white shadow-sm">{{ __("Actions") }}</x-bs::dropdown.button>
            <x-bs::dropdown.menu button="bulk-actions">
                <x-bs::dropdown.item wire:click.prevent="editStatuses"><em class="fas fa-tasks me-2 text-secondary"></em>{{ __("Change status") }}</x-bs::dropdown.item>
                <x-bs::dropdown.divider/>
                <x-bs::dropdown.item wire:click.prevent="confirmDelete()"><em class="far fa-trash-alt me-2 text-secondary"></em>{{ __("Delete") }}</x-bs::dropdown.item>
            </x-bs::dropdown.menu>
        </x-bs::dropdown>
        
        <x-bs::button.primary wire:click.prevent="create()">
            <em class="fas fa-plus me-2"></em>{{ __("New") }}
        </x-bs::button.primary>
    </div>

    <form wire:submit.prevent="saveStatuses">
        <x-bs::modal wire:model.defer="showStatusModal">
            <x-bs::modal.header>Edit cart status</x-bs::modal.header>
            <x-bs::modal.body>
                <x-bs::group label="New status" inline>
                    <x-bs::input.select wire:model.defer="editing_status" error="editing_status">
                        <option value="" disabled>{{ __("Select status") }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ __($status->name) }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::group>
            </x-bs::modal.body>
            <x-bs::modal.footer>
                <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
                <x-bs::button.primary type="submit">
                    <em wire:loading class="fa fa-spinner fa-spin me-2"></em>{{ __("Save") }}
                </x-bs::button.primary>
            </x-bs::modal.footer>
        </x-bs::modal>
    </form>
</div>
