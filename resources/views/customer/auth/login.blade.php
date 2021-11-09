@php($description = "Συνδεθείτε στο " . config('app.name') . " για περισσότερες δυνατότητες")
@php($title = __("Login"))

@extends('eshop::customer.layouts.master', ['title' => $title])

@push('meta')
    <link rel="canonical" href="{{ route('login', app()->getLocale()) }}">
    @foreach(array_keys(config('eshop.locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ route('login', $locale) }}"/>
    @endforeach

    <meta name="description" content="{{ $description }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('login', app()->getLocale()) }}">
    @include('eshop::customer.layouts.partials.meta-logo')
    <meta name="twitter:card" content="summary"/>

    <script type="application/ld+json">{!! schema()->webPage($title, $description) !!}</script>
@endpush

@section('main')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card h-100 shadow-sm p-4">
                    <h1 class="mb-4 fs-4">{{ __("Login") }}</h1>

                    <form x-data="{ submitting: false }" x-on:submit="submitting = true" method="POST" action="{{ route('login', app()->getLocale()) }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                <div class="invalid-feedback font-weight-bold" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                <div class="invalid-feedback font-weight-bold" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4 d-flex gap-3 align-items-center">
                                <button x-bind:disabled="submitting" type="submit" class="btn btn-primary rounded-pill px-4">
                                    <div x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    {{ __("Login") }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none" href="{{ route('password.request', app()->getLocale()) }}">{{ __("Forgot Your Password?") }}</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
