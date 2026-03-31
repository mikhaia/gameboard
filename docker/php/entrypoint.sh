#!/bin/sh
set -eu

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

mkdir -p \
    database \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

touch database/database.sqlite

composer install --no-interaction --prefer-dist --no-progress

if ! grep -Eq '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

if [ ! -e public/storage ] && [ ! -L public/storage ]; then
    php artisan storage:link
fi

php artisan migrate --force

exec php artisan serve --host=0.0.0.0 --port=8000
