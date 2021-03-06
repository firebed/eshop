<div x-data="{
            open: {{ empty($product) || $errors->has('seo.*') || $errors->has('slug') ? 'true' : 'false' }},
            title: '{{ $title = addslashes(old('seo.title', $variant->seo->title ?? '')) }}',
            slug: '{{ $slug = old('slug', $variant->slug ?? '') }}',
            description: '{{ $description = addslashes(old('seo.description', $variant->seo->description ?? '')) }}',
            productSlug: '{{ $productSlug = $product->slug ?? '' }}',
            categorySlug: '{{ $categorySlug = $variant->category->slug ?? '' }}',

            updateSlug() {
                this.slug = slugifyLower(this.title)
            }
        }"
     @if(request()->routeIs('products.variants.create'))
     x-on:variant-options-updated.window="
        title = '{{ $product->seo->title ?? '' }}' + ' ' + $event.detail.join(' ')
        updateSlug()
     "
     @endif
     class="d-grid gap-2"
>
    <div class="d-flex justify-content-between align-items-baseline mb-3">
        <div class="fw-500">{{ __("eshop::seo.search_listing") }}</div>
        <a x-on:click.prevent="open = !open" href="#" class="text-decoration-none">{{ __('eshop::seo.edit') }}</a>
    </div>

    <div class="d-grid w-100">
        <div class="d-flex gap-1 align-items-baseline text-truncate">
            <span style="color: #202124">{{ config('app.url') }}</span>
            <span>&rsaquo;</span>
            <span>{{ app()->getLocale() }}</span>
            <span>&rsaquo;</span>
            <span x-text="categorySlug">{{ $categorySlug }}</span>
            <span>&rsaquo;</span>
            <span style="color: #5f6368" x-text="slug">{{ $slug }}</span>
        </div>

        <div class="fs-4 mb-1" style="color: #1A0DAB"><span x-text="title">{{ $title }}</span> | {{ config('app.name') }}</div>

        <div style="color: #4D5156" x-text="description">{{ $description }}</div>
    </div>

    <div x-cloak x-show="open" x-transition>
        <div class="d-grid gap-3">
            <input type="text" value="{{ app()->getLocale() }}" name="seo[locale]" hidden>

            <x-bs::input.group for="seo-title" label="{{ __('eshop::seo.title') }}">
                @if(request()->routeIs('products.variants.create'))
                    <x-bs::input.text x-model="title" x-on:input="updateSlug()" name="seo[title]" id="seo-title" error="seo.title" required/>
                @else
                    <x-bs::input.text x-model="title" name="seo[title]" id="seo-title" error="seo.title" required/>
                @endif
                <small class="text-secondary"><span x-text="title.length + '{{ config('app.url') }}'.length"></span>, {{ __('eshop::seo.suggested') }}: 55-60</small>
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