<form x-data wire:submit.prevent="save" x-on:show-property-translations.window="$wire.show = true">
    <x-bs::modal wire:model.defer="show" size="lg">
        <x-bs::modal.header>Μεταφράσεις</x-bs::modal.header>

        <x-bs::modal.body>
            <div>
                <h2 class="fs-5">Ιδιότητα</h2>
                <div class="table-responsive">
                    <table class="table table-hover table-sm small" style="table-layout: fixed">
                        <thead>
                        <tr>
                            <th class="align-middle">{{ $default_locale_name }}</th>
                            @foreach($locales as $key => $locale)
                                <th>
                                    <button type="button" wire:click="translate('property', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white">
                                        <em class="fas fa-language text-cyan-600"></em> Μετάφραση ({{ $locale }})
                                    </button>
                                </th>
                            @endforeach
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td class="align-middle">{{ $this->getDefault('property.name') }}</td>
                            @foreach($locales as $key => $locale)
                                <td>
                                    <div class="d-flex gap-1">
                                        <input wire:model.defer="translations.{{ $key }}.property.name" class="form-control form-control-sm" type="text">
                                        <button type="button" wire:click="translate('property.name', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h2 class="fs-5">Επιλογές</h2>
                <div class="table-responsive">
                    <table class="table table-hover table-sm small" style="table-layout: fixed">
                        <thead>
                        <tr>
                            <th class="align-middle">{{ $default_locale_name }}</th>
                            @foreach($locales as $locale)
                                <th>
                                    <div class="d-flex gap-3 align-items-center">
                                        <button type="button" wire:click="translate('choices', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white">
                                            <em class="fas fa-language text-cyan-600"></em> Μετάφραση ({{ $locale }})
                                        </button>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($this->getDefault("choices") as $id => $name)
                            <tr wire:key="choice-{{ $id }}">
                                <td class="align-middle">{{ $name }}</td>
                                @foreach($locales as $key => $locale)
                                    <td>
                                        <div class="d-flex gap-1">
                                            <input wire:model.defer="translations.{{ $key }}.choices.{{ $id }}" class="form-control form-control-sm" type="text">
                                            <button wire:click="translate('choices.{{ $id }}', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </x-bs::modal.body>

        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>