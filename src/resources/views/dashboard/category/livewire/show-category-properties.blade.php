<div class="col-12 col-xxl-8 p-4 mx-auto d-grid gap-3">
    <div class="d-grid gap-1">
        @include('dashboard.category.partials.category-breadcrumbs')

        <div class="row row-cols-1 row-cols-sm-2 justify-content-between align-items-center g-3">
            <h1 class="col fs-3 mb-0">{{ __("Properties") }}</h1>

            <div class="col d-flex justify-content-between justify-content-sm-end gap-2">
                <x-bs::dropdown>
                    <x-bs::dropdown.button class="btn-white" id="bulk-actions">{{ __('Actions') }}</x-bs::dropdown.button>
                    <x-bs::dropdown.menu button="bulk-actions">
                        <x-bs::dropdown.item wire:click.prevent="delete()"><em class="far fa-trash-alt me-2 text-secondary w-1r"></em>{{ __('Delete') }}</x-bs::dropdown.item>
                    </x-bs::dropdown.menu>
                </x-bs::dropdown>

                <x-bs::button.primary wire:click="create" wire:loading.attr="disabled" wire:target="create"><em class="fa fa-plus me-2"></em>{{ __('New') }}</x-bs::button.primary>
            </div>
        </div>
    </div>

    <x-bs::card>
        <div class="table-responsive">
            @include('dashboard.category.partials.category-properties-table')
        </div>
    </x-bs::card>

    <form wire:submit.prevent="save">
        @include('dashboard.category.modals.category-property-modal')
    </form>

    <form wire:submit.prevent="saveChoices">
        @include('dashboard.category.modals.category-choices-modal')
    </form>
</div>
