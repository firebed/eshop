@extends('customer.layouts.master', ['title' => __("Home")])

@push('header_scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/keen-slider@latest/keen-slider.min.css" />
@endpush

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/keen-slider@latest/keen-slider.js"></script>
@endpush

@section('main')
    <div class="container-fluid py-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-3 d-none d-md-block">
                    <x-eshop-homepage-categories-list/>
                </div>
                <div class="col">
                    @include('eshop::customer.homepage.partials.carousel')
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12 mb-4 col-xl-3 mb-xl-0">
                    <x-eshop-top-sellers/>
                </div>
                <div class="col-12 col-xl-9">
                    <x-eshop-popular-products/>
                </div>
            </div>
        </div>
    </div>
@endsection
