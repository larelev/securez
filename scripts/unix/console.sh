#!/usr/bin/env bash

cmd="cd /var/www/html && php bin/console $@"

echo "Executing command: 
$cmd
"

set -a \
    && source .env.local \
    && docker compose -f compose.yaml exec php sh -c "$cmd";