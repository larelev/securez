#!/bin/sh

TARGET=$1;
CWD=$(pwd);

docker compose up --build  -d --wait

exit 0;
