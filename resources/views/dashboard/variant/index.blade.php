@extends('eshop::dashboard.layouts.product')

@section('content')
    <livewire:dashboard.product.variants-table :product="$product"/>
@endsection
