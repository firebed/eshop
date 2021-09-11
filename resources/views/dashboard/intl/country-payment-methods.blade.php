@extends('eshop::dashboard.layouts.master')

@push('footer_scripts')
    <script src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('main')
    <livewire:dashboard.intl.country-payment-methods/>
@endsection
