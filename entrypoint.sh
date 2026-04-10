#!/bin/sh
set -e

echo "🚀 Starting Laravel initialization..."

# Tunggu DB siap
# echo "⏳ Waiting for database..."
# until php artisan migrate:status >/dev/null 2>&1; do
#   sleep 3
# done

# Generate key kalau belum ada
# if [ -z "$APP_KEY" ]; then
#   php artisan key:generate
# fi

# if [ "$TYPE" = "QUEUE" ] || [ "$TYPE" = "SCHEDULER" ]; then
if [ "$TYPE" = "WEB" ]; then
	echo "⏩ Container TYPE is '${TYPE}'. Running migrations & seeds..."
	# Migration & seed
	# php artisan migrate:fresh --force
	# php artisan db:seed --force

	echo "✅ Database setup complete!"
else
	echo "📦 Container TYPE is '${TYPE}'. Skipping migrations & seeds."
fi

# Jalankan command utama (artisan serve)
# Penting: exec "$@" meneruskan command queue:work atau artisan serve
echo "▶️  Executing command: $@"
exec "$@"
