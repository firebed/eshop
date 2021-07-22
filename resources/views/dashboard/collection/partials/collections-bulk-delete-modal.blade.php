<form action="{{ route('collections.destroyMany') }}" method="post"
      x-data="{ submitting: false, ids: [] }"
      x-on:submit="submitting = true"
>
    @csrf
    @method('delete')

    <template x-for="id in ids" :key="id">
        <input type="hidden" name="ids[]" :value="id">
    </template>

    <div class="modal fade" id="collections-bulk-delete-modal" tabindex="-1"
         x-init="
            $el.addEventListener('show.bs.modal', e => {
                ids = [...document.querySelectorAll('.collection:checked')].map(i => i.value)
                if (ids.length === 0) {
                    $dispatch('show-toast', {type:'warning', body: '{{ __('eshop::collection.select_rows') }}'})
                    e.preventDefault()
                }
            })
         "
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="h5 modal-title" id="exampleModalLabel">{{ __('eshop::collection.bulk_delete') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('eshop::collection.bulk_delete_confirmation') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('eshop::collection.buttons.cancel') }}</button>

                    <button x-bind:disabled="submitting" type="submit" class="btn btn-danger">
                        <em x-cloak x-show="submitting" class="fas fa-circle-notch fa-spin me-2"></em>
                        {{ __('eshop::collection.buttons.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>