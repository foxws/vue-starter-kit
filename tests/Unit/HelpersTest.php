<?php

declare(strict_types=1);

it('renders markdown without raw html', function () {
    expect(markdown('**bold** <script>alert(1)</script>'))
        ->toContain('<strong>bold</strong>')
        ->not->toContain('<script>');
});

it('renders an empty string for blank markdown', function () {
    expect(markdown(null))->toBe('');
});
