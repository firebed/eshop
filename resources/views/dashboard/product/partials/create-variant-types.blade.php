<div class="card shadow-sm">
    <div class="card-body d-grid gap-3">
        <div class="fs-5">{{ __("Variants") }}</div>

        <div class="table-responsive">
            <x-bs::table>
                <x-slot name="head">
                    <x-bs::table.heading>{{ __("Option name") }}</x-bs::table.heading>
                    <x-bs::table.heading>&nbsp;</x-bs::table.heading>
                </x-slot>
                @foreach($variant_types as $type)
                    <tr>
                        <td><x-bs::input.text wire:model.defer="variant_types.{{ $loop->index }}" error="variant_types.{{ $loop->index }}"/></td>
                        <td class="text-end">
                            <x-bs::button.haze size="sm" wire:loading.attr="disabled" wire:target="removeVariantType({{ $loop->index }})" wire:click="removeVariantType({{ $loop->index }})">
                                <i class="far fa-trash-alt"></i>
                            </x-bs::button.haze>
                        </td>
                    </tr>
                @endforeach
            </x-bs::table>
            <div class="mt-2">
                <x-bs::button.haze size="sm" wire:loading.attr="disabled" wire:target="addVariantType" wire:click="addVariantType">
                    {{ __("Add new type") }}
                </x-bs::button.haze>
            </div>
        </div>
    </div>
</div>
