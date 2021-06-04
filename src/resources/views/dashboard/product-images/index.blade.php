@extends('dashboard.layouts.dashboard', ['title' => __("Product images")])

@section('main')
    @livewire('dashboard.product.show-product-images', compact('productId'))
@endsection
