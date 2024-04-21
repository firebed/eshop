@extends('eshop::dashboard.layouts.product', ['product' => $product])

@php($tinymce = api_key('TINYMCE_API_KEY'))

@push('header_scripts')
    <link rel="dns-prefetch" href="https://cdn.tiny.cloud/">
    <script defer src="https://cdn.tiny.cloud/1/{{ $tinymce }}/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
    @livewire('dashboard.product.translations', ['product' => $product])
@endsection
