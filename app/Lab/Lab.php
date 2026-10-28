<?php

namespace App\Lab;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\File;

/**
 * What the lab runs with, set by `php artisan lab:use` and kept in storage/app/lab.json, so a
 * run never edits a tracked file: one switcher or branding tool next to Nitpick (extra), the
 * panel without Nitpick (to tell a theme's own behaviour from Nitpick's), and the packages that
 * change every panel from their service provider (global), kept out of discovery until asked for,
 * and two themes that fail on purpose (failing), to see a user kept out of a broken theme.
 */
class Lab
{
    /**
     * Packages that act on every panel just by being installed: never discovered, their service
     * provider registered only while the lab asks for them.
     *
     * @var array<string, class-string>
     */
    public const GLOBAL_PACKAGES = [
        'vesper' => \Kashyap\Vesper\VesperServiceProvider::class,
        'awrel' => \Khoirulaksara\Awrel\AwrelThemeServiceProvider::class,
        'asign' => \Asignua\FilamentThemes\ThemesServiceProvider::class,
        'uiswitcher' => \Andreia\FilamentUiSwitcher\FilamentUiSwitcherServiceProvider::class,
        'isolation' => \JeffersonGoncalves\FilamentPanelThemeIsolation\FilamentPanelThemeIsolationServiceProvider::class,
    ];

    /**
     * Switchers and branding tools, each added to the panel next to Nitpick by LabExtras.
     *
     * @var list<string>
     */
    public const EXTRAS = ['asign', 'palette', 'whitelabel', 'uiswitcher', 'lightswitch', 'isolation', 'silasrm', 'customizer'];

    /** @var array{extra?: string|null, nitpick?: bool, enable?: list<string>, failing?: bool}|null */
    protected static ?array $state = null;

    public static function path(): string
    {
        return storage_path('app/lab.json');
    }

    /**
     * @return array{extra?: string|null, nitpick?: bool, enable?: list<string>, failing?: bool}
     */
    public static function state(): array
    {
        return static::$state ??= File::exists(static::path())
            ? (json_decode(File::get(static::path()), true) ?: [])
            : [];
    }

    /**
     * @param  array{extra?: string|null, nitpick?: bool, enable?: list<string>, failing?: bool}  $state
     */
    public static function save(array $state): void
    {
        File::ensureDirectoryExists(dirname(static::path()));
        File::put(static::path(), json_encode($state, JSON_PRETTY_PRINT));
        static::$state = $state;
    }

    public static function reset(): void
    {
        File::delete(static::path());
        static::$state = null;
    }

    public static function extra(): ?string
    {
        return static::state()['extra'] ?? null;
    }

    public static function withNitpick(): bool
    {
        return static::state()['nitpick'] ?? true;
    }

    public static function withFailingThemes(): bool
    {
        return static::state()['failing'] ?? false;
    }

    /**
     * Global packages asked for, plus the one an extra needs (its plugin lives in that package).
     *
     * @return list<string>
     */
    public static function enabled(): array
    {
        $enabled = static::state()['enable'] ?? [];

        if (array_key_exists((string) static::extra(), static::GLOBAL_PACKAGES)) {
            $enabled[] = static::extra();
        }

        return array_values(array_unique($enabled));
    }

    public static function isEnabled(string $package): bool
    {
        return in_array($package, static::enabled(), true);
    }

    public static function registerProviders(Application $app): void
    {
        // Filament's publishing commands publish every package's files, whether the lab uses it now or not.
        $publishing = $app->runningInConsole() && in_array($_SERVER['argv'][1] ?? null, ['filament:assets', 'filament:upgrade'], true);

        foreach ($publishing ? array_keys(static::GLOBAL_PACKAGES) : static::enabled() as $package) {
            if (isset(static::GLOBAL_PACKAGES[$package])) {
                $app->register(static::GLOBAL_PACKAGES[$package]);
            }
        }
    }
}
