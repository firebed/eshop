<div class="col-12 col-xxl-8 p-4 mx-auto d-grid gap-3">
    <h1 class="fs-3 mb-0">{{ __("Countries") }}</h1>

    <div class="row row-cols-1 row-cols-sm-2 g-2">
        <div class="col d-flex gap-2">
            <x-bs::input.search wire:model="search" placeholder="{{ __('Search') }}"/>

            <x-bs::input.select wire:model="visibility" class="w-8r">
                <option value="">All</option>
                <option value="1">Visible</option>
                <option value="0">Hidden</option>
            </x-bs::input.select>
        </div>

        <div class="col d-flex justify-content-between justify-content-sm-end align-items-center gap-2">
            <x-bs::dropdown>
                <x-bs::dropdown.button class="btn-white" id="bulk-actions">{{ __('Actions') }}</x-bs::dropdown.button>
                <x-bs::dropdown.menu button="bulk-actions">
                    <x-bs::dropdown.item wire:click.prevent="setVisibility(true)"><em class="fa fa-eye w-2r text-gray-600"></em>{{ __('Show') }}</x-bs::dropdown.item>
                    <x-bs::dropdown.item wire:click.prevent="setVisibility(false)"><em class="fa fa-eye-slash w-2r text-gray-600"></em>{{ __('Hide') }}</x-bs::dropdown.item>
                    <x-bs::dropdown.divider/>
                    <x-bs::dropdown.item wire:click.prevent="confirmDelete()"><em class="far fa-trash-alt w-2r text-gray-600"></em>{{ __('Delete') }}</x-bs::dropdown.item>
                </x-bs::dropdown.menu>
            </x-bs::dropdown>

            <x-bs::button.primary wire:click="create()" wire:loading.attr="disabled" wire:target="create">
                <em class="fa fa-plus me-2"></em> {{ __("New") }}
            </x-bs::button.primary>
        </div>
    </div>

    <x-bs::card>
        <div class="table-responsive">
            @include('eshop::dashboard.intl.partials.countries-table')
        </div>
    </x-bs::card>

    <form wire:submit.prevent="save">
        @include('eshop::dashboard.intl.partials.country-modal')
    </form>

    <form wire:submit.prevent="delete">
        <x-bs::modal wire:model.defer="showConfirmDelete">
            <x-bs::modal.body>
                <div class="d-grid gap-3 text-center">
                    <div><em class="far fa-trash-alt fa-5x text-red-400"></em></div>
                    <div class="fs-4 text-secondary">{{ __("Are you sure?") }}</div>
                    <div class="text-secondary">{{ __("Are you sure you want to delete the selected countries? This action cannot be undone.") }}</div>
                    <div>
                        <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                        <x-bs::button.danger type="submit" class="ms-2 px-3">{{ __("Delete") }}</x-bs::button.danger>
                    </div>
                </div>
            </x-bs::modal.body>
        </x-bs::modal>
    </form>
</div>
