@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">
        <a href="{{ route('collections.index') }}" class="text-decoration-none">
            <em class="fas fa-chevron-left me-2"></em>
            {{ __("eshop::collection.collections") }}
        </a>
    </div>
@endsection

@section('main')
    <div class="col-12 col-xxl-6 mx-auto p-4 d-grid gap-3">
        <h1 class="fs-3">{{ $collection->name }}</h1>

        <x-bs::card>
            <x-bs::card.body>
                <form action="{{ route('collections.update', $collection) }}" method="post" class="d-grid gap-3"
                      x-data="{ submitting: false }"
                      x-on:submit="submitting = true"
                >
                    @csrf
                    @method('put')

                    <x-bs::input.group for="name" label="{{ __('eshop::collection.name') }}">
                        <x-bs::input.text x-bind:readonly="submitting" name="name" value="{{ old('name', $collection->name) }}" error="name" id="name" placeholder="{{ __('eshop::collection.input.name') }}" required/>
                    </x-bs::input.group>

                    <div>
                        <x-bs::button.primary type="submit" x-bind:disabled="submitting">
                            <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm text-light me-2"></em>
                            {{ __('eshop::collection.buttons.save') }}
                        </x-bs::button.primary>
                    </div>
                </form>
            </x-bs::card.body>
        </x-bs::card>

        <x-bs::card>
            <x-bs::card.body>
                @include('eshop::dashboard.collection.partials.collection-product-table')
            </x-bs::card.body>
        </x-bs::card>

        <x-bs::card>
            <x-bs::card.body>
                <form action="{{ route('collections.destroy', $collection) }}" method="post" class="d-grid gap-3"
                      x-data="{ submitting: false }"
                      x-on:submit="submitting = true"
                >
                    @csrf
                    @method('delete')

                    <div class="fw-500">{{ __('eshop::collection.buttons.delete') }}</div>

                    <div>{{ __('eshop::collection.delete_text') }}</div>

                    <div class="d-flex gap-3 align-items-end">
                        <input type="hidden" name="delete_name" value="{{ $collection->name }}">

                        <x-bs::input.group for="delete-name" label="{{ __('eshop::collection.collection_name') }}">
                            <x-bs::input.text name="delete_name_confirmation" error="delete_name" id="delete-name" placeholder="{{ __('eshop::collection.collection_name') }}" required/>
                        </x-bs::input.group>

                        <x-bs::button.danger type="submit" x-bind:disabled="submitting">
                            <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm text-light me-2"></em>
                            {{ __('eshop::collection.buttons.delete') }}
                        </x-bs::button.danger>
                    </div>
                </form>
            </x-bs::card.body>
        </x-bs::card>
    </div>
@endsection
