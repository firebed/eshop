<?php

namespace Eshop\Providers;

use Eshop\Actions\Fortify\CreateNewUser;
use Eshop\Actions\Fortify\ResetUserPassword;
use Eshop\Actions\Fortify\UpdateUserPassword;
use Eshop\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\PasswordResetResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Fortify::loginView(function () {
            session(['url.intended' => url()->previous()]);
            return view('eshop::customer.auth.login');
        });

        Fortify::registerView(function () {
            session(['url.intended' => url()->previous()]);
            return view('eshop::customer.auth.register');
        });

        ResetPassword::createUrlUsing(fn($notifiable, $token) => url(route('password.reset', [
            'lang'  => app()->getLocale(),
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], FALSE)));

        Fortify::requestPasswordResetLinkView(fn() => view('eshop::customer.auth.forgot-password'));
        Fortify::resetPasswordView(fn($request) => view('eshop::customer.auth.reset-password', ['token' => $request->token, 'email' => $request->email]));
        Fortify::verifyEmailView(fn() => view('eshop::customer.auth.verify-email'));
        Fortify::confirmPasswordView(fn() => view('eshop::customer.auth.confirm-password'));

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        $this->app->singleton(PasswordResetResponse::class, \Eshop\Actions\Fortify\PasswordResetResponse::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
