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

# --- BAGIAN 3: DATABASE MIGRATION (MODE AMAN) ---
echo "⌛ Running Migration..."

# Gunakan migrate --force (TANPA FRESH) agar data yang sudah ada TIDAK TERHAPUS.
# Ini hanya akan memperbarui struktur jika ada file migrasi baru di masa depan.
php artisan migrate --force || echo "⚠️ Migration skipped/failed"

# Catatan: Kita matikan seeder otomatis di sini agar data tidak ganda.
# Data sudah aman di database Azure sekarang.

# --- BAGIAN 4: PERMISSION & START ---
php artisan storage:link || true
echo "🔒 Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Starting Apache..."
service ssh start || true
exec apache2-foreground
