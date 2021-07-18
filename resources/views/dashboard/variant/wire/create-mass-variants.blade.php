{{--        @if($errors->any())--}}
{{--            <x-bs::alert type="danger">--}}
{{--                {!! implode('', $errors->all('<div>:message</div>')) !!}--}}
{{--            </x-bs::alert>--}}
{{--        @endif--}}

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
                        <x-bs::input.text wire:model.debounce="variants.{{ $index }}.options.{{ $id }}" error="variants.{{ $index }}.options.{{ $id }}" name="variants[{{ $index }}][options][{{ $id }}]" class="option"/>
                    </td>
                @endforeach

                <td x-data="{price:0}">
                    <x-eshop::money x-effect="price = value" value="{{ $variants[$index]['price'] }}"/>
                    <input x-model="price" name="variants[{{ $index }}][price]" hidden/>
                </td>
                <td x-data="{stock:0}">
                    <x-eshop::integer x-effect="stock = value" value="{{ $variants[$index]['stock'] }}"/>
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
        {{ __('Add more') }}
    </x-bs::button.secondary>
</div>