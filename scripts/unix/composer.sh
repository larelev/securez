#!/bin/bash

TARGET=$1;
CWD=$(pwd);

set -a && source .env.local && docker compose -f compose.yaml exec php sh -c "
cd /var/www/html;
composer install;
"

exit 0;
