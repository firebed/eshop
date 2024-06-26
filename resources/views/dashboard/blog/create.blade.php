@extends('eshop::dashboard.layouts.master')

@php($tinymce = api_key('TINYMCE_API_KEY'))

@push('header_scripts')
    <link rel="dns-prefetch" href="https://cdn.tiny.cloud/">
    <script src="https://cdn.tiny.cloud/1/{{ $tinymce }}/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    @include('eshop::dashboard.blog.partials.editor')
@endpush

@section('header')
    <h1 class="fs-5 mb-0">Blogs</h1>
@endsection

@section('main')
    <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data" class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
        @csrf

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Αποθήκευση</button>
        </div>

        <x-bs::card>
            <x-bs::card.body x-data="{
                updateSlug() {
                    $refs.slug.value = slugifyLower($refs.title.value.trim())
                }
            }" class="d-grid gap-4">
                @include('eshop::dashboard.blog.forms.blog-form')
            </x-bs::card.body>
        </x-bs::card>
    </form>
@endsection
