@extends('eshop::dashboard.layouts.master')

@push('footer_scripts')
    <script src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('main')
    <div class="col-12 col-xxl-10 p-4 mx-auto d-grid gap-3">
        @include('eshop::dashboard.category.partials.category-breadcrumbs')

        <div class="row row-cols-1 row-cols-md-2">
            <div class="col">
                @if($category->isFolder())
                    <div class="d-grid gap-3">
                        @include('eshop::dashboard.category.partials.categories-table', ['parentId' => $category->id])
                    </div>
                @else
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="fs-4 mb-0">{{ __('eshop::category.properties') }}</h2>

                        <div>
                            <a href="{{ route('categories.properties.create', $category) }}" class="btn btn-primary">
                                <em class="fas fa-plus me-2"></em>
                                {{ __('eshop::buttons.add') }}
                            </a>
                        </div>
                    </div>

                    @include('eshop::dashboard.category.partials.category-properties-table')
                @endif
            </div>

            <div class="col">
                <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('categories.update', $category) }}" method="post" enctype="multipart/form-data" class="mb-3">
                    @csrf
                    @method('put')

                    <div class="d-grid gap-3">
                        <div class="d-flex justify-content-end">
                            <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                                <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm me-2"></em>
                                {{ __('eshop::buttons.save') }}
                            </x-bs::button.primary>
                        </div>

                        @include('eshop::dashboard.category.partials.category-primary')
                        @include('eshop::dashboard.category.partials.accessibility')
                        @include('eshop::dashboard.category.partials.category-seo')
                    </div>
                </form>

                @include('eshop::dashboard.category.partials.category-delete')
            </div>
        </div>
    </div>
@endsection
