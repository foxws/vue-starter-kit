<?php

declare(strict_types=1);

namespace Foundation\Settings;

use Domain\Shared\Enums\Locale;
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name = 'Laravel';

    public string $timezone = 'UTC';

    public Locale $default_locale = Locale::EnUs;

    public bool $allow_registration = false;

    public static function group(): string
    {
        return 'general';
    }
}
