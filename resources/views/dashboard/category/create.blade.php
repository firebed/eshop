@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">{{ __("Categories") }}</h1>
@endsection

@section('main')
    <div class="col-12 col-xxl-10 p-4 mx-auto d-grid gap-3">
        <div class="d-grid gap-3">
            @include('eshop::dashboard.category.partials.category-breadcrumbs', ['category' => $parent])

            <h1 class="fs-3 mb-0">
                @if($parent)
                    <a href="{{ route('categories.edit', $parent) }}" class="text-decoration-none">{{ $parent->name }} </a>
                @else
                    <a href="{{ route('categories.index') }}" class="text-decoration-none">{{ __('eshop::category.home') }}</a>
                @endif
            </h1>
        </div>

        <div class="row row-cols-1 row-cols-md-2">
            <div class="col">
                <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data" class="mb-3">
                    @csrf

                    @if($parent)
                        <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="fs-4 mb-0">{{ __('eshop::category.create') }}</h2>

                        <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                            <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm me-2"></em>
                            {{ __('eshop::buttons.save') }}
                        </x-bs::button.primary>
                    </div>

                    <div class="d-grid gap-3">
                        @include('eshop::dashboard.category.partials.category-primary')

                        @include('eshop::dashboard.category.partials.accessibility')

                        @include('eshop::dashboard.category.partials.category-seo')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
