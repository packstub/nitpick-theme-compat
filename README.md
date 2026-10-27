# Nitpick Theme Compat

The Filament community has made a lot of lovely themes. [Nitpick](https://github.com/packstub/filament-nitpick) lets each user of a Filament panel pick one for themselves, and this repo is where we check that they all feel at home there: a Laravel app with one panel, 37 free Filament 5 themes installed, and every one of them in Nitpick's picker.

Use it to see how a theme is set up with Nitpick, to try themes side by side before you choose, or to check a theme of your own.

## What's inside

- **Every theme's setup**, in [`app/Lab/LabThemes.php`](app/Lab/LabThemes.php): registered with `Theme::make()` the way its own README describes. Most need nothing more than `->plugin()`; a short comment explains the ones that need a step more.
- **A theme file per Vite-built theme**, in [`resources/css/themes/`](resources/css/themes): Filament's theme, then the theme's CSS, so each is linked only while it is the active theme.
- **A script that picks every theme** the way a user does (user menu → Appearance → the theme's card), screenshots the panel in light and dark mode, picks Aurora again, and checks that the page is back to exactly how it started.
- **Colour switchers and branding tools** you can put on the panel next to Nitpick, one at a time.

## Getting started

Requirements: PHP 8.4, Composer, Node 20+, SQLite, and Google Chrome for the scripts. The scripts expect the app at `https://nitpick-theme-compat.test` ([Laravel Herd](https://herd.laravel.com): `herd link nitpick-theme-compat && herd secure`); set `LAB_URL` for another address and `CHROME_PATH` for Chrome elsewhere than macOS's default.

```bash
git clone https://github.com/packstub/nitpick-theme-compat.git
cd nitpick-theme-compat
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite && php artisan migrate --seed
php artisan filament-benriadh-theme:install --migrate --no-interaction
php artisan filament:assets
npm install && npm run build
```

Open `/admin` and sign in with the **Jane Tester** button under the form (or `jane@lab.test` / `password`). Pick a theme from the user menu → **Appearance**.

## Running the checks

```bash
npm run check -- bodega nord press        # pick each theme and check the panel
node scripts/summary.mjs <saved output>    # one line per theme
scripts/run-extras.sh                      # each switcher or tool next to Nitpick
```

Screenshots land in `storage/lab/results/<theme>/`: the picker, dashboard, list, edit page, the list in dark mode, and the dashboard back on Aurora. `python3 scripts/contact-sheet.py sheet.png 2-dashboard abyss nord press` puts many of them on one image (needs Pillow).

The panel's setup comes from `php artisan lab:use`, so a run never edits a tracked file:

```bash
php artisan lab:use --extra=palette                         # a switcher or branding tool next to Nitpick
php artisan lab:use --extra=theme:arasy --without-nitpick   # a theme straight on the panel, to compare
php artisan lab:use --enable=vesper                         # a package that styles every panel (see below)
php artisan lab:status
php artisan lab:reset
```

To try an unreleased Nitpick, `scripts/use-local-nitpick.sh ../path/to/filament-nitpick` links your checkout in place of the release (`composer install` puts the release back).

## The themes

Tested versions are in `composer.lock`. "Theme file" means an entry in `resources/css/themes/` passed as `->stylesheet('vite:…')`.

| Theme | Package | Setup with Nitpick |
| --- | --- | --- |
| Abyss | `osamanagi/filament-abyss-theme` | `->plugin()`, its CSS in `vite.config.js` |
| Aberdeen | `spykapps/theme-aberdeen` | `->plugin()` + theme file |
| Akin | `lara-zeus/akin` | `->plugin()` + theme file |
| Arasy | `rafyakbar/arasy-theme` | `->plugin()` + theme file as the panel's `viteTheme`, through `->panel()` |
| Aurum | `thalysjuvenal/aurum-filament-theme` | `->plugin()` |
| Awrel | `khoirulaksara/awrel` | `->plugin()`; its stylesheet loads on every panel, so `lab:use --enable=awrel` |
| BenRiadh | `benriadh1/filament-benriadh-theme` | `->plugin()`, after its install command |
| Bodega Lite | `nodonhq/filament-bodega-lite` | `->plugin()` |
| Bonsai | `qalainau/bonsai-theme` | `->plugin()` |
| Brisk | `filafly/brisk` | `->plugin()` + theme file |
| Compact | `nehalpatel/filament-compact-theme` | its `theme.css` as a `vite:` stylesheet |
| DaisyUI (Dracula) | `osamanagi/filament-daisy-ui-themes` | `->plugin()` with one DaisyUI theme per Nitpick theme |
| Edinburgh | `spykapps/theme-edinburgh` | `->plugin()` + theme file |
| Editorial | `jeffersongoncalves/filament-editorial-theme` | `->plugin()` + theme file |
| Filament Tribute | `laboiteacode/filament-tribute-theme` | `->plugin()` + theme file |
| Inverness | `spykapps/theme-inverness` | `->plugin()` + theme file |
| Italia | `robyconte/filament-italia` | `FilamentItaliaTheme::applyTo()` through `->panel()` |
| Kinetics | `otatechie/filament-kinetics` | `->plugin()` + theme file as the panel's `viteTheme`, through `->panel()` |
| Laravel Tribute | `laboiteacode/filament-laravel-tribute-template` | `->plugin()` + theme file |
| MeUI | `saivra/meui-theme` | `->plugin()`, its migration |
| Mía | `johnrivera7/filament-mia-theme` | `->plugin()` |
| Moonlight | `notwonderful/filament-moonlight-theme` | `->plugin()` |
| Neobrutalism | `caresome/filament-neobrutalism-theme` | `->plugin()` |
| Nord | `andreia/filament-nord-theme` | `->plugin()` + theme file |
| Noreviq | `noreviq/filament-theme` | `->plugin()` |
| Polder | `vtech9nl/filament-polder-theme` | `->plugin()` + theme file |
| Press | `elemind/press-filament-theme` | `->plugin()` |
| Qt5 | `khwr/filament-qt5-theme` | `->plugin()` |
| Rail | `koassi/filament-rail` | `->plugin()` + theme file; it shows database notifications, so the app has a `notifications` table |
| Shadcn | `openplain/filament-shadcn-theme` | its palette and CSS, with the package in `dont-discover` |
| Shadcn | `irajul/filament-shadcn-theme` | `->plugin()` |
| Shoplab | `shoplab/shoplab-filament-theme` | theme file (its `theme.css` after Filament's) |
| Simple | `tomatophp/filament-simple-theme` | `->plugin()` |
| Sleek | `hansenmakangiras/filament-sleek-theme` | `->plugin()` with `->theme()` pointing at a theme file |
| Tailscale | `vlados/filament-tailscale-theme` | `->plugin()` + its CSS as a stylesheet, with the package in `dont-discover` |
| Vesper | `suraj-kashyap-dev/vesper` | `->plugin()`; it lays out every panel, so `lab:use --enable=vesper` |
| Yavuz | `yavuz-dev/filament-theme` | `->plugin()`; with its own user menu, `->picker(modal: false)` opens the Appearance page |

A few packages style every panel from their service provider, so they stay in `extra.laravel.dont-discover` and the lab registers them only on request. In an app of your own the same pattern works: keep the provider out of discovery and give Nitpick the stylesheet.

### Next to Nitpick

Colour switchers and branding tools, each on the panel with `lab:use --extra=…`: Themes (`asignua/filament-themes`), Palette Switcher (`octopyid/filament-palette`), White Label (`ashrafic/filament-white-label`), UI Switcher (`andreia/filament-ui-switcher`), Light Switch (`awcodes/light-switch`), Panel Theme Isolation (`jeffersongoncalves/filament-panel-theme-isolation`), Themes (`silasrm/filament-themes`) and Theme Customizer (`cuongpham2107/filament-theme-customizer`). Nitpick picks work alongside every one of them; when two tools set the panel's colours, the one the user touched last wins, so most apps pick one.

## Adding a theme

1. `composer require` it and follow its install steps.
2. Register it in `app/Lab/LabThemes.php` (and a theme file in `resources/css/themes/` plus `vite.config.js` if it builds with Vite).
3. `npm run build && php artisan filament:assets`, then `npm run check -- <id>`.

Building a theme yourself? [For theme authors](https://packstub.dev/docs/filament-nitpick/theme-authors) has the habits that make it a great fit in Nitpick, and a pull request adding it here is very welcome.

## Thanks

To the authors of every theme and tool in this repo: thank you for sharing your work with the Filament community.

## License

The lab's own code is MIT. Each theme keeps its own license; `resources/css/themes/shadcn.css` holds rules from `openplain/filament-shadcn-theme` (MIT), with its notice.
