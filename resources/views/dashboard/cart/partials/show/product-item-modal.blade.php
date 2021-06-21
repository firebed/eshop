<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showEditingModal">
        <x-bs::modal.header>{{ __("Edit product") }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div>
                @unless($model->getKey())
                    <div class="d-grid mb-3">
                        <x-bs::input.group for="category" label="{{ __('Category') }}" inline class="mb-3">
                            <x-bs::input.select wire:model="model.category_id" id="category" error="model.category_id" autofocus>
                                <option value="" disabled>{{ __("Select category") }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </x-bs::input.select>
                        </x-bs::input.group>

                        <x-bs::input.group for="product" label="{{ __('Product') }}" inline>
                            <x-bs::input.select wire:model="model.product_id" wire:loading.attr="disabled" wire:target="model.category_id" id="product" error="model.product_id">
                                <option value="" disabled>{{ __("Select product") }}</option>
                                @unless(empty($products_list))
                                    @foreach($products_list as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                @endunless
                            </x-bs::input.select>
                        </x-bs::input.group>

                        <div>
                            @if(!empty($model->product_id))
                                <x-bs::input.group for="variant" label="{{ __('Variant') }}" inline class="mt-3">
                                    <x-bs::input.select wire:model="model.variant_id" wire:loading.attr="disabled" wire:target="model.product_id" id="variant" error="model.variant_id">
                                        <option value="" disabled>{{ __("Select variant") }}</option>
                                        @foreach($variants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->sku . ' ' . $variant->options->pluck('pivot.value')->join(' - ') }}</option>
                                        @endforeach
                                    </x-bs::input.select>
                                </x-bs::input.group>
                            @endif
                        </div>
                    </div>
                @endunless
            </div>

            <div class="row row-cols-3">
                <x-bs::input.group for="quantity" label="{{ __('Quantity') }}" class="col">
                    <x-bs::input.decimal wire:model.defer="model.quantity" min="0" id="quantity" error="model.quantity"/>
                </x-bs::input.group>

                <x-bs::input.group for="price" label="{{ __('Price') }}" class="col">
                    <x-bs::input.money wire:model.defer="model.price" id="price" error="model.price"/>
                </x-bs::input.group>

                <x-bs::input.group for="discount" label="{{ __('Discount') }}" class="col">
                    <x-bs::input.percentage wire:model.defer="model.discount" min="0" max="100" id="discount" error="model.discount"/>
                </x-bs::input.group>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
