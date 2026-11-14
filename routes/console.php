<?php

declare(strict_types=1);

use Illuminate\Auth\Console\ClearResetsCommand;
use Illuminate\Cache\Console\PruneStaleTagsCommand;
use Illuminate\Database\Console\PruneCommand;
use Illuminate\Support\Facades\Schedule;
use Laravel\Horizon\Console\SnapshotCommand;
use Laravel\Sanctum\Console\Commands\PruneExpired;
use Laravel\Telescope\Console\PruneCommand as PruneTelescopeCommand;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Models\HealthCheckResultHistoryItem;

Schedule::command(SnapshotCommand::class)
    ->environments('production')
    ->everyFiveMinutes();

Schedule::command(RunHealthChecksCommand::class)
    ->everyFiveMinutes();

Schedule::command(PruneStaleTagsCommand::class)
    ->dailyAt('01:00');

Schedule::command(ClearResetsCommand::class)
    ->dailyAt('01:00');

Schedule::command(PruneExpired::class, ['--hours=24'])
    ->withoutOverlapping(10)
    ->dailyAt('01:00');

Schedule::command(PruneCommand::class, [
    '--model' => [
        HealthCheckResultHistoryItem::class,
    ]])
    ->withoutOverlapping(10)
    ->dailyAt('01:00');

if (class_exists(PruneTelescopeCommand::class)) {
    Schedule::command(PruneTelescopeCommand::class, ['--hours=48'])
        ->withoutOverlapping(10)
        ->dailyAt('01:00');
}
