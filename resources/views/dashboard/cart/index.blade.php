@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fs-5 fw-500">{{ __("Orders") }}</div>
@endsection

@section('main')
    <div class="col-12 p-4">
        <div class="row g-4">
            <div class="col-12 col-xxl-auto w-xxl-17r sticky-xxl-top" style="top: 5rem; height: fit-content">
                <livewire:dashboard.cart.statuses-list/>
            </div>

            <div class="col-12 col-xxl">
                <livewire:dashboard.cart.show-carts/>
            </div>
        </div>
    </div>
@endsection
