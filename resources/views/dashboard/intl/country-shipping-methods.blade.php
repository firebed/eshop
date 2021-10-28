@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Shipping options") }}</div>
@endsection

@section('main')
    <livewire:dashboard.intl.country-shipping-methods/>
@endsection
