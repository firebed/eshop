<div class="card shadow-sm">
    <div class="card-body">
        <div class="fw-500 mb-3">{{ __("Attachments") }}</div>

        <div class="row row-cols-1 g-3">
            <x-bs::input.group for="attachment-title" label="{{ __('Title') }}">
                <x-bs::input.text list="attachment-titles" name="attachment[title]" :value="old('attachment.title', $product->attachment?->title)" id="attachment-title" label="Title"/>
            </x-bs::input.group>
            
            <datalist id="attachment-titles">
                <option value="{{ __("Technical details") }}">
                <option value="{{ __("Specifications") }}">
                <option value="{{ __("Size guide") }}">
                <option value="{{ __("Color guide") }}">
                <option value="{{ __("Attributes") }}">
                <option value="{{ __("Manual") }}">
            </datalist>

            <x-bs::input.group for="attachment-file" label="{{ __('File') }}">
                <x-bs::input.file name="attachment[file]" id="attachment-file" accept="application/pdf,image/*"/>
            </x-bs::input.group>

            @if($product->attachment)
                <a target="_blank" class="btn btn-smoke" href="{{ asset( $product->attachment->url()) }}">{{ __("Preview") }}</a>
            @endif
        </div>
    </div>
</div>
