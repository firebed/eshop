<div class="col-12 col-xxl-8 p-4 mx-auto d-grid gap-3">
    <div class="d-grid gap-1">
        @include('eshop::dashboard.category.partials.category-breadcrumbs')

        <div class="row row-cols-1 row-cols-sm-2 justify-content-between align-items-center g-3">
            <h1 class="col fs-3 mb-0">{{ __("Categories") }}</h1>

            <div class="col d-flex justify-content-between justify-content-sm-end gap-2">
                <x-bs::dropdown>
                    <x-bs::dropdown.button class="btn-white" id="bulk-actions">{{ __('Actions') }}</x-bs::dropdown.button>
                    <x-bs::dropdown.menu button="bulk-actions">
                        <x-bs::dropdown.item><em class="fa fa-arrows-alt me-2 text-secondary w-1r"></em>{{ __('Move') }}</x-bs::dropdown.item>
                        <x-bs::dropdown.divider/>
                        <x-bs::dropdown.item wire:click.prevent="confirmDelete()"><em class="far fa-trash-alt me-2 text-secondary w-1r"></em>{{ __('Delete') }}</x-bs::dropdown.item>
                    </x-bs::dropdown.menu>
                </x-bs::dropdown>

                <x-bs::dropdown>
                    <x-bs::dropdown.button class="btn-primary" id="create"><em class="fa fa-plus me-2"></em>{{ __('New') }}</x-bs::dropdown.button>
                    <x-bs::dropdown.menu button="create" alignment="right">
                        <x-bs::dropdown.item wire:click.prevent="create()"><em class="fa fa-file-archive me-2 text-secondary w-1r"></em>{{ __('Category') }}</x-bs::dropdown.item>
                        <x-bs::dropdown.item wire:click.prevent="createGroup()"><em class="fa fa-folder me-2 text-warning w-1r"></em>{{ __('Category group') }}</x-bs::dropdown.item>
                    </x-bs::dropdown.menu>
                </x-bs::dropdown>
            </div>
        </div>
    </div>

    <x-bs::card>
        @include('eshop::dashboard.category.partials.categories-table')
    </x-bs::card>

    <form wire:submit.prevent="save">
        @include('eshop::dashboard.category.modals.category-modal')
    </form>

    <form wire:submit.prevent="delete">
        <x-bs::modal wire:model.defer="showConfirmDelete">
            <x-bs::modal.body>
                <div class="d-grid gap-3 text-center">
                    <div><em class="far fa-trash-alt fa-5x text-red-400"></em></div>
                    <div class="fs-4 text-secondary">{{ __("Are you sure?") }}</div>
                    <div class="text-secondary">{{ __("Are you sure you want to delete the selected categories? This action cannot be undone.") }}</div>
                    <div>
                        <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                        <x-bs::button.danger type="submit" class="ms-2 px-3">{{ __("Delete") }}</x-bs::button.danger>
                    </div>
                </div>
            </x-bs::modal.body>
        </x-bs::modal>
    </form>
</div>
