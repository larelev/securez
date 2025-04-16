#!/bin/bash

TARGET=$1;
CWD=$(pwd);

set -a && source .env.local && docker compose -f docker-compose.yml up --build  -d --wait

exit 0;
