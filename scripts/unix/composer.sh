#!/bin/bash

TARGET=$1;
CWD=$(pwd);

set -a && source .env.local && docker compose -f docker-compose.yml exec php sh -c "
cd /var/www/html;
composer install;
"

exit 0;
