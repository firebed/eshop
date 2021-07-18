<x-bs::card>
    <x-bs::card.body>
        <div x-data="{ open: {{ $errors->has('seo.*') || $errors->has('slug') ? 'true' : 'false' }} }"
             x-on:product-name-updated.window="$wire.setProductName($event.detail)"
             x-on:product-description-updated.window="$wire.set('description', $event.detail)"
             x-on:product-category-changed.window="$wire.set('categoryId', $event.detail)"
             class="d-grid gap-2"
        >
            <div class="d-flex justify-content-between align-items-baseline mb-3">
                <div class="fw-500">{{ __("eshop::product.seo_search_listing") }}</div>
                <a x-on:click.prevent="open = !open" href="#" class="text-decoration-none">{{ __('eshop::product.edit_seo') }}</a>
            </div>

            <div class="d-grid" style="max-width: 652px">
                <div class="d-flex gap-1 align-items-baseline">
                    <span style="color: #202124">{{ config('app.url') }}</span>
                    <span style="color: #5f6368">{!! $url !!}</span>
                </div>

                <div class="fs-4 mb-1" style="color: #1A0DAB">{{ $title }} - {{ config('app.name') }}</div>

                <div style="color: #4D5156">{{ $description }}</div>
            </div>

            <div x-cloak x-show="open" x-transition>
                <div class="d-grid gap-3">
                    <input type="text" value="{{ app()->getLocale() }}" name="seo[locale]" hidden>

                    <x-bs::input.group for="seo-title" label="{{ __('Title') }}">
                        <x-bs::input.text wire:model="title" name="seo[title]" id="seo-title" error="seo.title" required/>
                    </x-bs::input.group>

                    <x-bs::input.group for="slug" label="{{ __('Slug') }}">
                        <x-bs::input.text wire:model="slug" name="slug" error="slug" id="slug" required/>
                    </x-bs::input.group>

                    <x-bs::input.group for="seo-description" label="{{ __('Description') }}">
                        <x-bs::input.textarea wire:model="description" name="seo[description]" error="seo.description" id="seo-description" rows="5" required/>
                    </x-bs::input.group>
                </div>
            </div>
        </div>
    </x-bs::card.body>
</x-bs::card>
