@extends('eshop::dashboard.layouts.master', ['title' => __("Product images")])

@section('header')
    <h1 class="fs-5 mb-0">
        <a href="{{ route('products.edit', $product) }}" class="text-decoration-none">
            <small class="fas fa-chevron-left me-2"></small>{{ $product->name }}
        </a>
    </h1>
@endsection

@section('main')
    <livewire:dashboard.product.show-product-images :productId="$product->id"/>
@endsection
