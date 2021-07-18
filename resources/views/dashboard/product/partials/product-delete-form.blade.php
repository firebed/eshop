<form action="{{ route('products.destroy', $product) }}" method="post" x-data="{ submitting: false }" x-on:submit="submitting = true">
    @csrf
    @method('delete')

    <x-bs::button.danger type="button" data-bs-toggle="modal" data-bs-target="#product-delete-form">
        {{ __('eshop::product.actions.delete') }}
    </x-bs::button.danger>

    <div class="modal fade" id="product-delete-form" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('eshop::product.delete_product') }}</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex gap-3">
                        <div>{{ __('eshop::product.delete_confirmation') }}</div>

                        <div class="ms-auto"><em class="far fa-trash-alt fa-4x text-danger"></em></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('eshop::product.actions.cancel') }}</button>

                    <button type="submit" class="btn btn-danger" x-bind:disabled="submitting">
                        <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                        {{ __('eshop::product.actions.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>