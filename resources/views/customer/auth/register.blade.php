@extends('eshop::customer.layouts.master', ['title' => __("Register")])

@section('main')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card h-100 shadow-sm">
                    <div class="row g-0">
                        <div class="col">
                            <div class="card-body">
                                <h1 class="mb-4 fs-4 text-primary">{{ __("Register") }}</h1>
                                <form id="subscription-form" method="POST" action="{{ route('register', app()->getLocale()) }}">
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
                                            <a href="#" class="text-gold text-decoration-none ms-1">{{ __("terms of use") }}</a>
                                        </div>
                                        @error('terms-of-service')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <p class="text-secondary">{!! __("Fields with an asterisk (<span class='text-danger'>*</span>) are required.") !!}</p>
                                    <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                                        <button type="submit" class="btn btn-primary rounded-pill mb-3 px-4">{{ __("Register") }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
