#!/bin/bash
# Runs the lab against a local checkout of Nitpick instead of the release from Packagist, without
# touching composer.json or composer.lock: vendor/packstub/filament-nitpick becomes a symlink.
# `composer install` puts the release back.
# Usage: scripts/use-local-nitpick.sh [<path to filament-nitpick>]   (default: ../../plugins/filament-nitpick)
set -e
cd "$(dirname "$0")/.."
SRC="$(cd "${1:-../../plugins/filament-nitpick}" && pwd)"
rm -rf vendor/packstub/filament-nitpick
ln -s "$SRC" vendor/packstub/filament-nitpick
composer dump-autoload -q
php artisan filament:assets -q
echo "Nitpick from $SRC"
