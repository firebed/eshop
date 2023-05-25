@php($description = "Εγγραφείτε στο " . config('app.name') . " για περισσότερες δυνατότητες")
@php($title = __("Register"))

@extends('eshop::customer.layouts.master', ['title' => $title])

@push('meta')
    <link rel="canonical" href="{{ route('register', app()->getLocale()) }}">
    @foreach(array_keys(eshop('locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ route('register', $locale) }}"/>
    @endforeach

    <meta name="description" content="{{ $description }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('register', app()->getLocale()) }}">
    @include('eshop::customer.layouts.partials.meta-logo')
    <meta name="twitter:card" content="summary"/>

    <script type="application/ld+json">{!! schema()->webPage($title, $description) !!}</script>
@endpush

@section('main')
    <div class="container-fluid py-5">
        <div class="container-xxl">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card h-100 shadow-sm p-4">
                    <h1 class="mb-4 fs-4">{{ __("Register") }}</h1>
                    <form x-data="{ submitting: false }" x-on:submit="submitting = true" method="POST" action="{{ route('register', app()->getLocale()) }}">
                        @csrf
                        <div class="row">
                            <div class="col-sm mb-3">
                                <label for="first-name" class="form-label"><span class="text-danger">*</span> {{ __("Name") }}</label>
                                <input class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="first-name" required value="{{ old('first_name') }}">
                                @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm mb-3">
                                <label for="last-name" class="form-label"><span class="text-danger">*</span> {{ __("Surname") }}</label>
                                <input class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last-name" required value="{{ old('last_name') }}">
                                @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label"><span class="text-danger">*</span> {{ __("Email") }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" required value="{{ old('email') }}">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-sm mb-3">
                                <label for="password" class="form-label"><span class="text-danger">*</span> {{ __("Password") }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm mb-3">
                                <label for="password-confirmation" class="form-label"><span class="text-danger">*</span> {{ __("Password confirmation") }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" id="password-confirmation" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input @error('terms-of-service') is-invalid @enderror" type="checkbox" id="terms-of-service" name="terms-of-service" required>
                            <div class="d-flex">
                                <label class="form-check-label" for="terms-of-service">{{ __("I have read and agree to the") }}</label>
                                <a href="{{ route('pages.show', [app()->getLocale(), 'terms-of-service']) }}" target="_blank" class="text-gold text-decoration-none ms-1">{{ __("terms of use") }}</a>
                            </div>
                            @error('terms-of-service')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-center">
                            <button x-bind:disabled="submitting" type="submit" class="btn btn-primary rounded-pill mb-3 px-4">
                                <div x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                {{ __("Register") }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
