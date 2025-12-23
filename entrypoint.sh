#!/bin/sh
set -e

echo "🚀 Starting Laravel initialization..."

# Tunggu DB siap
# echo "⏳ Waiting for database..."
# until php artisan migrate:status >/dev/null 2>&1; do
#   sleep 3
# done

# Generate key kalau belum ada
if [ -z "$APP_KEY" ]; then
  php artisan key:generate
fi

# Migration & seed
# php artisan migrate --force
# php artisan db:seed --force

# Passport (aman dijalankan berulang)
# php artisan passport:install --force

# echo "✅ Laravel ready!"

# Jalankan command utama (artisan serve)
exec "$@"
