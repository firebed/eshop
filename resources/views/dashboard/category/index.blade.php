@extends('eshop::dashboard.layouts.master')

@push('footer_scripts')
    <script src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('main')
    <div class="col-12 col-xxl-8 p-4 mx-auto d-grid gap-3">
        @include('eshop::dashboard.category.partials.category-breadcrumbs')

        @include('eshop::dashboard.category.partials.categories-table')
    </div>
@endsection
