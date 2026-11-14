<?php

declare(strict_types=1);

namespace Modules\Web\Home\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class HomeController
{
    public function __invoke(): Response
    {
        return Inertia::render('HomePage');
    }
}
