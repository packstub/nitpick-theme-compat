# packstub/nitpick-theme-compat

A Laravel 13 app with one Filament 5 panel, every free Filament 5 theme we could find installed, and
Nitpick (`packstub/filament-nitpick`) offering them all in its picker. It answers "does theme X work in
Nitpick, and how do you set it up?", and re-runs that check before each Nitpick release.

- `app/Lab/LabThemes.php`: every theme's registration, the plainest way its README allows. The comment
  above a theme says why it needs more than `->plugin()`.
- `resources/css/themes/<id>.css`: a theme's Tailwind entry when it needs one (Filament's theme first,
  then the theme's CSS), listed in `vite.config.js`.
- `app/Lab/Lab.php` and `php artisan lab:use|lab:reset|lab:status`: what the panel runs with (an extra
  next to Nitpick, the panel without Nitpick, packages that change every panel). State lives in
  `storage/app/lab.json`; never toggle the lab by editing tracked files.
- `scripts/`: `check-themes.mjs` (the pick cycle), `run-extras.sh`, the `probe-*.mjs` diagnostics,
  `summary.mjs`, `contact-sheet.py`, `use-local-nitpick.sh`.
- Packages that act on every panel from their service provider stay in `extra.laravel.dont-discover`
  (`composer.json`) and are registered by `Lab` only when asked for.

Findings and the reasoning behind each setup live in the Packstub workspace notes
(`workspace/notes/filament-nitpick/theme-compat.md`); this repo's README stays a friendly how-to.
Write about other people's themes the way the README does: what each one needs, never what it lacks.
