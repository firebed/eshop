@extends('eshop::dashboard.layouts.master')

@section('header')
    <a href="{{ route('products.index') }}" class="text-dark text-decoration-none">{{ __("Products") }}</a>
@endsection

@push('header_scripts')
    <link rel="dns-prefetch" href="https://cdn.tiny.cloud/">
    <script defer src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.css" rel="stylesheet"/>
@endpush

@push('footer_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.js"></script>
@endpush

@section('main')
    <form method="post" action="{{ route('products.create') }}"
          enctype="multipart/form-data"
          x-data="{ submitting: false }"
          x-on:submit="submitting = true"
          class="col-12 p-4"
    >
        @csrf

        <div class="col-12 col-xxl-9 mx-auto mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <h1 class="fs-3">{{ __("eshop::product.new_product") }}</h1>

                <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                    <em x-show="!submitting" class="fa fa-save me-2"></em>
                    <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                    {{ __("eshop::product.actions.save") }}
                </x-bs::button.primary>
            </div>
        </div>

        <div class="col-12 col-xxl-9 mx-auto">
            <div class="row g-4">
                <div class="col-12 col-lg-7 d-flex flex-column gap-4">
                @include('eshop::dashboard.product.partials.primary')
                @include('eshop::dashboard.product.partials.pricing')
                @include('eshop::dashboard.product.partials.inventory')

                <x-bs::card>
                    <x-bs::card.body class="d-grid gap-3">
                        <div class="fw-500">{{ __("Variants") }}</div>

                        <div>{{ __('eshop::product.variant_type.has_variants') }}</div>

                        <livewire:dashboard.product.variant-types :variantTypes="old('variantTypes', [])"/>

                        @include('eshop::dashboard.product.partials.variants-display')
                    </x-bs::card.body>
                </x-bs::card>

                @include('eshop::dashboard.product.partials.product-seo')
            </div>
            <div class="col-12 col-md-5 d-flex flex-column gap-4">
                @include('eshop::dashboard.product.partials.image')
                @include('eshop::dashboard.product.partials.organization')

                <livewire:dashboard.product.product-properties
                    :categoryId="old('category_id')"
                    :properties="old('properties', [])"
                />

                @include('eshop::dashboard.product.partials.accessibility')
            </div>
        </div>

        <div>
            <x-bs::button.primary type="submit" x-bind:disabled="submitting">
                <em x-show="!submitting" class="fa fa-save me-2"></em>
                <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                {{ __("Save") }}
            </x-bs::button.primary>
        </div>
    </form>
@endsection
