<div class="d-grid gap-3">
    <div>
        <x-bs::button.secondary wire:click="showCombinationsModal" wire:loading.attr="disabled">
            {{ __('eshop::variant.generate_combinations') }}
        </x-bs::button.secondary>
    </div>

    <div class="table-responsive mb-3">
        <x-bs::table class="mb-2">
            <thead>
            <tr>
                @foreach($variantTypes as $id => $name)
                    <td>{{ $name }}</td>
                @endforeach
                <td class="w-7r">{{ __('eshop::product.price') }}</td>
                <td class="w-7r">{{ __('eshop::product.stock') }}</td>
                <td>{{ __('eshop::product.sku') }}</td>
                <td>{{ __('eshop::product.barcode') }}</td>
                <td>&nbsp;</td>
            </tr>
            </thead>
            <tbody>
            @foreach($variants as $index => $variant)
                <tr>
                    @foreach($variantTypes as $id => $name)
                        <td>
                            <x-bs::input.text wire:model.debounce="variants.{{ $index }}.options.{{ $id }}" error="variants.{{ $index }}.options.{{ $id }}" name="variants[{{ $index }}][options][{{ $id }}]" class="option" required/>
                        </td>
                    @endforeach

                    <td x-data="{ price: {{ $variants[$index]['price'] }} }">
                        <x-eshop::money x-effect="price = value" value="price"/>
                        <input x-model="price" name="variants[{{ $index }}][price]" hidden/>
                    </td>
                    <td x-data="{ stock: {{ $variants[$index]['stock'] }} }">
                        <x-eshop::integer x-effect="stock = value" value="stock"/>
                        <input x-model="stock" name="variants[{{ $index }}][stock]" hidden/>
                    </td>
                    <td>
                        <x-bs::input.text wire:model.defer="variants.{{ $index }}.sku" name="variants[{{ $index }}][sku]" error="variants.{{ $index }}.sku"/>
                    </td>
                    <td>
                        <x-bs::input.text wire:model.defer="variants.{{ $index }}.barcode" name="variants[{{ $index }}][barcode]" error="variants.{{ $index }}.barcode"/>
                    </td>
                    <td>
                        <x-bs::button.link wire:click.prevent="remove({{ $index }})" wire:loading.attr="disabled" wire:target="remove({{ $index }})">
                            <em class="far fa-trash-alt"></em>
                        </x-bs::button.link>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </x-bs::table>

        <x-bs::button.secondary size="sm" wire:click.prevent="add()" wire:loading.attr="disabled">
            {{ __('eshop::variant.buttons.add') }}
        </x-bs::button.secondary>
    </div>

    <x-bs::modal wire:model.defer="showCombinationsModal">
        <x-bs::modal.header>{{ __('eshop::variant.generate_combinations') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="mb-3">{{ __('eshop::variant.generate_combinations_content') }}</div>
            <x-bs::table>
                <tbody>
                @foreach($variantTypes as $id => $name)
                    <tr wire:key="vt-{{ $id }}">
                        <td class="align-middle">{{ $name }}</td>
                        <td>
                            <x-bs::input.text wire:model.defer="combinations.{{ $name }}"/>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </x-bs::table>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary wire:click.prevent="generateCombinations" wire:loading.attr="disabled">
                {{ __("eshop::variant.buttons.generate") }}
            </x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</div>