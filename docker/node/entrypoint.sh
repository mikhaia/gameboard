#!/bin/sh
set -eu

cd /var/www/html

ESBUILD_PACKAGE="$(node -p "const platform = process.platform; const arch = process.arch; const map = { 'linux-arm64': '@esbuild/linux-arm64', 'linux-x64': '@esbuild/linux-x64', 'darwin-arm64': '@esbuild/darwin-arm64', 'darwin-x64': '@esbuild/darwin-x64' }; map[platform + '-' + arch] || ''")"

if [ -n "$ESBUILD_PACKAGE" ] && [ ! -d "node_modules/$ESBUILD_PACKAGE" ]; then
  echo "Reinstalling node_modules for $ESBUILD_PACKAGE"
  mkdir -p node_modules
  find node_modules -mindepth 1 -maxdepth 1 -exec rm -rf {} +
fi

npm install --no-fund --no-audit

exec npm run dev -- --host 0.0.0.0 --port "${VITE_PORT:-5173}"
