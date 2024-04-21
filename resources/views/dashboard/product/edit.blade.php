@extends('eshop::dashboard.layouts.master')

@php($tinymce = api_key('TINYMCE_API_KEY'))

@section('header')
    <a href="{{ route('products.index') }}" class="text-dark text-decoration-none">{{ __("Products") }}</a>
@endsection

@push('header_scripts')
    <link rel="dns-prefetch" href="https://cdn.tiny.cloud/">
    <script defer src="https://cdn.tiny.cloud/1/{{ $tinymce }}/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.css" rel="stylesheet"/>
@endpush

@push('footer_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.js"></script>
@endpush

@section('main')
    <form method="post" action="{{ route('products.update', $product->id) }}"
          enctype="multipart/form-data"
          x-data="{ submitting: false }"
          x-on:submit="submitting = true"
          class="col-12 p-4"
    >
        @csrf
        @method('put')

        <div class="col-12 col-xxl-9 mx-auto mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                @includeWhen(isset($product), 'eshop::dashboard.product.partials.product-header')

                <div class="btn-toolbar gap-2" role="toolbar">
                    <div class="btn-group">
                        <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                            <em x-show="!submitting" class="fa fa-save me-2"></em>
                            <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                            {{ __("eshop::product.actions.save") }}
                        </x-bs::button.primary>
                    </div>

                    <div class="btn-group">
                        <button class="btn btn-white w-3r text-secondary" data-bs-toggle="offcanvas" data-bs-target="#label-print-dialog" type="button" title="Εκτύπωση ετικετών">
                            <em class="fas fa-receipt"></em>
                        </button>

                        @can('Copy products')
                            <a href="{{ route('products.copy.create', $product) }}" class="btn btn-white w-3r text-secondary" type="button" title="Αντιγραφή προϊόντος">
                                <em class="fas fa-copy"></em>
                            </a>
                        @endcan

                        @if(productRouteExists())
                            <a class="btn btn-white w-3r text-secondary" href="{{ productRoute($product) }}" title="Προβολή">
                                <em class="fas fa-eye"></em>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xxl-9 mx-auto">
            <div class="row g-4">
                <div class="col-12 col-lg-7 d-flex flex-column gap-4">
                    @include('eshop::dashboard.product.partials.primary')
                    @include('eshop::dashboard.product.partials.pricing')
                    @includeWhen($product->channels->isNotEmpty(), 'eshop::dashboard.product.partials.channel-pricing')
                    @include('eshop::dashboard.product.partials.inventory')

                    <x-bs::card>
                        <x-bs::card.body class="d-grid gap-3">
                            <div class="fw-500">{{ __("Variants") }}</div>

                            <div>{{ __('eshop::product.variant_type.has_variants') }}</div>

                            <livewire:dashboard.product.variant-types :variantTypes="old('variantTypes', $variantTypes)"/>

                            @include('eshop::dashboard.product.partials.variants-display')
                        </x-bs::card.body>
                    </x-bs::card>

                    @include('eshop::dashboard.product.partials.product-seo')
                </div>

                <div class="col-12 col-lg-5 d-flex flex-column gap-4">
                    @include('eshop::dashboard.product.partials.image')
                    @include('eshop::dashboard.product.partials.organization')

                    <livewire:dashboard.product.product-properties
                        :categoryId="old('category_id', $product->category_id ?? null)"
                        :properties="old('properties', $properties ?? [])"/>

                    @include('eshop::dashboard.product.partials.accessibility')
                </div>
            </div>
        </div>
    </form>

    @include('eshop::dashboard.product.partials.product-delete-form')

    <form action="{{ route('labels.export') }}" method="POST" target="_blank">
        @csrf
        <input type="hidden" name="labels[0][product_id]" value="{{ $product->id }}">
        <input type="hidden" name="labels[0][quantity]" value="1">
        <x-label-printer-dialog id="label-print-dialog"/>
    </form>
@endsection
