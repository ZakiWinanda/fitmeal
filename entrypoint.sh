#!/bin/bash
set -e

# --- BAGIAN 1: PERSIAPAN FOLDER ---
cd /var/www/html
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

# --- BAGIAN 2: ENV & CACHE ---
export APP_URL="https://fitmeall.azurewebsites.net"
export ASSET_URL="https://fitmeall.azurewebsites.net"
export APP_ENV=production
export SCHEME=https

echo "🧹 Clearing old cache..."
php artisan optimize:clear || true
rm -f bootstrap/cache/*.php || true

echo "🚀 Building fresh cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- BAGIAN 3: DATABASE (SOLUSI MENU KOSONG) ---
echo "⌛ Checking database connection..."

# 1. Jalankan Migrasi (Pastikan tabel ada)
php artisan migrate --force || echo "⚠️ Migration skipped"

# 2. Jalankan Seeder (SOLUSI UTAMA)
# Kita tambahkan perintah 'db:seed' secara eksplisit. 
# Jika Anda punya seeder khusus menu, pastikan namanya benar, misal: --class=MenuSeeder
echo "🌱 Running Database Seeder..."
php artisan db:seed --force || echo "⚠️ Seeding failed - mungkin data sudah ada atau ada error di file Seeder"

# --- BAGIAN 4: PERMISSION & SYMLINK ---
php artisan storage:link || true

echo "🔒 Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- BAGIAN 5: START SERVER ---
echo "✅ Starting services..."
service ssh start || true

echo "Starting Apache..."
exec apache2-foreground
