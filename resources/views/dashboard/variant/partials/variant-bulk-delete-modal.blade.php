<form action="{{ route('variants.bulk-destroy') }}" method="post"
      x-data="{
            submitting: false,
            ids: []
      }"
      x-on:submit="submitting = true">
    @csrf
    @method('delete')

    <div class="modal fade" id="variant-bulk-delete-modal" tabindex="-1"
         x-init="$el.addEventListener('show.bs.modal', () => {
            ids = [...document.querySelectorAll('.variant:checked')].map(i => i.value)
        })"
    >
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('eshop::variant.delete_multiple') }}</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <template x-for="id in ids" :key="id">
                        <input type="text" name="ids[]" :value="id" hidden>
                    </template>

                    <div class="d-flex gap-3">
                        <div>{{ __('eshop::variant.delete_multiple_confirmation') }}</div>

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