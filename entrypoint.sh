#!/bin/bash
set -e

# --- BAGIAN 1: PERSIAPAN FOLDER & ENV ---
# Masuk ke direktori kerja (Wajib)
cd /var/www/html

# Membuat struktur folder log dan cache yang dibutuhkan Laravel
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Export Variabel Lingkungan (Penting untuk URL HTTPS Azure)
export APP_URL="https://fitmeall.azurewebsites.net"
export ASSET_URL="https://fitmeall.azurewebsites.net"
export APP_ENV=production
export SCHEME=https

# --- BAGIAN 2: PEMBERSIHAN CACHE ---
echo "🧹 Clearing cache..."

# Hapus cache lama (gunakan || true agar container tidak mati jika gagal di sini)
php artisan optimize:clear || true
rm -f bootstrap/cache/*.php || true

# --- BAGIAN 3: BUILD CACHE BARU ---
echo "🚀 Building new cache..."
# Perintah ini akan menggunakan APP_URL yang sudah di-export di atas
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- BAGIAN 4: SETUP STORAGE ---
# Gunakan || true untuk mengabaikan error jika link sudah ada
php artisan storage:link || true

# --- BAGIAN 5: PERMISSION FIX (SOLUSI MASALAH LOG) ---
echo "🔒 Fixing permissions..."
# Ini adalah langkah paling krusial untuk mengatasi "Permission denied"
# Kita jalankan tepat sebelum server start agar tidak tertimpa proses lain.
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- BAGIAN 6: START SERVER ---
echo "✅ Starting services..."

# Start SSH (agar bisa masuk Debug Console Azure)
service ssh start || true

echo "Starting Apache in foreground..."
# Gunakan 'exec' agar Apache menjadi proses utama (PID 1)
exec apache2-foreground
