<div class="vstack gap-3">
    <div class="row row-cols-1 row-cols-md-2 g-2">
        <div class="col d-grid d-sm-flex gap-2">
            <x-bs::input.select wire:model="country">
                <option value="">{{ __('All countries') }}</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </x-bs::input.select>

            <x-bs::input.text wire:model="postcode" placeholder="TK" />
        </div>

        <div class="col d-flex justify-content-end gap-2">
            <x-bs::dropdown>
                <x-bs::dropdown.button class="btn-white" id="bulk-actions">{{ __('Actions') }}</x-bs::dropdown.button>
                <x-bs::dropdown.menu button="bulk-actions">
                    <x-bs::dropdown.item wire:click.prevent="confirmDelete()"><em class="far fa-trash-alt me-2"></em>{{ __('Delete') }}</x-bs::dropdown.item>
                </x-bs::dropdown.menu>
            </x-bs::dropdown>

            <x-bs::button.primary wire:click="create()" wire:loading.attr="disabled" wire:target="create">
                <em class="fa fa-plus me-2"></em> {{ __("New") }}
            </x-bs::button.primary>
        </div>
    </div>

    <x-bs::card>
        <div class="table-responsive">
            @include('eshop::dashboard.shipping-methods.wire.inaccessible-areas-table')
        </div>
    </x-bs::card>

    @include('eshop::dashboard.shipping-methods.wire.inaccessible-area-modal')

    <form wire:submit.prevent="delete">
        <x-bs::modal wire:model.defer="showConfirmDelete">
            <x-bs::modal.body>
                <div class="d-grid gap-3 text-center">
                    <div><em class="far fa-trash-alt fa-5x text-red-400"></em></div>
                    <div class="fs-4 text-secondary">{{ __("Are you sure?") }}</div>
                    <div class="text-secondary">{{ __("Are you sure you want to delete the selected areas? This action cannot be undone.") }}</div>
                    <div>
                        <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                        <x-bs::button.danger type="submit" class="ms-2 px-3">{{ __("Delete") }}</x-bs::button.danger>
                    </div>
                </div>
            </x-bs::modal.body>
        </x-bs::modal>
    </form>

</div>
