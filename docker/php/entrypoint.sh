#!/bin/sh
composer install --no-interaction --no-progress --optimize-autoloader
mkdir -p var/cache var/log
chmod -R 777 var
exec "$@"
