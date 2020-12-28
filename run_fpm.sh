#!/usr/bin/env bash

# this script runs as an ENTRYPOINT in docker (production, staging)

# this might help when switching environments
# rm bootstrap/cache/*.php

chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

php-fpm