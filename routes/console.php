<?php

use App\Lab\Lab;
use Illuminate\Support\Facades\Artisan;

// The lab's state (see App\Lab\Lab): what the panel runs with, kept in storage/app/lab.json.
Artisan::command('lab:use {--extra= : A switcher or branding tool next to Nitpick, or theme:<id> straight on the panel} {--without-nitpick : The panel without Nitpick} {--enable=* : A package that changes every panel (vesper, awrel, asign, uiswitcher, isolation)} {--failing-themes : Two themes that fail on purpose, one while it configures the panel, one while the page renders}', function () {
    $extra = $this->option('extra') ?: null;
    $enable = $this->option('enable');

    foreach ($enable as $package) {
        if (! array_key_exists($package, Lab::GLOBAL_PACKAGES)) {
            $this->error("Unknown package [{$package}]: one of ".implode(', ', array_keys(Lab::GLOBAL_PACKAGES)).'.');

            return 1;
        }
    }

    Lab::save(['extra' => $extra, 'nitpick' => ! $this->option('without-nitpick'), 'enable' => $enable, 'failing' => $this->option('failing-themes')]);
    Artisan::call('view:clear');
    $this->call('lab:status');
})->purpose('Set what the lab panel runs with');

Artisan::command('lab:reset', function () {
    Lab::reset();
    Artisan::call('view:clear');
    $this->info('The lab panel runs with Nitpick and every theme, nothing else.');
})->purpose('Put the lab panel back to its default');

Artisan::command('lab:status', function () {
    $this->table(['Nitpick', 'Extra', 'Global packages', 'Failing themes'], [[
        Lab::withNitpick() ? 'on' : 'off',
        Lab::extra() ?? '–',
        implode(', ', Lab::enabled()) ?: '–',
        Lab::withFailingThemes() ? 'on' : 'off',
    ]]);
})->purpose('Show what the lab panel runs with');
