@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Shipping methods") }}</div>
@endsection

@section('main')
    <div class="col-12 mx-auto p-4">
        <div class="hstack justify-content-between gap-3 mb-3">
            <h1 class="fs-3 mb-0">{{ $shippingMethod->name }}</h1>
        </div>

        @livewire('dashboard.shipping-methods.inaccessible-areas', ['shipping_method_id' => $shippingMethod->id])
    </div>
@endsection
