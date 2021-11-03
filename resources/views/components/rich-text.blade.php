@props([
    'value' => '',
    'menubar' => '',
    'plugins' => '',
    'paste' => true,
    'pasteAsText' => true,
    'toolbar' => 'fontsizeselect | bold italic underline | forecolor | alignleft aligncenter alignright alignjustify | outdent indent',
    'error'   => null
])

<div
    wire:ignore
    x-data="{ html: '', text: '' }"
    x-init="tinymce.init({
        target: $refs.input,
        plugins: [{{ $paste ? "'paste'," : "" }} '{{ $plugins }}'],
        menubar: '{{ $menubar }}',
        toolbar: '{{ $toolbar }}',
        entity_encoding: 'raw',
        paste_as_text: {{ $pasteAsText }},
        paste_as_text: true,
        relative_urls : false,
        setup: function (editor) {
            editor.on('input', e => {html = editor.getContent(); text = editor.getContent({format: 'text'})})
            editor.on('change', e => {html = editor.getContent(); text = editor.getContent({format: 'text'})})

            html = editor.getContent();
            text = editor.getContent({format: 'text'})
        }
    })"
    class="d-grid"
>
    <textarea x-ref="input" {{ $attributes->whereDoesntStartWith('wire:model')->class('form-control opacity-0') }}>
        {!! $value !!}
    </textarea>
</div>

@if($error)
    @error($error)
    <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
@endif
