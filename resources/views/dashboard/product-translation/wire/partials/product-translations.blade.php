<div>
    <h2 class="fs-5">Βασικές λεπτομέρειες</h2>
    <div class="table-responsive bg-white rounded-3 border shadow-sm">
        <table class="table table-hover table-sm small" style="table-layout: fixed">
            <thead>
            <tr>
                <th class="align-middle">{{ $default_locale_name }}</th>
                @foreach($locales as $key => $locale)
                    <th>
                        <button wire:click="translate('product', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white">
                            <em class="fas fa-language text-cyan-600"></em> Μετάφραση ({{ $locale }})
                        </button>
                    </th>
                @endforeach
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="align-middle">{{ $this->getDefault('product.name') }}</td>
                @foreach($locales as $key => $locale)
                    <td>
                        <div class="d-flex gap-1">
                            <input wire:model.defer="translations.{{ $key }}.product.name" class="form-control form-control-sm" type="text">
                            <button wire:click="translate('product.name', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
                        </div>
                    </td>
                @endforeach
            </tr>

            <tr>
                <td>
                    <div style="max-height: 254px; overflow: auto">{!! $this->getDefault('product.description') !!}</div>
                </td>
                @foreach($locales as $key => $locale)
                    <td>
                        <div class="d-flex gap-1 align-items-start">
                            <div class="flex-grow-1">
                                <textarea wire:ignore
                                          x-init="tinymce.init({
                                                target: $el,
                                                plugins: ['lists code'],
                                                menubar: '',
                                                toolbar: 'fontselect | bold italic underline | forecolor | bullist numlist | removeformat | code',
                                                entity_encoding: 'raw',
                                                relative_urls : false,
                                                branding: false,
                                                statusbar: false,
                                                setup: function (editor) {
                                                    editor.on('input', e => $dispatch('input', editor.getContent()))
                                                    editor.on('change', e => $dispatch('input', editor.getContent()))
                                                }
                                          })"
                                          wire:model.defer="translations.{{ $key }}.product.description"
                                          class="form-control opacity-0" rows="10">{{ $this->getTranslation('product.description', $key) }}</textarea>
                            </div>
                            <button wire:click="translate('product.description', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
                        </div>
                    </td>
                @endforeach
            </tr>
            </tbody>
        </table>
    </div>
</div>