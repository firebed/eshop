<div>
    <h2 class="fs-5">Παραλλαγές SEO</h2>
    <div class="table-responsive bg-white rounded-3 border shadow-sm">
        <table class="table table-hover table-sm small" style="table-layout: fixed">
            <thead>
            <tr>
                <th class="align-middle">{{ $default_locale_name }}</th>
                @foreach($locales as $locale)
                    <th>
                        <button wire:click="translate('variants_seo', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em> Μετάφραση ({{ $locale }})</button>
                    </th>
                @endforeach
            </tr>
            </thead>

            <tbody>
            @foreach($this->getDefault('variants_seo') as $id => $title)
                <tr>
                    <td class="align-middle">{{ $title }}</td>
                    @foreach($locales as $key => $locale)
                        <td>
                            <div class="d-flex gap-1">
                                <input wire:model.defer="translations.{{ $key }}.variants_seo.{{ $id }}" class="form-control form-control-sm" type="text">
                                <button wire:click="translate('variants_seo.{{ $id }}', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>