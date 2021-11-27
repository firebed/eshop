<x-bs::card>
    <x-bs::card.body>
        <div x-data="{
            open: {{ empty($product) || $errors->has('seo.*') || $errors->has('slug') ? 'true' : 'false' }},
            title: '{{ $title = addslashes(old('seo.title', $product->seo->title ?? '')) }}',
            slug: '{{ $slug = old('slug', $product->slug ?? '') }}',
            description: '{{ $description = addslashes(old('seo.description', $product->seo->description ?? '')) }}',
            categorySlug: '{{ $categorySlug = $product->category->slug ?? '' }}',

            updateSlug() {
                this.slug = slugifyLower(this.title)
            },

            removeLineBreaks(text) {
                return text.replace(/\r?\n|\r|\s\s+/g, ' ')
            }
        }"
             @if(request()->routeIs('products.create'))
             x-on:product-name-updated.window="
                title = $event.detail
                updateSlug()
             "
             x-on:product-description-updated.window='description = removeLineBreaks($event.detail).substring(0, 300)'
             @endif
             x-on:product-category-changed.window="categorySlug = $event.detail.slug"
             class="d-grid gap-2"
        >
            <div class="d-flex justify-content-between align-items-baseline mb-3">
                <div class="fw-500">{{ __("eshop::seo.search_listing") }}</div>
                <a x-on:click.prevent="open = !open" href="#" class="text-decoration-none">{{ __('eshop::seo.edit') }}</a>
            </div>

            <div class="d-grid">
                <div class="d-flex gap-1 align-items-baseline text-truncate">
                    <span style="color: #202124">{{ config('app.url') }}</span>
                    <span>&rsaquo;</span>
                    <span>{{ app()->getLocale() }}</span>
                    <span>&rsaquo;</span>
                    <span x-text="categorySlug">{{ $categorySlug }}</span>
                    <span>&rsaquo;</span>
                    <span style="color: #5f6368" x-text="slug">{{ $slug }}</span>
                </div>

                <div class="fs-4 mb-1" style="color: #1A0DAB"><span x-text="title">{{ $title }}</span> - {{ config('app.name') }}</div>

                <div style="color: #4D5156" x-text="description">{{ $description }}</div>
            </div>

            <div x-cloak x-show="open" x-transition>
                <div class="d-grid gap-3">
                    <input type="text" value="{{ app()->getLocale() }}" name="seo[locale]" hidden>

                    <x-bs::input.group for="seo-title" label="{{ __('eshop::seo.title') }}">
                        <input type="text" class="form-control" x-model="title" @if(request()->routeIs('products.create')) x-on:input="updateSlug()" @endif name="seo[title]" id="seo-title" error="seo.title" required/>
                        <small class="text-secondary"><span x-text="title.length"></span>, {{ __('eshop::seo.suggested') }}: 55-60</small>
                    </x-bs::input.group>

                    <x-bs::input.group for="slug" label="{{ __('Slug') }}">
                        <x-bs::input.text x-model="slug" name="slug" error="slug" id="slug" required/>
                    </x-bs::input.group>

                    <x-bs::input.group for="seo-description" label="{{ __('eshop::seo.description') }}">
                        <x-bs::input.textarea x-model="description" x-on:keydown.enter.prevent="" name="seo[description]" error="seo.description" id="seo-description" rows="5"/>
                        <small class="text-secondary"><span x-text="description.length"></span>, {{ __('eshop::seo.suggested') }}: 50-160</small>
                    </x-bs::input.group>
                </div>
            </div>
        </div>
    </x-bs::card.body>
</x-bs::card>
