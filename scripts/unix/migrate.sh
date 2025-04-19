#!/usr/bin/env bash

set -a && source .env.local && docker compose -f compose.yaml exec php sh -c "
 cd /var/www/html;
 php bin/console make:migration;
 php bin/console doctrine:migrations:migrate;
 php bin/console doctrine:fixtures:load;
";

# php bin/console make:migration;
# php bin/console doctrine:migrations:migrate;
# php bin/console doctrine:fixtures:load;