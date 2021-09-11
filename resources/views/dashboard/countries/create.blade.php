@extends('eshop::dashboard.layouts.master')

@section('main')
    <div class="col-5 mx-auto py-5">
        <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('countries.store') }}" method="post" class="mb-4">
            @csrf

            <div class="hstack justify-content-between gap-3 mb-4">
                <h1 class="fs-3 mb-0">{{ __("Add new country") }}</h1>

                <button x-bind:disabled="submitting" type="submit" class="btn btn-primary rounded-circle shadow-sm" style="width: 40px; height: 38px">
                    <em x-show="!submitting" class="fas fa-save"></em>
                    <span x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status"></span>
                </button>
            </div>

            <x-bs::card>
                <x-bs::card.body class="vstack gap-3">
                    <x-bs::input.floating-label for="name" label="{{ __('Name') }}">
                        <x-bs::input.text value="{{ old('name') }}" name="name" error="name" id="name" placeholder="{{ __('Name') }}"/>
                    </x-bs::input.floating-label>

                    <x-bs::input.floating-label for="code" label="{{ __('Code') }}">
                        <x-bs::input.text value="{{ old('code') }}" name="code" error="code" id="code" placeholder="{{ __('Code') }}"/>
                    </x-bs::input.floating-label>

                    <x-bs::input.floating-label for="timezone" label="{{ __('Timezone') }}">
                        <x-bs::input.text value="{{ old('timezone') }}" name="timezone" error="timezone" id="timezone" placeholder="{{ __('Timezone') }}"/>
                    </x-bs::input.floating-label>

                    <x-bs::input.checkbox id="visible" name="visible" :checked="old('visible')">
                        Ορατό στους πελάτες
                    </x-bs::input.checkbox>
                </x-bs::card.body>
            </x-bs::card>
        </form>
    </div>
@endsection
