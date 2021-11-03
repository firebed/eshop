@extends('eshop::customer.layouts.master', ['title' =>  __('Change password')])

@push('footer_scripts')
    @include('eshop::customer.eshop::dashboard.layouts.toasts')
@endpush

@section('main')
    @include('eshop::customer.account.partials.account-navbar')

    <div class="container-fluid py-3">
        <div class="container">
            <h1 class="fs-4 fw-normal mb-4">{{ __("Change password") }}</h1>

            <form x-data="{ submitting: false }" x-on:submit="submitting = true" method="post" action="{{ route('account.password.update', app()->getLocale()) }}">
                @method('PUT')
                @csrf

                <div class="row row-cols-1 g-4 mb-4">
                    <div class="col">
                        <x-bs::input.floating-label for="old-password" label="{{ __('Old password') }}">
                            <x-bs::input.password name="old_password" error="old_password" id="old-password" placeholder="{{ __('Old password') }}" required/>
                        </x-bs::input.floating-label>
                    </div>

                    <div class="col">
                        <x-bs::input.floating-label for="password" label="{{ __('New password') }}">
                            <x-bs::input.password name="password" error="password" id="password" placeholder="{{ __('New password') }}" required/>
                        </x-bs::input.floating-label>
                    </div>

                    <div class="col">
                        <x-bs::input.floating-label for="confirm-password" label="{{ __('Confirm password') }}">
                            <x-bs::input.password name="password_confirmation " error="password_confirmation" id="confirm-password" placeholder="{{ __('Confirm password') }}" required/>
                        </x-bs::input.floating-label>
                    </div>
                </div>

                <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                    <div x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    {{ __("Save") }}
                </x-bs::button.primary>
            </form>
        </div>
    </div>
@endsection
