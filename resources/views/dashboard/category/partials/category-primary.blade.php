<x-bs::card>
    <x-bs::card.body>
        <div class="row">
            <div class="col-8 d-grid gap-2 align-items-start">
                <x-bs::input.group for="type" label="{{ __('eshop::category.type') }}" labelCol="3">
                    <div class="d-grid mb-2">
                        <x-bs::input.radio name="type" value="File" id="file" :checked="old('type', $category->type ?? 'File') === 'File'" required>
                            {{ __('eshop::category.file') }}
                        </x-bs::input.radio>
                        <small class="text-secondary ps-4">{!! __('eshop::category.info.file') !!}</small>
                    </div>

                    <div class="d-grid">
                        <x-bs::input.radio name="type" value="Folder" id="folder" :checked="old('type', $category->type ?? '') === 'Folder'" required>
                            {{ __('eshop::category.folder') }}
                        </x-bs::input.radio>
                        <small class="text-secondary ps-4">{!! __('eshop::category.info.folder') !!}</small>
                    </div>
                </x-bs::input.group>

                <x-bs::input.group x-data="" for="name" label="{{ __('eshop::category.name') }}" labelCol="3">
                    <x-bs::input.text x-on:input="$dispatch('category-name-updated', $el.value.trim())"
                                      name="name"
                                      value="{{ old('name', $category->name ?? '') }}"
                                      id="name"
                                      error="name"
                                      placeholder="{{ __('eshop::category.placeholders.name') }}"
                                      required/>
                </x-bs::input.group>
            </div>

            <div class="col">
                @include('eshop::dashboard.category.partials.image')
            </div>
        </div>
    </x-bs::card.body>
</x-bs::card>