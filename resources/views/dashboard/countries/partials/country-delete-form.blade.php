<form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('countries.destroy', $country) }}" method="post">
    @csrf
    @method('delete')

    <div class="modal fade" id="delete-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Delete") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Διαγραφή χώρας "{{ $country->name }}";
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("eshop::buttons.cancel") }}</button>

                    <x-bs::button.danger x-bind:disabled="submitting" type="submit">
                        <span x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status"></span>
                        {{ __("eshop::buttons.delete") }}
                    </x-bs::button.danger>
                </div>
            </div>
        </div>
    </div>
</form>