<?php

declare(strict_types=1);

use Inertia\Inertia;

if (! function_exists('toast')) {
    /**
     * Flash a toast to the next Inertia response, in the shape resources/js/composables/flash.ts reads.
     */
    function toast(string $title, string $description, string $type = 'success'): void
    {
        Inertia::flash([
            'title' => $title,
            'description' => $description,
            'type' => $type,
        ]);
    }
}
