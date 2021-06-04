@extends('customer.layouts.master', ['title' =>  implode(' | ', [$product->title, $product->manufacturer->name ?? NULL])])

@section('main')
    <x-com-category-breadcrumb :category="$category"/>

    <div class="container-fluid bg-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md mb-4 mb-md-0">
                    @include('customer.product.partials.images')
                </div>
                <div class="col-12 col-md">
                    <h1 class="fs-3 fw-500">{{ $product->title }}</h1>

                    @include('customer.product.partials.product-category')
                    @includeWhen($product->isVariant(), 'customer.product.partials.product-parent')
                    @includeWhen(isset($product->manufacturer), 'customer.product.partials.product-manufacturer')

                    <div class="mt-4">
                        @include('customer.product.partials.product-properties')
                    </div>

                    @includeWhen($product->description !== NULL, 'customer.product.partials.product-description')

                    <div class="d-grid gap-2 mt-4">
                        @if($product->has_variants)
                            <a href="#product-variants" class="btn btn-primary btn-block">{{ __("See all variants") }} ({{ $product->variants_count }})</a>
                        @elseif($product->available)
                            @push('footer_scripts')
                                <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
                            @endpush
                            <livewire:customer.product.add-to-cart-form :product="$product"/>
                        @else
                            <div class="col-12 mb-4">
                                <div class="h3 mb-0">{{ format_currency($product->netValue) }}</div>
                            </div>
                            @include('customer.product.partials.out-of-stock-button')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($product->has_variants)
        <div id="product-variants" class="container-fluid mb-4 py-4 bg-light">
            <livewire:customer.product.product-variants :product="$product" :category="$category" />
        </div>
    @endif
@endsection
