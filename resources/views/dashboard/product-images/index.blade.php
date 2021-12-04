@extends('eshop::dashboard.layouts.product')

@section('content')
    <livewire:dashboard.product.show-product-images :productId="$product->id"/>
@endsection
