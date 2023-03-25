@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">{{ __("Categories") }}</h1>
@endsection

@section('main')
    <div class="col-12 col-xxl-10 p-4 mx-auto d-grid gap-3">
        <div class="d-grid gap-3">
            @include('eshop::dashboard.category.partials.category-breadcrumbs')

            <h1 class="fs-3 mb-0">
                <a href="{{ route('categories.edit', $category) }}" class="text-decoration-none">{{ $category->name }}</a>
            </h1>
        </div>

        <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('categories.properties.update', $property) }}" method="post" class="mb-3">
            @csrf
            @method('put')

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fs-4 mb-0">{{ __('eshop::category.edit_property') }}: {{ $property->name }}</h2>

                <div class="flex gap-3">
                    <x-bs::button.white x-data="" @click.prevent="$dispatch('show-property-translations')">
                        <em class="fas fa-language text-secondary"></em> Μεταφράσεις
                    </x-bs::button.white>

                    <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                        <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm me-2"></em>
                        {{ __('eshop::buttons.save') }}
                    </x-bs::button.primary>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2">
                <div class="col">
                    <x-bs::card>
                        <livewire:dashboard.category.property-choices :choices="old('choices', $choices)"/>
                    </x-bs::card>
                </div>

                <div class="col">
                    @include('eshop::dashboard.category-property.partials.category-property-form')
                </div>
            </div>
        </form>

        <div class="row row-cols-1 row-cols-md-2 justify-content-md-end">
            <div class="col">
                @include('eshop::dashboard.category-property.partials.category-property-delete')
            </div>
        </div>

        <livewire:dashboard.category.category-property-translations :property="$property"/>
    </div>
@endsection
