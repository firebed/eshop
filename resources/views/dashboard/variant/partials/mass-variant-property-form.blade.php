<form action="{{ route('variants.properties.update') }}" method="post" enctype="multipart/form-data"
      x-data="{ submitting: false }"
      x-on:submit="submitting = true"
>
    @csrf
    @method('put')

    <div class="modal fade" id="mass-variant-property-modal" tabindex="-1"
         x-data="{
            variants: [],
            property: '',
            global: '',
            title: '',

            applyToAll() {
                this.variants.forEach(v => v.property = this.global)
            }
         }"
         x-init="$el.addEventListener('show.bs.modal', event => {
                global = ''
                variants = []

                const target = event.relatedTarget
                property = target.getAttribute('data-property')
                title = target.getAttribute('data-title')
                const id = target.getAttribute('id')
                const value = target.getAttribute(property)

                document.querySelectorAll('.variant:checked').forEach(i => {
                    variants.push({
                        id: i.getAttribute('data-id'),
                        options: i.getAttribute('data-options'),
                        property: i.getAttribute('data-' + property)
                    })
                })
            })"
    >
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" x-text="title"></h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <input type="text" x-model="property" name="property" hidden>
                    <div class="table-responsive">
                        <x-bs::table hover size="sm">
                            <thead>
                            <tr>
                                <td class="ps-3 align-middle">
                                    <template x-if="property === 'price' || property === 'compare_price'">
                                        <x-eshop::money x-effect="global = value" :value="0" class="w-auto"/>
                                    </template>

                                    <template x-if="property === 'discount'">
                                        <x-eshop::percentage x-effect="global = value*100" :value="0" class="w-auto"/>
                                    </template>

                                    <template x-if="property === 'stock' || property === 'weight'">
                                        <x-eshop::integer x-effect="global = value" :value="0" class="w-auto"/>
                                    </template>

                                    <template x-if="property === 'sku'">
                                        <x-bs::input.text x-model="global" value="" class="w-auto"/>
                                    </template>
                                </td>
                                <td class="w-10r pe-3 align-middle">
                                    <div class="d-grid">
                                        <x-bs::button.secondary x-on:click.prevent="applyToAll()" size="sm">
                                            {{ __('eshop::variant.mass-actions.apply_to_all') }}
                                        </x-bs::button.secondary>
                                    </div>
                                </td>
                            </tr>
                            </thead>

                            <tbody>
                            <template x-for="variant in variants" :key="variant.id">
                                <tr>
                                    <td class="align-middle ps-3" x-text="variant.options"></td>
                                    <td class="text-end pe-3">
                                        <input type="text" x-model="variant.id" name="ids[]" hidden>

                                        <template x-if="property === 'price' || property === 'compare_price'">
                                            <input x-model="variant.property" type="number" step="any" name="values[]" class="form-control text-end">
                                        </template>

                                        <template x-if="property === 'discount'">
                                            <input x-model="variant.property" type="number" min="1" max="100" name="values[]" class="form-control text-end">
                                        </template>

                                        <template x-if="property === 'stock' || property === 'weight'">
                                            <input x-model="variant.property" type="number" name="values[]" class="form-control text-end">
                                        </template>

                                        <template x-if="property === 'sku'">
                                            <x-bs::input.text x-model="variant.property" name="values[]"/>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </x-bs::table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('eshop::product.actions.cancel') }}</button>

                    <button type="submit" class="btn btn-primary" x-bind:disabled="submitting">
                        <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                        {{ __('eshop::product.actions.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>