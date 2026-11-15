<?php

declare(strict_types=1);

namespace Support\Inertia\Middleware;

use Domain\Shared\Enums\Language;
use Domain\Shared\Enums\Locale;
use Domain\Users\Models\User;
use Foundation\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Middleware;
use Laravel\Fortify\Features;
use Modules\Web\Users\Responses\UserDataProperty;
use Spatie\LaravelOptions\Options;

class HandleInertiaRequests extends Middleware
{
    /**
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /** @var User|null $user */
        $user = $request->user();

        return array_merge(parent::share($request), [
            'nonce' => fn (): string => app('csp-nonce'),
            'locale' => fn (): string => $request->getLocale(),
            'auth' => fn (): UserDataProperty => new UserDataProperty($user),
        ]);
    }

    /**
     * @see https://inertiajs.com/docs/v3/data-props/shared-data#sharing-once-props
     *
     * @return array<string, mixed>
     */
    public function shareOnce(Request $request): array
    {
        return array_merge(parent::shareOnce($request), [
            'app' => fn (): string => app(GeneralSettings::class)->site_name,
            'locales' => fn (): Options => Options::forEnum(Locale::class),
            'languages' => fn (): Options => Options::forEnum(Language::class),
            'features' => fn (): array => [
                'registration' => Features::enabled(Features::registration()) && app(GeneralSettings::class)->allow_registration,
                'resetPasswords' => Features::enabled(Features::resetPasswords()),
                'twoFactor' => Features::enabled(Features::twoFactorAuthentication()),
                'passkeys' => Features::enabled(Features::passkeys()),
            ],
            'echo' => fn (): array => [
                'key' => Config::string('reverb.apps.apps.0.options.wsKey', ''),
                'host' => Config::string('reverb.apps.apps.0.options.wsHost', 'localhost'),
                'port' => Config::integer('reverb.apps.apps.0.options.wsPort', 8080),
                'scheme' => Config::string('reverb.apps.apps.0.options.wsScheme', 'http'),
            ],
        ]);
    }
}
