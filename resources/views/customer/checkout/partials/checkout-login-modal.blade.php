<div class="modal fade" id="login-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content mx-auto shadow">
            <div class="modal-header">
                <div class="fs-5 fw-500 modal-title" id="exampleModalLabel">{{ __('Login') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 p-sm-4 p-xl-5">
                <form method="POST" action="{{ route('checkout.login', app()->getLocale()) }}">
                    @csrf
                    <div class="d-grid gap-3">
                        <x-bs::input.floating-label for="email" label="{{ __('Email') }}">
                            <x-bs::input.email id="email" name="email" placeholder="{{ __('Email') }}"/>
                        </x-bs::input.floating-label>

                        <x-bs::input.floating-label for="password" label="{{ __('Password') }}">
                            <x-bs::input.password id="password" name="password" autocomplete="on" placeholder="{{ __('Password') }}"/>
                        </x-bs::input.floating-label>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>

                        @if (Route::has('password.request'))
                            <a class="text-decoration-none" href="{{ route('password.request', app()->getLocale()) }}">{{ __("Forgot your password?") }}</a>
                        @endif
                    </div>
                </form>

                <hr>

                <div class="d-grid gap-3">
                    <div>{{ __('Don\'t have an account?') }}</div>
                    <a href="{{ route('register', app()->getLocale()) }}" class="btn btn-green">{{ __('Register') }}</a>
                </div>

                <div class="text-center my-2 fst-italic">-{{ __('or') }}-</div>

                <div class="d-grid">
                    <a href="{{ route('checkout.details.edit', app()->getLocale()) }}" class="btn btn-secondary">{{ __('Continue as guest') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
