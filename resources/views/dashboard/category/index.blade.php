@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">{{ __("Categories") }}</h1>
@endsection

@section('main')
    <div class="col-12 col-xxl-8 p-4 mx-auto d-grid gap-3">
        @include('eshop::dashboard.category.partials.category-breadcrumbs')

        @include('eshop::dashboard.category.partials.categories-table')
    </div>
@endsection
