<div class="card shadow-sm">
    <div class="card-body d-grid gap-3">
        <div class="fs-5">{{ __("Variants") }}</div>

        <div>{{ __('eshop::product.has_variants') }}</div>

        <div class="table-responsive">
            <x-bs::table>
                <thead>
                <x-bs::table.heading>{{ __("Name") }}</x-bs::table.heading>
                <x-bs::table.heading>&nbsp;</x-bs::table.heading>
                </thead>

                <tbody>
                @foreach($variantTypes as $type)
                    <tr wire:key="row-{{ $type->id }}">
                        <td class="align-middle">{{ $type->name }}</td>
                        <td class="text-end">
                            <x-bs::button.haze size="sm" wire:loading.attr="disabled" wire:target="edit({{ $type->id }})" wire:click="edit({{ $type->id }})">
                                <i class="far fa-edit"></i>
                            </x-bs::button.haze>

                            <x-bs::button.light size="sm" wire:click="confirmDelete({{ $type->id }})" wire:loading.attr="disabled" wire:target="confirmDelete({{ $type->id }})">
                                <i class="far fa-trash-alt"></i>
                            </x-bs::button.light>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </x-bs::table>
        </div>

        <div class="d-flex">
            <x-bs::button.haze size="sm" wire:loading.attr="disabled" wire:target="create" wire:click="create">
                {{ __("Add new type") }}
            </x-bs::button.haze>
        </div>

        <x-bs::modal wire:model.defer="showModal">
            <form wire:submit.prevent="save">
                <x-bs::modal.header>{{ __('Edit variant type') }}</x-bs::modal.header>
                <x-bs::modal.body>
                    <x-bs::input.group for="variant-type-name" label="{{ __('Variant name') }}">
                        <x-bs::input.text wire:model.defer="editing.name" id="variant-type-name" error="editing.name" autofocus/>
                    </x-bs::input.group>
                </x-bs::modal.body>
                <x-bs::modal.footer>
                    <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
                    <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
                </x-bs::modal.footer>
            </form>
        </x-bs::modal>

        <x-bs::modal wire:model.defer="showConfirmDelete">
            <form wire:submit.prevent="delete">
                <x-bs::modal.body>
                    <div class="d-grid gap-3 text-center">
                        <div><em class="far fa-trash-alt fa-5x text-red-400"></em></div>
                        <div class="fs-4 text-secondary">{{ __("Are you sure?") }}</div>
                        <div class="text-secondary">{{ __("Are you sure you want to delete the selected variant type? All variants' respective type values will also be deleted.") }}</div>
                        <div>
                            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                            <x-bs::button.danger type="submit" class="ms-2 px-3">{{ __("Delete") }}</x-bs::button.danger>
                        </div>
                    </div>
                </x-bs::modal.body>
            </form>
        </x-bs::modal>
    </div>
</div>
