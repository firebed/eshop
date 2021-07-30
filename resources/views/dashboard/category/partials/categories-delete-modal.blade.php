<form x-data="{ ids: [], submitting: false }" x-on:submit="submitting = true" action="{{ route('categories.destroyMany') }}" method="post">
    @csrf
    @method('delete')

    <div class="modal fade" id="categories-delete-modal" tabindex="-1"
        x-init="
            $el.addEventListener('show.bs.modal', () => {
                ids = [...document.querySelectorAll('.category:checked')].map(i => i.value)
            })
        "
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('eshop::category.bulk_delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <template x-for="id in ids" :key="id">
                        <input type="hidden" name="ids[]" x-model="id">
                    </template>

                    <div class="d-flex justify-content-between gap-3">
                        <div class="text-secondary">{{ __('eshop::category.bulk_delete_prompt') }}</div>
                        <em class="far fa-trash-alt fa-4x text-danger"></em>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('eshop::buttons.cancel') }}</button>

                    <button x-bind:disabled="submitting" type="submıt" class="btn btn-danger">
                        <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm"></em>
                        {{ __('eshop::buttons.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>