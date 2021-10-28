@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">
        <a href="{{ route('products.edit', $product) }}" class="text-decoration-none">
            <small class="fas fa-chevron-left me-2"></small>{{ $product->name }}
        </a>
    </h1>
@endsection

@section('main')
    <div class="col-12 col-xxl-10 mx-auto p-4 d-grid gap-3">
        @include('eshop::dashboard.variant.partials.variant-header')
        @include('eshop::dashboard.product.partials.product-navigation')

        <livewire:dashboard.product.variants-table :product="$product"/>
    </div>
@endsection
