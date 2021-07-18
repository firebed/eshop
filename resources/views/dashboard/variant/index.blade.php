@extends('eshop::dashboard.layouts.master')

@push('footer_scripts')
    <script src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('main')
    <div class="col-12 col-xxl-10 mx-auto p-4 d-grid gap-3">
        @include('eshop::dashboard.variant.partials.variant-header')
        @include('eshop::dashboard.product.partials.product-navigation')

        <livewire:dashboard.product.variants-table :product="$product"/>
    </div>
@endsection
