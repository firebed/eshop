<x-bs::card class="h-100">
    <x-bs::card.body>
        <h2 class="fs-5">{{ __("Locales") }}</h2>

        <div class="col d-flex justify-content-between justify-content-sm-end align-items-center gap-2 mb-3">
            <x-bs::dropdown>
                <x-bs::dropdown.button class="btn-white" id="bulk-actions"><em class="fas fa-bars text-secondary"></em></x-bs::dropdown.button>
                <x-bs::dropdown.menu button="bulk-actions" alignment="right">
                    <x-bs::dropdown.item wire:click.prevent="confirmDelete()"><em class="far fa-trash-alt w-2r text-gray-600"></em>{{ __('Delete') }}</x-bs::dropdown.item>
                </x-bs::dropdown.menu>
            </x-bs::dropdown>

            <x-bs::button.primary wire:click="create()" wire:loading.attr="disabled" wire:target="create"><em class="fa fa-plus"></em></x-bs::button.primary>
        </div>

        <x-bs::table>
            <thead>
            <tr>
                <td class="w-1r"><x-bs::input.checkbox wire:model="selectAll" id="check-all"/></td>
                <td>{{ __("Name") }}</td>
                <td>&nbsp;</td>
            </tr>
            </thead>

            <tbody>
            @forelse($locales as $locale)
                <tr wire:key="row-{{ $locale->id }}" wire:loading.class.delay="opacity-50" wire:target="save, edit, delete">
                    <td class="align-middle">
                        <x-bs::input.checkbox wire:model="selected" value="{{ $locale->id }}" id="cb-{{ $locale->id }}"/>
                    </td>
                    <td>{{ $locale->name }}</td>
                    <td class="text-end">
                        <a href="#" wire:click.prevent="edit({{ $locale->id }})"><em class="far fa-edit"></em></a>
                    </td>
                </tr>
            @empty
                <tr wire:key="no-records-found">
                    <td colspan="8" class="text-center py-4 fst-italic text-secondary">{{ __("No records found") }}</td>
                </tr>
            @endforelse
            </tbody>

            <caption>
                <x-eshop::wire-pagination :paginator="$locales"/>
            </caption>
        </x-bs::table>
    </x-bs::card.body>

    <form wire:submit.prevent="save">
        @include('eshop::dashboard.config.partials.locale-modal')
    </form>

    <form wire:submit.prevent="delete">
        <x-bs::modal wire:model.defer="showConfirmDelete">
            <x-bs::modal.body>
                <div class="d-grid gap-3 text-center">
                    <div><em class="far fa-trash-alt fa-5x text-red-400"></em></div>
                    <div class="fs-4 text-secondary">{{ __("Are you sure?") }}</div>
                    <div class="text-secondary">{{ __("Are you sure you want to delete the selected locales? This action cannot be undone.") }}</div>
                    <div>
                        <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                        <x-bs::button.danger type="submit" class="ms-2 px-3">{{ __("Delete") }}</x-bs::button.danger>
                    </div>
                </div>
            </x-bs::modal.body>
        </x-bs::modal>
    </form>
</x-bs::card>