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
        <h1 class="fs-3">{{ __('eshop::collection.add_new_collection') }}</h1>

        <x-bs::card>
            <x-bs::card.body>
                <form action="{{ route('collections.store') }}" method="post" class="d-grid gap-3"
                    x-data="{ submitting: false }"
                    x-on:submit="submitting = true"
                >
                    @csrf

                    <x-bs::input.group for="name" label="{{ __('eshop::collection.name') }}">
                        <x-bs::input.text x-bind:readonly="submitting" name="name" value="{{ old('name') }}" error="name" id="name" placeholder="{{ __('eshop::collection.input.name') }}" required/>
                    </x-bs::input.group>

                    <div>
                        <x-bs::button.primary type="submit" x-bind:disabled="submitting">
                            <em x-cloak x-show="submitting" class="fas fa-circle-notch fa-spin me-2"></em>
                            {{ __('eshop::collection.buttons.save') }}
                        </x-bs::button.primary>
                    </div>
                </form>
            </x-bs::card.body>
        </x-bs::card>
    </div>
@endsection
