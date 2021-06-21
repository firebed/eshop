@extends('eshop::customer.layouts.master', ['title' =>  __('Change password')])

@section('main')
    <div class="container-fluid bg-pink-500">
        <div class="container pt-4">
            <div class="row py-4">
                <div class="col fs-3 text-light">{{ user()->fullName }}</div>
            </div>
        </div>
    </div>

    @include('eshop::customer.account.partials.account-navbar')

    <div class="container-fluid py-3">
        <div class="container">
            <h1 class="fs-4 fw-normal mb-4">{{ __("Change password") }}</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ __("Your password was changed!") }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="post" action="{{ route('account.password.update', app()->getLocale()) }}">
                @method('PUT')
                @csrf

                <div class="row row-cols-1 g-4 mb-4">
                    <div class="col">
                        <x-bs::input.floating-label for="old-password" label="{{ __('Old password') }}">
                            <x-bs::input.password name="old_password" error="old_password" id="old-password" placeholder="{{ __('Old password') }}"/>
                        </x-bs::input.floating-label>
                    </div>

                    <div class="col">
                        <x-bs::input.floating-label for="password" label="{{ __('New password') }}">
                            <x-bs::input.password name="password" error="password" id="password" placeholder="{{ __('New password') }}"/>
                        </x-bs::input.floating-label>
                    </div>

                    <div class="col">
                        <x-bs::input.floating-label for="confirm-password" label="{{ __('Confirm password') }}">
                            <x-bs::input.password name="password_confirmation " error="password_confirmation" id="confirm-password" placeholder="{{ __('Confirm password') }}"/>
                        </x-bs::input.floating-label>
                    </div>
                </div>

                <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
            </form>
        </div>
    </div>
@endsection
