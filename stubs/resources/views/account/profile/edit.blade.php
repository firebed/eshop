@extends('layouts.master', ['title' =>  __('Profile')])

@push('header_scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endpush

@push('footer_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endpush

@section('main')
    <div class="container-fluid bg-pink-500">
        <div class="container pt-4">
            <div class="row py-4">
                <div class="col fs-3 text-light">{{ user()->fullName }}</div>
            </div>
        </div>
    </div>

    @include('account.partials.account-navbar')

    <div class="container-fluid py-3">
        <div class="container">
            <h1 class="fs-4 fw-normal mb-4">{{ __("Edit profile") }}</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ __("Changes were successfully saved") }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="post" action="{{ route('account.profile.update', app()->getLocale()) }}">
                @method('PUT')
                @csrf

                <div class="row row-cols-1 row-cols-sm-2 g-4 mb-4">
                    <div class="col">
                        <x-bs::input.floating-label for="first-name" label="{{ __('Name') }}">
                            <x-bs::input.text name="first_name" :value="old('first_name', $user->first_name)" error="first_name" id="first-name" placeholder="{{ __('Name') }}"/>
                        </x-bs::input.floating-label>
                    </div>

                    <div class="col">
                        <x-bs::input.floating-label for="last-name" label="{{ __('Surname') }}">
                            <x-bs::input.text name="last_name" :value="old('last_name', $user->last_name)" error="last_name" id="last-name" placeholder="{{ __('Surname') }}"/>
                        </x-bs::input.floating-label>
                    </div>

                    <div class="col">
                        <x-bs::input.floating-label for="phone" label="{{ __('Phone') }}">
                            <x-bs::input.text name="phone" :value="old('phone', $user->phone)" error="phone" id="phone" placeholder="{{ __('Phone') }}"/>
                        </x-bs::input.floating-label>
                    </div>

                    <div class="col">
                        <x-bs::input.floating-label for="email" label="{{ __('Email') }}">
                            <x-bs::input.email name="email" :value="old('email', $user->email)" error="email" id="email" placeholder="{{ __('Email') }}"/>
                        </x-bs::input.floating-label>
                    </div>

                    <div class="col">
                        <x-bs::input.floating-label for="gender" label="{{ __('Gender') }}">
                            <x-bs::input.select name="gender" error="gender" id="gender">
                                <option value="" disabled>{{ __("Gender") }}</option>
                                <option value="Male" @if(old('gender', $user->gender) === 'Male') selected @endif>{{ __("Male") }}</option>
                                <option value="Female" @if(old('gender', $user->gender) === 'Female') selected @endif>{{ __("Female") }}</option>
                            </x-bs::input.select>
                        </x-bs::input.floating-label>
                    </div>

                    <div class="col">
                        <x-bs::input.floating-label for="birthday" label="{{ __('Birthday') }}">
                            <x-bs::input.date name="birthday" error="birthday" :value="old('birthday', optional($user->birthday)->format('d/m/Y'))" id="birthday" placeholder="{{ __('Birthday') }}"/>
                        </x-bs::input.floating-label>
                    </div>
                </div>

                <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
            </form>
        </div>
    </div>
@endsection
