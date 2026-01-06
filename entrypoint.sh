#!/bin/bash
set -e

# --- BAGIAN 1: PERSIAPAN FOLDER & ENV ---
cd /var/www/html
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

export APP_URL="https://fitmeall.azurewebsites.net"
export ASSET_URL="https://fitmeall.azurewebsites.net"
export APP_ENV=production
export SCHEME=https

# --- BAGIAN 2: OPTIMASI CACHE ---
echo "🧹 Clearing and rebuilding cache..."
php artisan optimize:clear || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- BAGIAN 3: DATABASE RESET & SEEDING ---
echo "⌛ Resetting Database structure..."

# PERHATIAN: migrate:fresh akan menghapus semua tabel dan membuat ulang.
# Ini penting agar kolom 'category' dan 'instructions' muncul di database Azure.
php artisan migrate:fresh --seed --force || echo "⚠️ Database reset failed"

# Jalankan kembali seeder khusus untuk memastikan data menu/admin masuk
echo "🌱 Running Specific Seeder (MegaPlanSeeder)..."
php artisan db:seed --class=MegaPlanSeeder --force || echo "⚠️ MegaPlanSeeder failed"

# --- BAGIAN 4: PERMISSION & START ---
php artisan storage:link || true
echo "🔒 Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Starting Apache..."
service ssh start || true
exec apache2-foreground
