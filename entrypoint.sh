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

# Gunakan variabel dari Azure Configuration jika tersedia, jika tidak gunakan default ini
export APP_URL="${APP_URL:-https://fitmeall.azurewebsites.net}"
export ASSET_URL="${ASSET_URL:-https://fitmeall.azurewebsites.net}"
export APP_ENV=production
export SCHEME=https

# --- BAGIAN 2: PEMBERSIHAN & OPTIMASI ---
echo "🧹 Clearing and Building cache..."
php artisan optimize:clear || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- BAGIAN 3: SETUP STORAGE ---
php artisan storage:link || true

# --- BAGIAN 4: DATABASE MIGRATION & SEEDER (SOLUSI MENU KOSONG) ---
echo "⌛ Waiting for database connection..."
# Menjalankan migrasi
php artisan migrate --force || echo "⚠️ Migration failed"

# Menjalankan Seeder otomatis agar Menu & Program Latihan muncul
# Perintah ini hanya akan mengisi data jika tabel masih kosong (tergantung logika Seeder Anda)
echo "🌱 Seeding data for Menu and Exercise Programs..."
php artisan db:seed --force || echo "⚠️ Seeding failed or already seeded"

# --- BAGIAN 5: PERMISSION FIX ---
echo "🔒 Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- BAGIAN 6: START SERVICES ---
echo "✅ Starting services..."
service ssh start || true

echo "Starting Apache..."
exec apache2-foreground
