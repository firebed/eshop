<div>
    <x-bs::button.primary wire:click="create" wire:loading.attr="disabled" wire:target="create" size="sm">
        <em class="fa fa-plus"></em> {{ __('New') }}
    </x-bs::button.primary>

    <form wire:submit.prevent="save">
        <x-bs::modal wire:model.defer="showEditingModal">
            <x-bs::modal.header>{{ __("Add product") }}</x-bs::modal.header>
            <x-bs::modal.body>
                <div class="d-grid gap-2">
                    <x-bs::input.group for="create-category" label="{{ __('Category') }}" inline>
                        <x-bs::input.select wire:model="categoryId" id="create-category" error="categoryId" autofocus>
                            <option value="" disabled>{{ __("Select category") }}</option>
                            @foreach($categories as $parentId => $group)
                                @if($parentId)
                                    <optgroup label="{{ $group->first()->parent->name }}">
                                        @foreach($group as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    @foreach($group as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </x-bs::input.select>
                    </x-bs::input.group>

                    <x-bs::input.group for="create-product" label="{{ __('Product') }}" inline>
                        <div x-data="{ items: @entangle('products').defer }">
                            <x-bs::input.select x-ref="op" wire:model="productId" wire:loading.attr="disabled" wire:target="categoryId" id="create-product" error="productId">
                                <option value="" disabled>{{ __("Select product") }}</option>
                                <template x-for="item in items" :key="item.id">
                                    <option x-text="item.name" x-bind:value="item.id"></option>
                                </template>
                            </x-bs::input.select>
                        </div>
                    </x-bs::input.group>

                    <x-bs::input.group for="create-variant" label="{{ __('Variant') }}" inline>
                        <div x-data="{ items: @entangle('variants').defer }">
                            <x-bs::input.select wire:model="variantId" wire:loading.attr="disabled" wire:target="productId" id="create-variant" error="variantId">
                                <option value="" disabled>{{ __("Select variant") }}</option>
                                <template x-for="item in items" :key="item.id">
                                    <option x-text="item.name" x-bind:value="item.id"></option>
                                </template>
                            </x-bs::input.select>
                        </div>
                    </x-bs::input.group>

                    <div class="row row-cols-3">
                        <x-bs::input.group for="create-quantity" label="{{ __('Quantity') }}" class="col">
                            <x-bs::input.decimal wire:model.defer="model.quantity" min="0" id="create-quantity" error="model.quantity"/>
                        </x-bs::input.group>

                        <x-bs::input.group for="create-price" label="{{ __('Price') }}" class="col">
                            <x-bs::input.money wire:model.defer="model.price" id="create-price" error="model.price"/>
                        </x-bs::input.group>

                        <x-bs::input.group for="create-discount" label="{{ __('Discount') }}" class="col">
                            <x-bs::input.percentage wire:model.defer="model.discount" min="0" max="100" id="create-discount" error="model.discount"/>
                        </x-bs::input.group>
                    </div>
                </div>
            </x-bs::modal.body>

            <x-bs::modal.footer>
                <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
            </x-bs::modal.footer>
        </x-bs::modal>
    </form>
</div>
