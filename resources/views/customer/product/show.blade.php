@extends('eshop::customer.layouts.master', ['title' =>  $product->seo->title])

@push('header_scripts')
    <link rel="stylesheet" href="https://unpkg.com/simplebar@5.3.3/dist/simplebar.css"/>
    <script src="https://unpkg.com/simplebar@5.3.3/dist/simplebar.min.js"></script>

    @includeUnless($product->has_variants, 'eshop::customer.product.partials.jsonld-product')
@endpush

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
    <script src="{{ asset('vendor/eshop/js/fslightbox.js') }}"></script>
@endpush

@section('main')
    @if($product->isVariant())
        <x-eshop-category-breadcrumb :category="$category" :product="$product->parent" :variant="$product"/>
    @else
        <x-eshop-category-breadcrumb :category="$category" :product="$product" :variant="null"/>
    @endif

    <div class="container-fluid bg-white py-5">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col">
                    @include('eshop::customer.product.partials.images')
                </div>

                <div class="col d-grid gap-4 align-self-start">
                    <h1 class="fs-3 fw- mb-0">{{ $product->trademark }}</h1>

                    @include('eshop::customer.product.partials.product-category')
                    @includeWhen($product->isVariant(), 'eshop::customer.product.partials.product-parent')
                    @includeWhen(isset($product->manufacturer), 'eshop::customer.product.partials.product-manufacturer')

                    @includeWhen(filled($properties), 'eshop::customer.product.partials.product-properties')

                    @includeWhen($product->description !== NULL, 'eshop::customer.product.partials.product-description')

                    <div class="d-grid gap-2">
                        @if($product->has_variants)
                            @if($product->variants_display === 'grid')
                                <a href="#product-variants" class="btn btn-primary btn-block">{{ __("See all variants") }} ({{ $product->variants_count }})</a>
                            @endif

                            @if($product->variants_display === 'buttons')
                                <livewire:customer.product.product-variants-buttons :product="$product"/>
                            @endif

                            @if($product->variants_display === 'list')
                            @endif
                        @elseif($product->canBeBought())
                            <livewire:customer.product.add-to-cart-form :product="$product"/>
                        @else
                            <div class="col-12 mb-4">
                                <div class="h3 mb-0">{{ format_currency($product->netValue) }}</div>
                            </div>

                            <button class="btn btn-danger" disabled>{{ __("Out of stock") }}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($product->has_variants && $product->variants_display === 'grid')
        <div id="product-variants" class="container-fluid mb-4 py-4 bg-light">
            <livewire:customer.product.product-variants :product="$product" :category="$category"/>
        </div>
    @endif
@endsection