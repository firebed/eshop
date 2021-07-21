@extends('eshop::dashboard.layouts.master')

@push('footer_scripts')
    <script src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@include('eshop::dashboard.product.partials.slim-select')

@section('main')
    <div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
        <form method="post" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" class="d-grid gap-3"
              x-data="{ submitting: false }"
              x-on:submit="submitting = true"
        >
            @csrf
            @method('put')

            <div class="d-grid gap-2">
                <a href="{{ route('products.index') }}" class="text-secondary text-decoration-none"><i class="fa fa-chevron-left"></i> {{ __("All products") }}</a>

                <div class="d-flex align-items-center justify-content-between">
                    <h1 class="fs-3 mb-0">{{ $product->name }}</h1>

                    <x-bs::button.primary type="submit" x-bind:disabled="submitting">
                        <em x-show="!submitting" class="fa fa-save me-2"></em>
                        <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                        {{ __("eshop::product.actions.save") }}
                    </x-bs::button.primary>
                </div>
            </div>

            @include('eshop::dashboard.product.partials.product-navigation')

            <div class="row g-4">
                <div class="col-12 col-md-7 d-flex flex-column gap-4">
                    @include('eshop::dashboard.product.partials.primary')
                    @include('eshop::dashboard.product.partials.pricing')
                    @include('eshop::dashboard.product.partials.inventory')


                    <x-bs::card>
                        <x-bs::card.body class="d-grid gap-3">
                            <div class="fw-500">{{ __("Variants") }}</div>

                            <div>{{ __('eshop::product.variant_type.has_variants') }}</div>

                            <livewire:dashboard.product.variant-types :variantTypes="old('variantTypes', $variantTypes)"/>

                            @include('eshop::dashboard.product.partials.variants-display')
                        </x-bs::card.body>
                    </x-bs::card>

                    <livewire:dashboard.product.product-seo
                            :productName="old('name', $product->name)"
                            :categoryId="old('category_id', $product->category_id)"
                            :title="old('seo.title', $product->seo->title ?? '')"
                            :slug="old('slug', $product->slug ?? '')"
                            :description="old('seo.description', $product->seo->description ?? '')"/>
                </div>

                <div class="col-12 col-md-5 d-flex flex-column gap-4">
                    @include('eshop::dashboard.product.partials.image')
                    @include('eshop::dashboard.product.partials.organization')

                    <livewire:dashboard.product.product-properties
                            :categoryId="old('category_id', $product->category_id ?? null)"
                            :properties="old('properties', $properties ?? [])"/>

                    @include('eshop::dashboard.product.partials.accessibility')
                </div>
            </div>
        </form>

        @include('eshop::dashboard.product.partials.product-delete-form')
    </div>
@endsection
