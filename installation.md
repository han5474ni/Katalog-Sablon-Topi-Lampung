# Installation Guide: Katalog Sablon Topi Lampung

Panduan setup project dari awal hingga siap digunakan.

## 1. Clone Repository
```bash
git clone https://github.com/han5474ni/Katalog-Sablon-Topi-Lampung.git
cd Katalog-Sablon-Topi-Lampung
```

## 2. Install Dependencies
```bash
composer install
npm install
```

## 3. Setup Environment
```bash
copy .env.example .env   # Windows
cp .env.example .env     # Linux/Mac
php artisan key:generate
```

## 4. Konfigurasi Database
Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=katalog_sablon_topi
DB_USERNAME=root
DB_PASSWORD=
```
Lalu jalankan:
```bash
php artisan migrate
php artisan db:seed   # (Optional) data dummy
```

## 5. Storage Link & Build Assets
```bash
php artisan storage:link
npm run build
```

## 6. Jalankan Aplikasi
```bash
php artisan serve
# atau
composer dev
```
Akses: http://localhost:8000

## 7. Testing
```bash
php artisan test
```

## 8. Deployment
Lihat bagian deployment di README.md untuk shared hosting/VPS.

---

Jika ada error, cek file `storage/logs/laravel.log` atau bagian troubleshooting di README.md.