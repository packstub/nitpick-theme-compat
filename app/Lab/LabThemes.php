<?php

namespace App\Lab;

use Andreia\FilamentNordTheme\FilamentNordThemePlugin;
use ArasyTheme\ArasyThemePlugin;
use Benriadh1\FilamentBenriadhTheme\FilamentBenriadhThemePlugin;
use Caresome\FilamentNeobrutalism\NeobrutalismeTheme;
use Elemind\PressFilamentTheme\PressFilamentTheme;
use Filafly\Themes\Brisk\BriskTheme;
use Filament\Panel;
use Filament\Support\Colors\Color;
use HansenMakangiras\FilamentSleekTheme\FilamentSleekTheme;
use Irajul\FilamentShadcnTheme\FilamentShadcnThemePlugin;
use JeffersonGoncalves\FilamentEditorialTheme\EditorialThemePlugin;
use JohnRivera7\FilamentMia\MiaTheme;
use Kashyap\Vesper\VesperPlugin;
use Khoirulaksara\Awrel\AwrelPlugin;
use Khwr\FilamentQt5Theme\FilamentQt5ThemePlugin;
use Koassi\FilamentRail\ThemePlugin as RailThemePlugin;
use Laboiteacode\FilamentTributeTheme\FilamentTributeThemePlugin;
use LaBoiteACode\LaravelTributeTemplate\LaravelTributeTemplatePlugin;
use LaraZeus\Akin\AkinTheme;
use Nagi\FilamentAbyssTheme\FilamentAbyssThemePlugin;
use Nagi\FilamentDaisyUiThemes\FilamentDaisyUiThemesPlugin;
use NodonHq\BodegaLite\BodegaLite;
use Noreviq\FilamentTheme\NoreviqThemePlugin;
use notwonderful\FilamentMoonlightTheme\MoonlightTheme;
use Openplain\FilamentShadcnTheme\Color as ShadcnColor;
use Otatechie\Kinetics\KineticsPlugin;
use Packstub\Nitpick\Facades\Themes;
use Packstub\Nitpick\Theme;
use Qalainau\BonsaiTheme\BonsaiThemePlugin;
use RoBYCoNTe\FilamentItalia\FilamentItaliaTheme;
use Saivra\MeUi\MeUiPlugin;
use SpyApp\ThemeAberdeen\ThemeAberdeenPlugin;
use SpyApp\ThemeEdinburgh\ThemeEdinburghPlugin;
use SpyApp\ThemeInverness\ThemeInvernessPlugin;
use ThalysJuvenal\Aurum\AurumTheme;
use TomatoPHP\FilamentSimpleTheme\FilamentSimpleThemePlugin;
use Vlados\FilamentTailscaleTheme\FilamentTailscaleTheme;
use VTech9NL\PolderTheme\PolderThemePlugin;
use Yavuz\FilamentTheme\ThemePlugin as YavuzThemePlugin;

/**
 * Every theme the lab runs, registered in Nitpick the plainest way its own README allows.
 * A theme file in resources/css/themes/ is that theme's Tailwind entry (listed in vite.config.js):
 * Filament's theme first, then the theme's CSS, the way its install command would write the
 * panel's theme file, but one file per theme so each is linked only while it is active.
 */
class LabThemes
{
    public static function register(): void
    {
        Themes::register(
            Theme::make('abyss')->plugin(fn () => FilamentAbyssThemePlugin::make()),
            // Its install command imports aberdeen.css into the panel's theme file.
            Theme::make('aberdeen')->plugin(fn () => ThemeAberdeenPlugin::make())->stylesheet('vite:resources/css/themes/aberdeen.css'),
            Theme::make('akin')->plugin(fn () => AkinTheme::make())->stylesheet('vite:resources/css/themes/akin.css'),
            // Its install command imports arasy.css into the panel's theme file, which its layout needs as the panel's viteTheme.
            Theme::make('arasy')->plugin(fn () => ArasyThemePlugin::make())->panel(fn (Panel $panel) => $panel->viteTheme('resources/css/themes/arasy.css')),
            Theme::make('aurum')->plugin(fn () => AurumTheme::make()),
            // After `php artisan filament-benriadh-theme:install --migrate`, which publishes its stylesheet.
            Theme::make('benriadh')->plugin(fn () => FilamentBenriadhThemePlugin::make()),
            Theme::make('bodega')->plugin(fn () => BodegaLite::make()),
            Theme::make('bonsai')->plugin(fn () => BonsaiThemePlugin::make()),
            Theme::make('brisk')->plugin(fn () => BriskTheme::make())->stylesheet('vite:resources/css/themes/brisk.css'),
            Theme::make('compact-nehal')->label('Compact')->stylesheet('vite:vendor/nehalpatel/filament-compact-theme/resources/css/theme.css'),
            // One DaisyUI theme per Nitpick theme.
            Theme::make('daisy-dracula')->label('Dracula')->plugin(fn () => FilamentDaisyUiThemesPlugin::make()->themes(['dracula'])->defaultTheme('dracula')->themeSwitcher(false)),
            Theme::make('edinburgh')->plugin(fn () => ThemeEdinburghPlugin::make())->stylesheet('vite:resources/css/themes/edinburgh.css'),
            Theme::make('editorial')->plugin(fn () => EditorialThemePlugin::make())->stylesheet('vite:resources/css/themes/editorial.css'),
            Theme::make('inverness')->plugin(fn () => ThemeInvernessPlugin::make())->stylesheet('vite:resources/css/themes/inverness.css'),
            Theme::make('italia')->panel(fn (Panel $panel) => FilamentItaliaTheme::applyTo($panel)),
            // Its plugin asks for a viteTheme on the panel (it stops with a message without one), so the theme file goes
            // there; its own Appearance page cannot join a panel mid-request, so it is off.
            Theme::make('kinetics')->plugin(fn () => KineticsPlugin::make()->appearancePage(false))->colors(['primary' => Color::Teal, 'gray' => Color::Stone])
                ->panel(fn (Panel $panel) => $panel->viteTheme('resources/css/themes/kinetics.css')),
            Theme::make('meui')->label('MeUI')->plugin(fn () => MeUiPlugin::make()),
            Theme::make('mia')->label('Mía')->plugin(fn () => MiaTheme::make()),
            Theme::make('moonlight')->plugin(fn () => MoonlightTheme::make()),
            Theme::make('neobrutalism')->plugin(fn () => NeobrutalismeTheme::make()),
            Theme::make('nord')->plugin(fn () => FilamentNordThemePlugin::make())->stylesheet('vite:resources/css/themes/nord.css'),
            Theme::make('noreviq')->plugin(fn () => NoreviqThemePlugin::make()),
            Theme::make('polder')->plugin(fn () => PolderThemePlugin::make())->stylesheet('vite:resources/css/themes/polder.css'),
            Theme::make('press')->plugin(fn () => PressFilamentTheme::make()),
            Theme::make('qt5')->label('Qt5')->plugin(fn () => FilamentQt5ThemePlugin::make()),
            // Turns on database notifications: needs the notifications table.
            Theme::make('rail')->plugin(fn () => RailThemePlugin::make())->stylesheet('vite:resources/css/themes/rail.css'),
            // Its rules, kept as this theme's stylesheet; its service provider is not discovered (composer.json).
            Theme::make('shadcn')->label('Shadcn (Openplain)')->colors(['primary' => ShadcnColor::Default, 'gray' => Color::Zinc])->stylesheet(resource_path('css/themes/shadcn.css')),
            Theme::make('shadcn-irajul')->label('Shadcn (irajul)')->plugin(fn () => FilamentShadcnThemePlugin::make()),
            // Compiled from its theme.css after Filament's.
            Theme::make('shoplab')->stylesheet('vite:resources/css/themes/shoplab.css'),
            Theme::make('simple')->plugin(fn () => FilamentSimpleThemePlugin::make()),
            // Its theme.css compiled after Filament's, through the plugin's ->theme().
            Theme::make('sleek')->plugin(fn () => FilamentSleekTheme::make()->theme('resources/css/themes/sleek.css')),
            // Its service provider is not discovered (composer.json), so the theme links its stylesheet.
            Theme::make('tailscale')->plugin(fn () => FilamentTailscaleTheme::make())->stylesheet(base_path('vendor/vlados/filament-tailscale-theme/resources/dist/filament-tailscale-theme.css')),
            Theme::make('tribute-filament')->label('Filament Tribute')->plugin(fn () => FilamentTributeThemePlugin::make())->stylesheet('vite:resources/css/themes/tribute-filament.css'),
            Theme::make('tribute-laravel')->label('Laravel Tribute')->plugin(fn () => LaravelTributeTemplatePlugin::make())->stylesheet('vite:resources/css/themes/tribute-laravel.css'),
            Theme::make('yavuz')->plugin(fn () => YavuzThemePlugin::make()),
        );

        // Packages that change every panel from their service provider: only while the lab asks for them.
        if (Lab::isEnabled('awrel')) {
            Themes::register(Theme::make('awrel')->plugin(fn () => AwrelPlugin::make()));
        }

        if (Lab::isEnabled('vesper')) {
            Themes::register(Theme::make('vesper')->plugin(fn () => VesperPlugin::make()));
        }
    }
}
