@extends('dashboard.layouts.dashboard')

@push('header_scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.2/slimselect.min.css" rel="stylesheet"/>
@endpush

@push('footer_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.2/slimselect.min.js" defer></script>
    <script src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('main')
    @livewire('dashboard.product.edit-product', compact('product'))
@endsection
