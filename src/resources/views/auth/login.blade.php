@extends('com::customer.layouts.master', ['title' => __("Login")])

@section('main')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card h-100 shadow-sm">
                    <div class="row g-0">
                        <div class="col">
                            <div class="card-body">
                                <h1 class="mb-4 fs-4 text-primary">{{ __("Login") }}</h1>
                                <form method="POST" action="{{ route('login', app()->getLocale()) }}">
                                    @csrf

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

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
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __("Login") }}</button>

                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link text-decoration-none" href="{{ route('password.request', app()->getLocale()) }}">{{ __("Forgot your password?") }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-4 d-none d-xl-block">
                            <div class="ratio ratio-1x1 h-100">
                                <img src="{{ asset('storage/images/login.jpg') }}" alt="knitting needles heart" class="rounded-end">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
