<div class="card shadow-sm">
    <div class="card-body d-grid gap-3">
        <div class="fw-500">{{ __("Variants") }}</div>

        <div>{{ __('eshop::product.variant_type.has_variants') }}</div>

        <div class="table-responsive">
            <x-bs::table class="table-sm">
                <thead>
                <tr>
                    <td>{{ __("eshop::product.variant_type.name") }}</td>
                    <td class="w-2r">&nbsp;</td>
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
                        <td class="text-end">
                            <button wire:click.prevent="remove({{ $index }})" wire:loading.attr="disabled" class="btn btn-sm btn-link shadow-none">
                                <em class="far fa-trash-alt"></em>
                            </button>
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
    </div>
</div>
