<x-bs::input.group for="variants_display" label="{{ __('eshop::variant.display.title') }}">
    <x-bs::input.select id="variants_display" name="variants_display">
        <option value="" @if(old('variants_display', $product->variants_display ?? '') === null) selected @endif>{{ __('eshop::variant.display.select') }}</option>
        <option value="grid" @if(old('variants_display', $product->variants_display ?? '') === 'grid') selected @endif>{{ __('eshop::variant.display.grid') }}</option>
        <option value="buttons" @if(old('variants_display', $product->variants_display ?? '') === 'buttons') selected @endif>{{ __('eshop::variant.display.buttons') }}</option>
        <option value="list" @if(old('variants_display', $product->variants_display ?? '') === 'list') selected @endif>{{ __('eshop::variant.display.list') }}</option>
    </x-bs::input.select>
</x-bs::input.group>

<x-bs::input.checkbox :checked="old('preview_variants', $product->preview_variants ?? false)" id="preview_variants" name="preview_variants">
    {{ __('eshop::variant.display.preview') }}
</x-bs::input.checkbox>