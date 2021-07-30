@extends('eshop::dashboard.layouts.master')

@section('main')
    <div class="col-12 col-xxl-10 p-4 mx-auto d-grid gap-3">
        <div class="d-grid gap-3">
            @include('eshop::dashboard.category.partials.category-breadcrumbs')

            <h1 class="fs-3 mb-0">
                <a href="{{ route('categories.edit', $category) }}" class="text-decoration-none">{{ $category->name }}</a>
            </h1>
        </div>

        <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('categories.properties.store', $category) }}" method="post" class="mb-3">
            @csrf

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fs-4 mb-0">{{ __('eshop::category.create_property') }}</h2>

                <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                    <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm me-2"></em>
                    {{ __('eshop::buttons.save') }}
                </x-bs::button.primary>
            </div>

            <div class="row row-cols-1 row-cols-md-2">
                <div class="col">
                    @include('eshop::dashboard.category-property.partials.category-property-form')
                </div>

                <div class="col">
                    <x-bs::card>
                        <x-bs::card.body>
                            <div class="fw-500 mb-3">{{ __('eshop::category.choices') }}</div>

                            <livewire:dashboard.category.property-choices :choices="old('choices', [])"/>
                        </x-bs::card.body>
                    </x-bs::card>
                </div>
            </div>
        </form>
    </div>
@endsection
