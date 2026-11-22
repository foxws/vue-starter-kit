<?php

declare(strict_types=1);

use Domain\Users\Commands\CreateUserCommand;
use Domain\Users\Models\User;
use Foundation\Settings\GeneralSettings;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

it('creates a user from the command line, whether or not registration is open', function (bool $allowed) {
    $settings = app(GeneralSettings::class);
    $settings->allow_registration = $allowed;
    $settings->save();

    $stream = fopen('php://memory', 'r+');
    fwrite($stream, 'Sup3r-Secret-Passw0rd!');
    rewind($stream);

    $input = new ArrayInput(['--name' => 'Jane', '--email' => 'jane@example.com', '--password-stdin' => true]);
    $input->setStream($stream);
    $input->setInteractive(false);

    $command = app(CreateUserCommand::class);
    $command->setLaravel(app());

    expect($command->run($input, new BufferedOutput))->toBe(0)
        ->and(User::query()->where('email', 'jane@example.com')->sole()->hasVerifiedEmail())->toBeTrue();
})->with([
    'open' => [true],
    'closed' => [false],
]);
