<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Domain\Users\Actions\CreateNewUser;
use Domain\Users\Actions\ResetUserPassword;
use Domain\Users\Actions\UpdateUserPassword;
use Domain\Users\Actions\UpdateUserProfileInformation;
use Foundation\Settings\GeneralSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;
use Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse as ProfileInformationUpdatedResponseContract;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Modules\Web\Account\Responses\PasswordUpdateResponse;
use Modules\Web\Account\Responses\ProfileInformationUpdatedResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PasswordUpdateResponseContract::class, PasswordUpdateResponse::class);
        $this->app->singleton(ProfileInformationUpdatedResponseContract::class, ProfileInformationUpdatedResponse::class);
    }

    public function boot(): void
    {
        $this->configureActions();
        $this->configureRateLimits();
        $this->configureViews();
    }

    protected function configureActions(): static
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        return $this;
    }

    protected function configureRateLimits(): static
    {
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->getAuthIdentifier() ?? $request->ip());
        });

        return $this;
    }

    protected function configureViews(): static
    {
        Fortify::loginView(fn () => Inertia::render('Auth/AuthLogin', [
            'action' => route('login.store'),
            'status' => session('status'),
            'forgotPasswordUrl' => Features::enabled(Features::resetPasswords()) ? route('password.request') : null,
            'registerUrl' => $this->registrationAllowed() ? route('register') : null,
        ]));

        Fortify::registerView(function () {
            abort_unless($this->registrationAllowed(), 404);

            return Inertia::render('Auth/AuthRegister', [
                'action' => route('register.store'),
                'loginUrl' => route('login'),
            ]);
        });

        Fortify::requestPasswordResetLinkView(fn () => Inertia::render('Auth/AuthForgotPassword', [
            'action' => route('password.email'),
            'loginUrl' => route('login'),
            'status' => session('status'),
        ]));

        Fortify::resetPasswordView(fn (Request $request) => Inertia::render('Auth/AuthResetPassword', [
            'action' => route('password.update'),
            'token' => $request->route('token'),
            'email' => $request->string('email')->value(),
        ]));

        Fortify::verifyEmailView(fn () => Inertia::render('Auth/AuthVerifyEmail', [
            'action' => route('verification.send'),
            'status' => session('status'),
        ]));

        Fortify::confirmPasswordView(fn () => Inertia::render('Auth/AuthConfirmPassword', [
            'action' => route('password.confirm.store'),
        ]));

        Fortify::twoFactorChallengeView(fn () => Inertia::render('Auth/AuthTwoFactorChallenge', [
            'action' => route('two-factor.login.store'),
        ]));

        return $this;
    }

    protected function registrationAllowed(): bool
    {
        return Features::enabled(Features::registration())
            && app(GeneralSettings::class)->allow_registration;
    }
}
