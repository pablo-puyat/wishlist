#!/bin/sh
set -e

if [ ! -d "vendor" ] || [ -z "$(ls -A vendor 2>/dev/null)" ] || [ "composer.json" -nt "vendor" ]; then
    echo "Running composer install..."
    composer install

else
    echo "composer packages up to date, skipping composer install"
fi

exec "$@"
