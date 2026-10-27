<?php

use Filament\Support\Colors\Color;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Palette Theme
    |--------------------------------------------------------------------------
    |
    | This option controls the default color palette theme that will be used
    | if a user has not selected a specific theme.
    |
    */
    'default' => env('FILAMENT_PALETTE_DEFAULT', 'slate'),

    /*
    |--------------------------------------------------------------------------
    | Palette Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the cache store that will be used by the palette
    | switcher to persist the selected theme. If set to null, the framework's
    | default cache store will be used. Note that ephemeral drivers like
    | 'array' or 'null' will not persist the theme across requests.
    |
    */
    'cache_store' => env('FILAMENT_PALETTE_CACHE_STORE', null),

    /*
    |--------------------------------------------------------------------------
    | Available Color Palettes
    |--------------------------------------------------------------------------
    |
    | This option defines the various color palettes that will be available
    | for users to choose from in the palette switcher. You may customize
    | the colors, add new palettes, or remove existing ones as needed.
    |
    */
    'palette' => [
        'slate'   => [
            'primary' => Color::Slate,
            'warning' => Color::Amber,
            'danger'  => Color::Red,
            'success' => Color::Emerald,
            'info'    => Color::Sky,
        ],
        'stone'   => [
            'primary' => Color::Stone,
            'warning' => Color::Yellow,
            'danger'  => Color::Red,
            'success' => Color::Lime,
            'info'    => Color::Blue,
        ],
        'red'     => [
            'primary' => Color::Red,
            'warning' => Color::Amber,
            'danger'  => Color::Rose,
            'success' => Color::Green,
            'info'    => Color::Cyan,
        ],
        'amber'   => [
            'primary' => Color::Amber,
            'warning' => Color::Yellow,
            'danger'  => Color::Red,
            'success' => Color::Lime,
            'info'    => Color::Blue,
        ],
        'emerald' => [
            'primary' => Color::Emerald,
            'warning' => Color::Yellow,
            'danger'  => Color::Red,
            'success' => Color::Emerald,
            'info'    => Color::Sky,
        ],
        'teal'    => [
            'primary' => Color::Teal,
            'warning' => Color::Amber,
            'danger'  => Color::Red,
            'success' => Color::Emerald,
            'info'    => Color::Blue,
        ],
        'sky'     => [
            'primary' => Color::Sky,
            'warning' => Color::Yellow,
            'danger'  => Color::Red,
            'success' => Color::Green,
            'info'    => Color::Cyan,
        ],
        'violet'  => [
            'primary' => Color::Violet,
            'warning' => Color::Amber,
            'danger'  => Color::Red,
            'success' => Color::Lime,
            'info'    => Color::Sky,
        ],
        'fuchsia' => [
            'primary' => Color::Fuchsia,
            'warning' => Color::Yellow,
            'danger'  => Color::Red,
            'success' => Color::Lime,
            'info'    => Color::Sky,
        ],
    ],
];
