@extends('eshop::dashboard.layouts.master')

@push('footer_scripts')
    <script src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@include('eshop::dashboard.product.partials.slim-select')

@section('main')
    <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data" class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3"
          x-data="{ submitting: false }"
          x-on:submit="submitting = true"
    >
        @csrf

        <div class="d-flex justify-content-between align-items-center">
            <h1 class="fs-3 mb-0">{{ __("eshop::product.new_product") }}</h1>

            <x-bs::button.primary type="submit" x-bind:disabled="submitting">
                <em x-show="!submitting" class="fa fa-save me-2"></em>
                <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                {{ __("Save") }}
            </x-bs::button.primary>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-7 d-flex flex-column gap-4">
                @include('eshop::dashboard.product.partials.primary')
                @include('eshop::dashboard.product.partials.pricing')
                @include('eshop::dashboard.product.partials.inventory')
                <livewire:dashboard.product.variant-types :variantTypes="old('variantTypes', [])"/>
                <livewire:dashboard.product.product-seo
                        :productName="old('name')"
                        :categoryId="old('category_id')"
                        :title="old('seo.title', '')"
                        :slug="old('slug', '')"
                        :description="old('seo.description', '')"/>
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
