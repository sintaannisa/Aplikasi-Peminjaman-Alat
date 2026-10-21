docker run --rm \
  -v "$PWD:/var/www" \
  -w /var/www \
  --entrypoint php \
  01api-ujikom-app-laravel artisan key:generate

  PANDUAN KONFIGURASI PROJECT LARAVEL PEMINJAMAN ALAT DENGAN DOCKER

A. EKSTRAK ZIP PROJECT

1. Download ZIP project Aplikasi-Peminjaman-Alat dari GitHub.
2. Letakkan file ZIP di folder home Ubuntu.
3. Buka terminal Ubuntu, lalu jalankan:

cd ~
unzip Aplikasi-Peminjaman-Alat-main.zip
cd ~/Aplikasi-Peminjaman-Alat-main

B. MENYIAPKAN FILE ENV

Masuk ke folder backend dan salin file konfigurasi:

cd ~/Aplikasi-Peminjaman-Alat-main/backend
cp .env.example .env

Buka file .env:

nano .env

Sesuaikan konfigurasi berikut. Nilai database harus sama dengan konfigurasi MySQL pada docker-compose.yml:

APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=mysql-server
DB_PORT=3306
DB_DATABASE=API_ujikom
DB_USERNAME=api_ujikom
DB_PASSWORD=api_ujikom
SESSION_DRIVER=file

Jangan menghapus konfigurasi lain yang sudah ada di file .env.

Simpan dengan Ctrl+O, tekan Enter, lalu keluar dengan Ctrl+X.

C. MEMASANG DEPENDENCY COMPOSER

Pastikan masih berada di folder backend, lalu jalankan:

docker run --rm -v "$PWD:/app" -w /app composer:2 install

Perintah ini memasang dependency Laravel ke folder vendor menggunakan image Composer.

D. MEMBANGUN IMAGE DAN MENYIAPKAN CONTAINER

Kembali ke folder utama project:

cd ~/Aplikasi-Peminjaman-Alat-main

Bangun image Docker:

docker compose build

Setelah proses build berhasil, buat APP_KEY:

docker compose run --rm app-laravel php artisan key:generate

Kemudian jalankan container:

docker compose up -d

E. MENYIAPKAN DATABASE DAN MENJALANKAN APLIKASI

Jalankan migrasi untuk membuat tabel database:

docker compose exec app-laravel php artisan migrate --seed

Jalankan pembersihan cache konfigurasi:

docker compose exec app-laravel php artisan config:clear

F. MEMBUKA APLIKASI

Buka browser dan akses:

http://localhost:8000

CATATAN PENTING

1. Pastikan Docker Desktop dan integrasi WSL Ubuntu sudah aktif.
2. Gunakan konfigurasi database yang sesuai dengan docker-compose.yml.
3. Untuk instalasi baru di komputer sekolah, gunakan volume database baru dari Docker Compose. Jangan menghubungkannya ke volume eksternal dari komputer lain.
4. Jangan menjalankan migrate:fresh jika ada data yang perlu dipertahankan.
5. Jika docker compose build gagal, proses belum selesai dan error build perlu diperbaiki terlebih dahulu.
