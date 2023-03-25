<form x-data wire:submit.prevent="save" x-on:show-category-translations.window="$wire.show = true">
    <x-bs::modal wire:model.defer="show" size="lg">
        <x-bs::modal.header>Μεταφράσεις</x-bs::modal.header>

        <x-bs::modal.body>
            <div>
                <h2 class="fs-5">Κατηγορία</h2>
                <div class="table-responsive">
                    <table class="table table-hover table-sm small" style="table-layout: fixed">
                        <thead>
                        <tr>
                            <th class="align-middle">{{ $default_locale_name }}</th>
                            @foreach($locales as $key => $locale)
                                <th>
                                    <button type="button" wire:click="translate('category', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white">
                                        <em class="fas fa-language text-cyan-600"></em> Μετάφραση ({{ $locale }})
                                    </button>
                                </th>
                            @endforeach
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td class="align-middle">{{ $this->getDefault('category.name') }}</td>
                            @foreach($locales as $key => $locale)
                                <td>
                                    <div class="d-flex gap-1">
                                        <input wire:model.defer="translations.{{ $key }}.category.name" class="form-control form-control-sm" type="text">
                                        <button type="button" wire:click="translate('category.name', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td>
                                <div class="d-flex gap-1 align-items-start">
                                    <textarea wire:model.defer="translations.el.category.description" class="form-control" rows="3">{{ $this->getTranslation('category.description', 'el') }}</textarea>
                                </div>
                            </td>

                            @foreach($locales as $key => $locale)
                                <td>
                                    <div class="d-flex gap-1 align-items-start">
                                        <textarea wire:model.defer="translations.{{ $key }}.category.description" class="form-control" rows="3">{{ $this->getTranslation('category.description', $key) }}</textarea>
                                        <button type="button" wire:click="translate('category.description', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h2 class="fs-5">SEO</h2>
                <div class="table-responsive">
                    <table class="table table-hover table-sm small" style="table-layout: fixed">
                        <thead>
                        <tr>
                            <th class="align-middle">{{ $default_locale_name }}</th>
                            @foreach($locales as $locale)
                                <th>
                                    <div class="d-flex gap-3 align-items-center">
                                        <button type="button" wire:click="translate('seo', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white">
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
                                        <button type="button" wire:click="translate('seo.title', '{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white"><em class="fas fa-language text-cyan-600"></em></button>
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
        </x-bs::modal.body>

        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>