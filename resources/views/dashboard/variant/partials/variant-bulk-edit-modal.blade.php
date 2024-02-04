<form action="{{ route('variants.bulk-update', $product) }}" method="post"
      x-data="{
        submitting: false,
        selectedVariants: [],
        property: '',
        title: '',
        global: '',

        apply() {
            const str = this.global.toString()
            const pad = str.indexOf('##');
            let len = ($el.querySelectorAll('input.' + this.property).length).toString().length;
            $el.querySelectorAll('input.' + this.property).forEach((input, i) => {
                let replacement = (i + 1).toString();
                if (pad !== -1) {
                    len = Math.max(2, len)
                    replacement = str.replace('##', replacement.padStart(len, '0'))
                } else {
                    replacement = str.replace('#', replacement)
                }
                input.value = replacement
            })
        }
      }"
      x-on:submit="submitting = true"
>
    @csrf
    @method('put')

    <div class="modal fade" id="variant-bulk-edit-modal" tabindex="-1"
         x-init="$el.addEventListener('show.bs.modal', evt => {
            const target = evt.relatedTarget
            global = ''
            property = target.getAttribute('data-property')
            title = target.getAttribute('data-title')
            selectedVariants = [...document.querySelectorAll('.variant:checked')].map(i => parseInt(i.value))
        })"
    >
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" x-text="title"></h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" x-bind:value="property" name="properties[]">

                    <div class="input-group mb-3">
                        <x-eshop::money x-show="property === 'price' || property === 'compare_price' || property === 'wholesale_price'" x-effect="global = value" value="0" class="rounded-start"/>
                        <x-eshop::percentage x-show="property === 'discount'" x-effect="global = value" value="0" class="rounded-start"/>
                        <x-eshop::integer x-show="property === 'stock' || property === 'weight'" x-effect="global = value" value="0" class="rounded-start"/>
                        <x-bs::input.text x-show="property === 'sku' || property === 'mpn' || property === 'display_stock_lt' || property === 'available_gt'" x-on:input="global = $el.value.trim()" class="rounded-start"/>
                        <button class="btn btn-outline-secondary" type="button" x-on:click.prevent="apply()">{{ __('eshop::variant.buttons.apply_all') }}</button>
                    </div>

                    <div class="table-responsive">
                        <x-bs::table hover>
                            <tbody>
                            @foreach($variants as $variant)
                                <tr x-show="selectedVariants.includes({{ $variant->id }})">
                                    <td class="align-middle">{{ $variant->options->pluck('pivot.name')->join(' / ') }}</td>

                                    <td class="d-none">
                                        <input x-bind:disabled="!selectedVariants.includes({{ $variant->id }})" type="text" name="bulk_ids[]" value="{{ $variant->id }}">
                                    </td>

                                    <td x-data="{ v: {{ $variant->price }} }" x-show="property === 'price'" class="text-end w-7r">
                                        <x-eshop::money x-effect="v = value" value="v" class="price text-end"/>
                                        <input x-model="v" x-bind:disabled="property !== 'price' || !selectedVariants.includes({{ $variant->id }})" type="text" name="bulk_price[]" hidden>
                                    </td>

                                    <td x-data="{ v: {{ $variant->compare_price }} }" x-show="property === 'compare_price'" class="text-end w-7r">
                                        <x-eshop::money x-effect="v = value" value="v" class="compare_price text-end"/>
                                        <input x-model="v" x-bind:disabled="property !== 'compare_price' || !selectedVariants.includes({{ $variant->id }})" type="text" name="bulk_compare_price[]" hidden>
                                    </td>
                                    
                                    <td x-data="{ v: {{ $variant->wholesale_price }} }" x-show="property === 'wholesale_price'" class="text-end w-7r">
                                        <x-eshop::money x-effect="v = value" value="v" class="wholesale_price text-end"/>
                                        <input x-model="v" x-bind:disabled="property !== 'wholesale_price' || !selectedVariants.includes({{ $variant->id }})" type="text" name="bulk_wholesale_price[]" hidden>
                                    </td>

                                    <td x-data="{ v: {{ $variant->discount }} }" x-show="property === 'discount'" class="text-end w-7r">
                                        <x-eshop::percentage x-effect="v = value" value="v" class="discount text-end"/>
                                        <input x-model="v" x-bind:disabled="property !== 'discount' || !selectedVariants.includes({{ $variant->id }})" type="text" name="bulk_discount[]" hidden>
                                    </td>

                                    <td x-show="property === 'sku'" class="text-end w-10r">
                                        <x-bs::input.text class="sku" x-bind:disabled="property !== 'sku' || !selectedVariants.includes({{ $variant->id }})" name="bulk_sku[]" value="{{ $variant->sku }}"/>
                                    </td>

                                    <td x-show="property === 'mpn'" class="text-end w-10r">
                                        <x-bs::input.text class="mpn" x-bind:disabled="property !== 'mpn' || !selectedVariants.includes({{ $variant->id }})" name="bulk_mpn[]" value="{{ $variant->mpn }}"/>
                                    </td>

                                    <td x-data="{ v: {{ $variant->stock }} }" x-show="property === 'stock'" class="text-end w-7r">
                                        <x-eshop::integer x-effect="v = value" value="v" class="stock text-end"/>
                                        <input x-model="v" x-bind:disabled="property !== 'stock' || !selectedVariants.includes({{ $variant->id }})" type="text" name="bulk_stock[]" hidden>
                                    </td>

                                    <td x-data="{ v: {{ $variant->weight }} }" x-show="property === 'weight'" class="text-end w-7r">
                                        <x-eshop::integer x-effect="v = value" value="v" class="weight text-end"/>
                                        <input x-model="v" x-bind:disabled="property !== 'weight' || !selectedVariants.includes({{ $variant->id }})" type="text" name="bulk_weight[]" hidden>
                                    </td>

                                    <td x-show="property === 'display_stock_lt'" class="text-end w-7r">
                                        <x-bs::input.text class="display_stock_lt" x-bind:disabled="property !== 'display_stock_lt' || !selectedVariants.includes({{ $variant->id }})" name="bulk_display_stock_lt[]" value="{{ $variant->display_stock_lt }}"/>
                                    </td>

                                    <td x-show="property === 'available_gt'" class="text-end w-7r">
                                        <x-bs::input.text class="available_gt" x-bind:disabled="property !== 'available_gt' || !selectedVariants.includes({{ $variant->id }})" name="bulk_available_gt[]" value="{{ $variant->available_gt }}"/>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </x-bs::table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('eshop::variant.buttons.cancel') }}</button>

                    <button type="submit" class="btn btn-primary" x-bind:disabled="submitting">
                        <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                        {{ __('eshop::variant.buttons.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>