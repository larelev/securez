#!/usr/bin/env bash

symfony console make:migration
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
