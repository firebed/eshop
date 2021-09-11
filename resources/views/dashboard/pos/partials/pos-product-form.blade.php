<div wire:ignore.self x-data="{productId:0, quantity:0, price:0, discount:0}" class="offcanvas offcanvas-end px-0" tabindex="-1" id="product-form">
    <div class="offcanvas-header border-bottom">
        <div class="fs-5 fw-500">Επεξεργασία προϊόντος</div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body vstack gap-3">
        <div class="d-flex justify-content-center">
            <div class="ratio ratio-4x3 w-17r">
                <img id="product-image" class="rounded img-middle"/>
            </div>
        </div>

        <div id="product-trademark" class="fs-5 fw-500 text-center"></div>

        <x-eshop::integer hidden id="product-id" x-effect="productId = value"/>

        <x-bs::input.floating-label for="product-quantity" label="{{ __('Quantity') }}">
            <x-eshop::integer id="product-quantity" x-effect="quantity = value" placeholder="{{ __('Quantity') }}" autocomplete="new"/>
        </x-bs::input.floating-label>

        <x-bs::input.floating-label for="product-price" label="{{ __('Price') }}">
            <x-eshop::money id="product-price" x-effect="price = value" placeholder="{{ __('Price') }}" autocomplete="new"/>
        </x-bs::input.floating-label>

        <x-bs::input.floating-label for="product-discount" label="{{ __('Discount') }}">
            <x-eshop::percentage id="product-discount" x-effect="discount = value" placeholder="{{ __('Discount') }}" autocomplete="new"/>
        </x-bs::input.floating-label>
    </div>

    <div class="d-grid border-top p-3">
        <button x-on:click="Livewire.emit('updateProduct', productId, quantity, price, discount)" data-bs-dismiss="offcanvas" type="button" class="btn btn-green btn-lg"><em class="fas fa-save me-2"></em>Αποθήκευση</button>
    </div>
</div>