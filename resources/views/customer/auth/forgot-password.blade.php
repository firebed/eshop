@extends('eshop::customer.layouts.master', ['title' => __("Rest password")])

@section('main')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h1 class="fs-4 mb-4">{{ __('Reset Password') }}</h1>

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>Για λόγους ασφαλείας ο κωδικός σας είναι γνωστός μόνο σε εσάς. Σε περίπτωση που τον ξεχάσατε θα πρέπει να δημιουργήσετε νέο κωδικό πρόσβασης.</p>
                        <p>Παρακαλώ συμπληρώστε παρακάτω το email σας όπου θα σας σταλεί ένας σύνδεσμος στον οποίο θα πρέπει να πατήσετε. Ύστερα θα μεταφερθείτε στη φόρμα όπου θα έχετε την δυνατότητα δημιουργίας νέου κωδικού πρόσβασης.</p>

                        <form method="POST" action="{{ route('password.email', app()->getLocale()) }}" class="mt-5">
                            @csrf

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('Send Password Reset Link') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
