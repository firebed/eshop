@extends('eshop::dashboard.layouts.master')

@section('main')
    <div class="col-5 mx-auto py-5">
        <div class="hstack justify-content-between gap-3 mb-4">
            <h1 class="fs-3 mb-0">{{ __("New payment method") }}</h1>

            <a href="{{ route('payment-methods.create') }}" class="btn btn-primary rounded-circle shadow-sm">
                <em class="fas fa-plus"></em>
            </a>
        </div>

        <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('payment-methods.store') }}" method="post" class="mb-4">
            @csrf

            <x-bs::card>
                <x-bs::card.body class="vstack gap-3">
                    <x-bs::input.floating-label for="name" label="{{ __('Name') }}">
                        <x-bs::input.text value="{{ old('name') }}" name="name" error="name" id="name" placeholder="{{ __('Name') }}"/>
                    </x-bs::input.floating-label>

                    <x-bs::input.checkbox id="show-total-on-order-form" name="show_total_on_order" :checked="old('show_total_on_order')">
                        {{ __('Show total on order form') }}
                    </x-bs::input.checkbox>

                    <div>
                        <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                            <span x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status"></span>
                            {{ __("Save") }}
                        </x-bs::button.primary>
                    </div>
                </x-bs::card.body>
            </x-bs::card>
        </form>
    </div>
@endsection
