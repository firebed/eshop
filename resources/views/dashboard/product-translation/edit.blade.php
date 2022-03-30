@extends('eshop::dashboard.layouts.product', ['product' => $product])

@push('header_scripts')
    <link rel="dns-prefetch" href="https://cdn.tiny.cloud/">
    <script defer src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
    @livewire('dashboard.product.translations', ['product' => $product])
@endsection
