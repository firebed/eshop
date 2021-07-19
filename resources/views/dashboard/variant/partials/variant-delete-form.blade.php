<form action="{{ route('variants.destroy', $variant) }}" method="post" x-data="{ submitting: false }" x-on:submit="submitting = true">
    @csrf
    @method('delete')

    <x-bs::button.danger type="button" data-bs-toggle="modal" data-bs-target="#variant-delete-form">
        {{ __('eshop::variant.buttons.delete') }}
    </x-bs::button.danger>

    <div class="modal fade" id="variant-delete-form" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('eshop::variant.delete') }}</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex gap-3">
                        <div>{{ __('eshop::variant.delete_confirmation') }}</div>

                        <div class="ms-auto"><em class="far fa-trash-alt fa-4x text-danger"></em></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('eshop::variant.buttons.cancel') }}</button>

                    <button type="submit" class="btn btn-danger" x-bind:disabled="submitting">
                        <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                        {{ __('eshop::variant.buttons.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>