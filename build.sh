#!/usr/bin/env bash

echo "Building API"

npm install

composer install

composer dump-autoload --optimize

php artisan migrate

node_modules/.bin/apidoc -i app/ -o public/apidoc/