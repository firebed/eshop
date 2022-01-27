<div class="table-responsive">
    <x-bs::table class="table-sm">
        <thead>
        <tr>
            <td>{{ __("eshop::product.variant_type.name") }}</td>
            <td>&nbsp;</td>
        </tr>
        </thead>

        <tbody>
        @foreach($variantTypes as $index => $variantType)
            <tr wire:key="variant-type-{{ $loop->index }}">
                <td>
                    <input type="text" value="{{ $variantTypes[$index]['id'] }}" name="variantTypes[{{ $index }}][id]" hidden/>
                    <x-bs::input.text value="{{ $variantTypes[$index]['name'] }}" error="variantTypes.{{ $index }}.name" id="name-{{ $index }}" list="variantTypes" autocomplete="off" name="variantTypes[{{ $index }}][name]"/>
                    <datalist id="variantTypes">
                        <option value="{{ __('eshop::product.variant_type.size') }}"></option>
                        <option value="{{ __('eshop::product.variant_type.color') }}"></option>
                        <option value="{{ __('eshop::product.variant_type.material') }}"></option>
                    </datalist>
                </td>
                <td class="align-middle text-end">
                    <div class="d-flex justify-content-end gap-1">
                        <button wire:click.prevent="moveUp({{ $index }})" wire:target="moveUp({{ $index }})" wire:loading.attr="disabled" class="btn btn-sm btn-link shadow-none" @if($loop->first) disabled @endif>
                            <em class="fas fa-chevron-up"></em>
                        </button>

                        <button wire:click.prevent="moveDown({{ $index }})" wire:target="moveDown({{ $index }})" wire:loading.attr="disabled" class="btn btn-sm btn-link shadow-none" @if($loop->last) disabled @endif>
                            <em class="fas fa-chevron-down"></em>
                        </button>

                        <button wire:click.prevent="remove({{ $index }})" wire:loading.attr="disabled" class="btn btn-sm btn-link shadow-none">
                            <em class="far fa-trash-alt"></em>
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </x-bs::table>

    <div class="mt-2">
        <x-bs::button.haze size="sm" wire:click.prevent="add()" wire:loading.attr="disabled">
            {{ __("eshop::product.variant_type.new") }}
        </x-bs::button.haze>
    </div>
</div>