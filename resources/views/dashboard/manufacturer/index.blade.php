@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">{{ __("Manufacturers") }}</h1>
@endsection

@section('main')
    <livewire:dashboard.product.show-manufacturers/>
@endsection
