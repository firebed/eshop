@extends('eshop::dashboard.layouts.master')

@section('header')
    <a href="{{ route('products.index') }}" class="text-dark text-decoration-none">{{ __("Products") }}</a>
@endsection

@section('main')
    
    <form method="post" action="{{ route('products.copy.store', $product) }}"
          enctype="multipart/form-data"
          x-data="{ submitting: false }"
          x-on:submit="submitting = true"
          class="col-12 p-4"
    >
        @csrf

        <div class="col-12 col-xxl-9 mx-auto mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <h1 class="fs-3">Αντιγραφή προϊόντος</h1>

                <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                    <em x-show="!submitting" class="fa fa-save me-2"></em>
                    <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                    {{ __("eshop::product.actions.save") }}
                </x-bs::button.primary>
            </div>
            
            @error('db')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-xxl-9 mx-auto">
            <div class="row g-4">
                <div class="col-12 d-flex flex-column gap-4">
                    <x-bs::card>
                        <x-bs::card.body class="d-grid gap-3">
                            <x-bs::input.group for="name" label="{{ __('Name') }}">
                                <x-bs::input.text x-on:input.debounce="$dispatch('product-name-updated', $el.value.trim())" :value="old('name')" name="name" error="name" id="name" placeholder="{{ __('Name') }}" autofocus required/>
                            </x-bs::input.group>

                            <div class="row row-cols-2 g-3">
                                <x-bs::input.group x-data="{ price: {{ old('price') ?? 0 }} }" for="selling-price" label="{{ __('Selling price') }}" class="col">
                                    <x-eshop::money x-effect="price = value" value="price" id="selling-price" error="price"/>
                                    <input type="text" x-model="price" name="price" hidden>
                                </x-bs::input.group>

                                <x-bs::input.group x-data="{ price: {{ old('compare_price') ?? 0 }} }" for="compare-price" label="{{ __('eshop::product.purchase_price') }}" class="col">
                                    <x-eshop::money x-effect="price = value" value="price" id="compare-price" error="compare_price"/>
                                    <input type="text" x-model="price" name="compare_price" hidden>
                                </x-bs::input.group>
                            </div>

                            <x-bs::input.group for="sku" label="{{ __('SKU') }}" class="col">
                                <x-bs::input.text name="sku" value="{{ old('sku') }}" error="sku" id="sku"/>
                            </x-bs::input.group>

                            <div x-data="{
                                title: '{{ $title = addslashes(old('seo.title')) }}',
                                slug: '{{ $slug = old('slug') }}',
                    
                                updateSlug() {
                                    this.slug = slugifyLower(this.title)
                                },
                    
                                removeLineBreaks(text) {
                                    return text.replace(/\r?\n|\r|\s\s+/g, ' ')
                                }
                            }"
                                 x-on:product-name-updated.window="
                                title = $event.detail
                                updateSlug()
                             "
                                 class="d-grid gap-2"
                            >
                                <div class="d-grid gap-3">
                                    <x-bs::input.group for="seo-title" label="{{ __('eshop::seo.title') }} SEO">
                                        <input type="text" @class(["form-control", "is-invalid" => $errors->has('seo.title')]) x-model="title" x-on:input="updateSlug()" name="seo[title]" id="seo-title" error="seo.title" required/>
                                        @error('seo.title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </x-bs::input.group>

                                    <x-bs::input.group for="slug" label="{{ __('Slug') }}">
                                        <x-bs::input.text x-model="slug" name="slug" error="slug" id="slug" required/>
                                    </x-bs::input.group>
                                </div>
                            </div>
                        </x-bs::card.body>
                    </x-bs::card>
                </div>
            </div>
        </div>
    </form>
@endsection
