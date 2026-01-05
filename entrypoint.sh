#!/bin/bash
set -e

# --- BAGIAN 1: PERSIAPAN FOLDER & ENV ---
cd /var/www/html

mkdir -p storage/app/public \
         storage/framework/cache \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

export APP_URL="https://fitmeall.azurewebsites.net"
export ASSET_URL="https://fitmeall.azurewebsites.net"
export APP_ENV=production
export SCHEME=https

# --- BAGIAN 2: PEMBERSIHAN CACHE ---
echo "🧹 Clearing cache..."
php artisan optimize:clear || true
rm -f bootstrap/cache/*.php || true

# --- BAGIAN 3: BUILD CACHE BARU ---
echo "🚀 Building new cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- BAGIAN 4: SETUP STORAGE ---
php artisan storage:link || true

# --- BAGIAN 5: DATABASE MIGRATION (TAMBAHAN FIX) ---
# Kita tambahkan cek koneksi agar jika database belum siap, container tidak langsung exit
echo "Waiting for database connection..."
# Mencoba migrasi dengan --force karena ini di lingkungan production (Azure)
# Menggunakan || true agar jika gagal (misal DB belum dibuat), Apache tetap berusaha jalan
php artisan migrate --force || echo "⚠️ Migration failed, check if database exists and credentials are correct."

# --- BAGIAN 6: PERMISSION FIX ---
echo "🔒 Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- BAGIAN 7: START SERVER ---
echo "✅ Starting services..."
service ssh start || true

echo "Starting Apache in foreground..."
exec apache2-foreground
