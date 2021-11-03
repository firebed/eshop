@extends('eshop::dashboard.layouts.master')

@push('header_scripts')
    <link rel="dns-prefetch" href="https://cdn.tiny.cloud/">
    <script defer src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Pages") }}</div>
@endsection

@section('main')
    <div class="col-12 col-xxl-8 p-4 mx-auto d-grid gap-3">
        <form action="{{ route('pages.update', $slug) }}" method="post">
            @csrf
            @method('put')

            <div class="d-flex justify-content-between mb-4">
                <h1 class="fs-3 mb-0">{{ __($page->name) }}</h1>
                <button class="btn btn-primary">{{ __("Save") }}</button>
            </div>
            
            <x-eshop::rich-text :value="old('content', $page->content ?? '')" name="content" error="description" id="content" rows="30" plugins="advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount" toolbar="undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | help code" menubar='file edit view insert format tools table tc help'/>
        </form>
    </div>
@endsection
