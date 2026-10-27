import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js',
                // Nitpick lab: theme stylesheets built by Vite, each linked only while its theme is active.
                'resources/css/themes/aberdeen.css', 'resources/css/themes/inverness.css', 'resources/css/themes/brisk.css', 'resources/css/themes/sleek.css',
                'vendor/osamanagi/filament-abyss-theme/resources/css/theme.css', 'resources/css/themes/nord.css', 'resources/css/themes/kinetics.css',
                'resources/css/themes/editorial.css', 'resources/css/themes/edinburgh.css',
                'resources/css/themes/akin.css', 'resources/css/themes/shoplab.css',
                'resources/css/themes/rail.css', 'resources/css/themes/polder.css', 'resources/css/themes/tribute-laravel.css', 'resources/css/themes/tribute-filament.css',
                'vendor/nehalpatel/filament-compact-theme/resources/css/theme.css', 'resources/css/filament-italia/theme.css', 'resources/css/themes/arasy.css'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
