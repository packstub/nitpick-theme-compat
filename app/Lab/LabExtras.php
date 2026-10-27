<?php

namespace App\Lab;

use Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin;
use ArasyTheme\ArasyThemePlugin;
use Asignua\FilamentThemes\ThemesPlugin as AsignThemesPlugin;
use Awcodes\LightSwitch\LightSwitchPlugin;
use CuongPham\FilamentThemeCustomizer\ThemeCustomizerPlugin;
use Filament\Panel;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource;
use JeffersonGoncalves\FilamentPanelThemeIsolation\FilamentPanelThemeIsolationPlugin;
use Octopy\Filament\Palette\PaletteSwitcherPlugin;
use Silasrm\FilamentThemes\ThemesPlugin as SilasrmThemesPlugin;
use Yavuz\FilamentTheme\ThemePlugin as YavuzThemePlugin;

/**
 * The one extra the lab puts on the panel (`php artisan lab:use --extra=…`), set up the way its
 * README does: a colour switcher or branding tool next to Nitpick, or a theme straight on the
 * panel, to compare it with `--without-nitpick`.
 */
class LabExtras
{
    public static function apply(Panel $panel): Panel
    {
        return match (Lab::extra()) {
            'asign' => $panel->plugin(AsignThemesPlugin::make()),
            'palette' => $panel->plugin(PaletteSwitcherPlugin::make()),
            'whitelabel' => $panel->whiteLabel()->resources([WhiteLabelSettingsResource::class]),
            'uiswitcher' => $panel->plugin(FilamentUiSwitcherPlugin::make()),
            'lightswitch' => $panel->plugin(LightSwitchPlugin::make()),
            'isolation' => $panel->plugin(FilamentPanelThemeIsolationPlugin::make()),
            'silasrm' => $panel->plugin(SilasrmThemesPlugin::make()),
            'customizer' => $panel->plugin(ThemeCustomizerPlugin::make()),
            'theme:arasy' => $panel->plugin(ArasyThemePlugin::make())->viteTheme('resources/css/themes/arasy.css'),
            'theme:yavuz' => $panel->plugin(YavuzThemePlugin::make()),
            default => $panel,
        };
    }
}
