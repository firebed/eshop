<div class="card shadow-sm"
     x-data="{
        variantTypes: @entangle('variantTypes').defer,

        addVariantType() {
            this.variantTypes.push({id: Date.now(), name: ''})
        },

        removeVariantType(variantType) {
            this.variantTypes.splice(this.variantTypes.indexOf(variantType), 1)
        }
     }">
    <div class="card-body d-grid gap-3">
        <div class="fs-5">{{ __("Variants") }}</div>

        <div>{{ __('eshop::product.has_variants') }}</div>

        <div class="table-responsive">
            <x-bs::table class="table-sm">
                <thead>
                <tr>
                    <td>{{ __("eshop::product.variant_type") }}</td>
                    <td class="w-2r">&nbsp;</td>
                </tr>
                </thead>

                <tbody>
                <template x-for="variantType in variantTypes" :key="variantType.id">
                    <tr>
                        <td>
                            <x-bs::input.text x-model="variantType.name"/>
                        </td>
                        <td class="text-end">
                            <button x-on:click.prevent="removeVariantType(variantType)" class="btn btn-sm btn-link">
                                <em class="far fa-trash-alt"></em>
                            </button>
                        </td>
                    </tr>
                </template>
                </tbody>
            </x-bs::table>

            <div class="mt-2">
                <x-bs::button.haze size="sm" x-on:click.prevent="addVariantType()">
                    {{ __("Add new type") }}
                </x-bs::button.haze>
            </div>
        </div>
    </div>
</div>
