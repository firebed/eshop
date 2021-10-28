@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Countries") }}</div>
@endsection

@section('main')
    <div class="col-9 mx-auto py-5">
        <div class="hstack justify-content-between gap-3 mb-4">
            <h1 class="fs-3 mb-0">{{ $country->name }}</h1>

            <x-bs::dropdown>
                <button class="btn btn-smoke rounded-circle" data-bs-toggle="dropdown">
                    <em class="fas fa-bars"></em>
                </button>

                <x-bs::dropdown.menu button="options" alignment="right">
                    <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#delete-modal"><em class="far fa-trash-alt me-2"></em>{{ __("Delete") }}</x-bs::dropdown.item>
                </x-bs::dropdown.menu>
            </x-bs::dropdown>
        </div>

        <div class="row">
            <div class="col-5">
                @include('eshop::dashboard.countries.partials.country-card')
            </div>

            <div class="col-7">
                <x-bs::card>
                    <div class="hstack justify-content-between p-3">
                        <div class="fw-500 fs-5">Νομοί</div>

                        <div class="hstack gap-2">
                            <x-bs::dropdown>
                                <button class="btn btn-smoke rounded-circle" data-bs-toggle="dropdown">
                                    <em class="fas fa-bars"></em>
                                </button>

                                <x-bs::dropdown.menu button="options" alignment="right" class="shadow">
                                    <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#delete-modal">
                                        <em class="far fa-trash-alt me-2"></em>{{ __("Delete") }}
                                    </x-bs::dropdown.item>
                                </x-bs::dropdown.menu>
                            </x-bs::dropdown>

                            <a x-data x-on:click.prevent="$dispatch('edit-province', {name:'', shippable: true})" href="#create-province-form" data-bs-toggle="offcanvas" class="btn btn-primary rounded-circle shadow-sm">
                                <em class="fas fa-plus small"></em>
                            </a>
                        </div>
                    </div>

                    <div id="provinces" class="table-responsive scrollbar" style="max-height: 500px">
                        @include('eshop::dashboard.countries.partials.provinces')
                    </div>
                </x-bs::card>
            </div>
        </div>
    </div>

    @include('eshop::dashboard.countries.partials.country-delete-form')
    @include('eshop::dashboard.countries.partials.create-province-form')
    @include('eshop::dashboard.countries.partials.edit-province-form')
    @include('eshop::dashboard.countries.partials.provinces-delete-form')
@endsection
