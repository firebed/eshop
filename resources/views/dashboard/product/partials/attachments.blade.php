<div class="card shadow-sm">
    <div class="card-body">
        <div class="fw-500 mb-3">{{ __("Attachments") }}</div>

        <div class="row row-cols-1 g-3">
            <x-bs::input.group for="attachment-title" label="{{ __('Title') }}">
                <x-bs::input.text list="attachment-titles" name="attachment[title]" :value="old('attachment.title', $product->attachment?->title)" id="attachment-title" label="Title"/>
            </x-bs::input.group>
            
            <datalist id="attachment-titles">
                <option value="Τεχνικά χαρακτηριστικά">
                <option value="Μεγεθολόγιο">
                <option value="Χρωματολόγιο">
                <option value="Οδηγίες χρήσης">
            </datalist>

            <x-bs::input.group for="attachment-file" label="{{ __('File') }}">
                <x-bs::input.file name="attachment[file]" id="attachment-file" accept="application/pdf,image/*"/>
            </x-bs::input.group>
            
            @if($product->attachment)
                <a href="{{ asset($product->attachment->src) }}"></a>
            @endif
        </div>
    </div>
</div>
