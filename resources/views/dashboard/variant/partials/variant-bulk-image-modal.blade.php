<form action="{{ route('variants.bulk-images.update') }}" method="post" enctype="multipart/form-data"
      x-data="{
            submitting: false,
            ids: []
      }"
      x-on:submit="submitting = true"
>
    @csrf
    @method('put')

    <div class="modal fade" id="variant-bulk-image-modal" tabindex="-1"
         x-init="$el.addEventListener('show.bs.modal', () => {
            ids = [...document.querySelectorAll('.variant:checked')].map(i => i.value)
        })"
    >
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('eshop::variant.bulk-actions.image') }}</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <template x-for="id in ids" :key="id">
                        <input type="text" name="ids[]" :value="id" hidden>
                    </template>

                    <div class="row" x-data="{ image: null }">
                        <div class="col">
                            <div class="ratio ratio-4x3 border rounded">
                                <template x-if="image">
                                    <img x-bind:src="image" class="img-middle rounded" alt="">
                                </template>
                            </div>
                        </div>

                        <div class="col-7">
                            <x-bs::input.file x-on:change="image = URL.createObjectURL($el.files[0])" name="image" accept="image/*"/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('eshop::variant.buttons.cancel') }}</button>

                    <button type="submit" class="btn btn-primary" x-bind:disabled="submitting">
                        <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                        {{ __('eshop::variant.buttons.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>