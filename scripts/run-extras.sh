#!/bin/bash
# Each switcher or branding tool on the panel next to Nitpick, then the pick cycle for three kinds
# of theme: Midnight (bundled), Bodega (a plugin with a palette), Abyss (sets the panel's viteTheme).
# Usage: scripts/run-extras.sh [<extra> ...]   (default: all of them; results in storage/lab/extras)
set -e
cd "$(dirname "$0")/.."
EXTRAS=("$@")
[ ${#EXTRAS[@]} -eq 0 ] && EXTRAS=(asign palette whitelabel uiswitcher lightswitch isolation silasrm customizer)
trap 'php artisan lab:reset > /dev/null' EXIT
for x in "${EXTRAS[@]}"; do
  php artisan lab:use --extra="$x" > /dev/null
  mkdir -p "storage/lab/extras/$x"
  LAB_OUT="storage/lab/extras/$x" node scripts/check-themes.mjs midnight bodega abyss > "storage/lab/extras/$x/log.txt" 2>&1
  echo "$x: $(node scripts/summary.mjs "storage/lab/extras/$x/log.txt" | tr '\n' ' ')"
done
