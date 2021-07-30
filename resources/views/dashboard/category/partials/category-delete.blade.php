<x-bs::card>
    <x-bs::card.body>
        <div class="fw-500 mb-3">{{ __("eshop::category.delete") }}</div>

        <form action="{{ route('categories.destroy', $category) }}" method="post"
              x-data="{ submitting: false }"
              x-on:submit="submitting = true"
        >
            @csrf
            @method('delete')

            <div class="d-flex gap-3 align-items-end">
                <input type="hidden" name="delete_name" value="{{ $category->name }}">

                <x-bs::input.group for="delete-name" label="{{ __('eshop::category.name') }}">
                    <x-bs::input.text name="delete_name_confirmation" error="delete_name" id="delete-name" placeholder="{{ __('eshop::category.name') }}" required/>
                </x-bs::input.group>

                <x-bs::button.danger x-bind:disabled="submitting" type="submit">
                    <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm me-2"></em>
                    {{ __('eshop::buttons.delete') }}
                </x-bs::button.danger>
            </div>
        </form>
    </x-bs::card.body>
</x-bs::card>