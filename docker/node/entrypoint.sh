#!/bin/sh
set -eu

cd /var/www/html

npm install --no-fund --no-audit

exec npm run dev -- --host 0.0.0.0 --port "${VITE_PORT:-5173}"
