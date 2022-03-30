<div>
    <h2 class="fs-5">SEO</h2>
    <div class="table-responsive bg-white rounded-3 border shadow-sm">
        <table class="table table-hover table-sm small" style="table-layout: fixed">
            <thead>
            <tr>
                <th class="align-middle">{{ $default_locale_name }}</th>
                @foreach($locales as $locale)
                    <th>
                        <div class="d-flex gap-3 align-items-center">
                            <button wire:click="translate('seo', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white">
                                <em class="fas fa-language text-cyan-600"></em> Μετάφραση ({{ $locale }})
                            </button>
                        </div>
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="align-middle">{{ $this->getDefault('seo.title') }}</td>
                @foreach($locales as $key => $locale)
                    <td>
                        <div class="d-flex gap-1">
                            <input wire:model.defer="translations.{{ $key }}.seo.title" class="form-control form-control-sm" type="text">
                            <button wire:click="translate('seo.title', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
                        </div>
                    </td>
                @endforeach
            </tr>

            <tr>
                <td>{{ $this->getDefault('seo.description') }}</td>
                @foreach($locales as $key => $locale)
                    <td>
                        <div class="d-flex gap-1 align-items-start">
                            <textarea wire:model.defer="translations.{{ $key }}.seo.description" class="form-control form-control-sm h-100" rows="3"></textarea>
                            <button wire:click="translate('seo.description', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
                        </div>
                    </td>
                @endforeach
            </tr>
            </tbody>
        </table>
    </div>
</div>