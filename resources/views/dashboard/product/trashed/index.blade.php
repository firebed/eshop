@extends('eshop::dashboard.layouts.master', [
    'header' => __("Trashed products")
])

@section('main')
    <livewire:dashboard.product.show-trashed-products/>
@endsection
