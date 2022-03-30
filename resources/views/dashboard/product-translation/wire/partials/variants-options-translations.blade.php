<div>
    <h2 class="fs-5">Επιλογές παραλλαγών</h2>
    <div class="table-responsive bg-white rounded-3 border shadow-sm">
        <table class="table table-hover table-sm small" style="table-layout: fixed">
            <thead>
            <tr>
                <th class="align-middle">{{ $default_locale_name }}</th>
                @foreach($locales as $locale)
                    <th>
                        <button wire:click="translate('options', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em> Μετάφραση ({{ $locale }})</button>
                    </th>
                @endforeach
            </tr>
            </thead>

            <tbody>
            @foreach($this->getDefault("options") as $pivot_id => $name)
                <tr wire:key="option-{{ $pivot_id }}">
                    <td class="align-middle">{{ $name }}</td>
                    @foreach($locales as $key => $locale)
                        <td>
                            <div class="d-flex gap-1">
                                <input wire:model.defer="translations.{{ $key }}.options.{{ $pivot_id }}" class="form-control form-control-sm" type="text">
                                <button wire:click="translate('options.{{ $pivot_id }}', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>