<div class="col-12 col-xxl-9 p-4 mx-auto d-grid gap-3">
    <h1 class="fs-3 mb-0">{{ __("Users") }}</h1>

    <div class="row row-cols-1 row-cols-sm-2 g-2">
        <div class="col">
            <x-bs::input.search wire:model="search" placeholder="{{ __('Search') }} {{ $users->total() }} users..."/>
        </div>

        <div class="col d-flex justify-content-between justify-content-sm-end align-items-center gap-2">
            <x-bs::dropdown>
                <x-bs::dropdown.button class="btn-white" id="bulk-actions">{{ __('Actions') }}</x-bs::dropdown.button>
                <x-bs::dropdown.menu button="bulk-actions">
                    <x-bs::dropdown.item wire:click.prevent="confirmDelete()"><em class="far fa-trash-alt w-2r text-gray-600"></em>{{ __('Delete') }}</x-bs::dropdown.item>
                </x-bs::dropdown.menu>
            </x-bs::dropdown>

            <x-bs::button.white wire:click="exportSelected" wire:loading.attr="disabled" wire:target="exportSelected">
                <em class="fa fa-file-excel text-green-500 me-2"></em> {{ __("Excel") }}
            </x-bs::button.white>

            <x-bs::button.primary wire:click="create()" wire:loading.attr="disabled" wire:target="create">
                <em class="fa fa-plus me-2"></em> {{ __("New") }}
            </x-bs::button.primary>
        </div>
    </div>

    <x-bs::card>
        @include('dashboard.user.partials.users-table')
    </x-bs::card>

    <form wire:submit.prevent="save">
        @include('dashboard.user.partials.user-modal')
    </form>

    <form wire:submit.prevent="delete">
        @include('dashboard.user.partials.user-delete-modal')
    </form>
</div>
