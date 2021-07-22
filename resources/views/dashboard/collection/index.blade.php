@extends('eshop::dashboard.layouts.master')

@section('main')
    <div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
        <h1 class="fs-3">{{ __('eshop::collection.collections') }}</h1>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('collections.create') }}" class="btn btn-primary">
                <em class="fas fa-plus me-2"></em>
                {{ __('eshop::collection.add_new') }}
            </a>

            <x-bs::dropdown>
                <x-bs::dropdown.button class="btn-white" id="collection-options">{{ __('eshop::collection.buttons.more') }}</x-bs::dropdown.button>
                <x-bs::dropdown.menu button="collection-options" alignment="right">
                    <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#collections-bulk-delete-modal">
                        {{ __('eshop::variant.buttons.delete') }}
                    </x-bs::dropdown.item>
                </x-bs::dropdown.menu>
            </x-bs::dropdown>
        </div>

        <x-bs::card>
            @include('eshop::dashboard.collection.partials.collections-table')
        </x-bs::card>
    </div>

    @include('eshop::dashboard.collection.partials.collections-bulk-delete-modal')
@endsection
