#!/bin/bash

TARGET=$1;
CWD=$(pwd);

cmd="cd /var/www/html && composer $@"

echo "Executing command:
$cmd
"
set -a \
    && source .env.local \
    && docker compose -f compose.yaml exec php sh -c "$cmd";

exit 0;
