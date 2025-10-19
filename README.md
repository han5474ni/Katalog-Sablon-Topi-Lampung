# 🎨 Katalog Sablon Topi Lampung

> Aplikasi web katalog sablon topi berbasis Laravel untuk mengelola produk, pelanggan, dan pesanan dengan sistem multi-role.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

## 📋 Daftar Isi

- [Tentang Aplikasi](#-tentang-aplikasi)
- [Teknologi](#-teknologi)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Git Workflow](#-git-workflow)
- [Keamanan](#-keamanan)
- [Deployment](#-deployment)

---

## 🎯 Tentang Aplikasi

**Katalog Sablon Topi Lampung** adalah platform e-commerce untuk bisnis sablon topi dengan fitur:

- 🔐 **Multi-Role**: Super Admin, Admin, dan Customer
- 👤 **Manajemen Pengguna**: Profil lengkap, avatar, Google OAuth
- 📦 **Manajemen Produk**: CRUD lengkap dengan gambar
- 📊 **Dashboard & Analytics**: Statistik real-time, export Excel/PDF
- 🛒 **E-Commerce**: Katalog, keranjang, detail produk
- 📝 **Activity Logging**: Tracking semua aktivitas

## 🛠 Teknologi

### Backend
- PHP 8.2+
- Laravel 12.x
- Livewire 3.6+ & Volt 1.7+
- Laravel Sanctum (API auth)
- Laravel Socialite (Google OAuth)
- Maatwebsite/Excel (Export Excel)

### Frontend
- TailwindCSS 3.x
- Alpine.js (via Livewire)
- Chart.js (Visualisasi data)
- Vite 6.x (Build tool)
- Bootstrap 5.3+

### Database
- MySQL/MariaDB (recommended)
- PostgreSQL, SQLite (supported)

---

## 💻 Persyaratan Sistem

### Minimum Requirements
```
- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.x
- NPM >= 9.x
- MySQL >= 5.7 atau MariaDB >= 10.3
- Apache/Nginx Web Server
```

### PHP Extensions
```
BCMath, Ctype, cURL, DOM, Fileinfo, Filter, Hash, 
Mbstring, OpenSSL, PDO, Session, Tokenizer, XML, GD/Imagick
```

### Tools Development
```
- Git >= 2.0
- Laragon/XAMPP/Wamp (Windows)
- VS Code atau IDE favorit
- PhpMyAdmin/TablePlus (Database management)
```

---

## 📥 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/han5474ni/Katalog-Sablon-Topi-Lampung.git
cd Katalog-Sablon-Topi-Lampung
```

### 2. Install Dependencies
```bash
# PHP dependencies
composer install

# Node.js dependencies
npm install
```

### 3. Setup Environment
```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database
```env
# Edit file .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=katalog_sablon_topi
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# Jalankan migrations
php artisan migrate

# (Optional) Seed data dummy
php artisan db:seed
```

### 5. Storage Link & Build Assets
```bash
# Symbolic link untuk storage
php artisan storage:link

# Build assets
npm run dev          # Development
npm run build        # Production
```

### 6. Jalankan Aplikasi
```bash
# Development server
php artisan serve
# Akses: http://localhost:8000

# Atau gunakan composer script (recommended)
composer dev
# Menjalankan server, queue, logs, dan vite bersamaan
```

---

## 🔄 Git Workflow

### Setup Awal
```bash
# Konfigurasi Git
git config --global user.name "Nama Anda"
git config --global user.email "email@example.com"
```

### Pull & Push

#### **Pull (Mengambil Update)**
```bash
# Update dari branch saat ini
git pull origin main

# Pull dengan rebase (lebih bersih)
git pull --rebase origin main

# Fetch tanpa merge
git fetch origin
```

#### **Push (Upload Perubahan)**
```bash
# Cek status
git status

# Tambah file ke staging
git add .                    # Semua file
git add file.php             # File spesifik
git add resources/views/     # Folder tertentu

# Commit dengan pesan jelas
git commit -m "feat: Tambah fitur export Excel"
git commit -m "fix: Perbaiki bug upload avatar"
git commit -m "docs: Update README instalasi"

# Push ke remote
git push origin main
git push origin feature/nama-fitur

# Push pertama kali (set upstream)
git push -u origin feature/nama-fitur
```

### Branch Management
```bash
# Lihat semua branch
git branch -a

# Buat branch baru
git checkout -b feature/nama-fitur
git checkout -b fix/nama-bug

# Pindah branch
git checkout main
git checkout develop

# Hapus branch lokal
git branch -d feature/nama-fitur
```

### Workflow Development
```bash
# 1. Update main branch
git checkout main
git pull origin main

# 2. Buat branch fitur baru
git checkout -b feature/payment-gateway

# 3. Kerjakan fitur & commit
git add .
git commit -m "feat: Implement payment gateway"

# 4. Push ke remote
git push -u origin feature/payment-gateway

# 5. Buat Pull Request di GitHub
# 6. Merge ke main setelah review
# 7. Delete branch
git checkout main
git branch -d feature/payment-gateway
```

### Mengatasi Konflik
```bash
# Jika terjadi konflik saat pull
git pull origin main
# CONFLICT (content): Merge conflict in file.php

# 1. Edit file yang konflik, hapus markers:
# <<<<<<< HEAD
# =======
# >>>>>>>

# 2. Tandai sebagai resolved
git add file.php

# 3. Commit
git commit -m "Resolve merge conflict"

# 4. Push
git push origin your-branch
```

### Commands Penting
```bash
# Undo changes
git restore file.php         # Buang perubahan
git restore --staged file    # Unstage
git reset HEAD~1             # Undo commit terakhir

# Stashing (simpan sementara)
git stash                    # Simpan
git stash pop                # Ambil kembali
git stash list               # Lihat daftar

# Cleaning
git clean -fd                # Hapus untracked files

# Remote
git remote -v                # Lihat remote
git remote set-url origin url # Ubah URL remote
```

### Conventional Commits
```bash
# Format: <type>: <subject>

feat: Fitur baru
fix: Bug fix
docs: Dokumentasi
style: Format code
refactor: Refactor code
perf: Performance
test: Testing
chore: Maintenance

# Contoh:
git commit -m "feat: add Google OAuth login"
git commit -m "fix: prevent duplicate items in cart"
git commit -m "docs: update installation steps"
```

---

## 🔒 Keamanan

### Fitur Keamanan

#### 1. Authentication & Authorization
- Multi-guard (Web, Admin, API)
- Role-based access control (Super Admin, Admin, Customer)
- Middleware protection
- Session management

#### 2. Database Security
- Eloquent ORM (SQL Injection prevention)
- Prepared statements
- Input validation & sanitization
- Mass assignment protection
- Bcrypt password hashing

#### 3. CSRF & XSS Protection
- Automatic CSRF tokens
- Blade template auto-escaping
- Content Security Policy (CSP)

#### 4. File Upload Security
- File type validation (mimes)
- File size limits (2MB avatar, 5MB produk)
- Filename sanitization
- Non-public storage dengan symbolic link

#### 5. Rate Limiting
- API rate limiting (60 requests/minute)
- Login throttling (5 attempts/minute)
- Lockout duration: 1 minute

#### 6. Environment Security
```bash
# JANGAN commit file .env!
# Simpan sensitive data di .env:
APP_KEY=
DB_PASSWORD=
MAIL_PASSWORD=
GOOGLE_CLIENT_SECRET=
```

#### 7. Session Security
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_COOKIE_HTTPONLY=true
SESSION_COOKIE_SECURE=true  # Production (HTTPS)
```

### Security Checklist Production

- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Aktifkan HTTPS dengan SSL certificate
- [ ] Update semua dependencies
- [ ] Enable firewall
- [ ] Setup backup database rutin
- [ ] Jalankan `composer audit`
- [ ] Setup monitoring (Sentry)
- [ ] Rate limiting aktif
- [ ] File permissions: 755 (dir), 644 (files)
- [ ] Disable directory listing

### Security Commands
```bash
# Check vulnerabilities
composer audit

# Update dependencies
composer update
npm update

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate new key (logout semua user!)
php artisan key:generate
```

### Google OAuth Setup
1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru
3. Aktifkan Google+ API
4. Buat OAuth 2.0 credentials
5. Tambah redirect URI: `http://localhost:8000/auth/google/callback`
6. Copy Client ID & Secret ke `.env`

### Email Setup (Gmail)
1. Aktifkan 2-Factor Authentication
2. Generate App Password di Google Account Settings
3. Tambahkan ke `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

---

## 🚀 Deployment

### Shared Hosting

#### Persiapan
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Upload
1. Upload semua file via FTP ke `public_html`
2. Set permissions: `chmod -R 755 storage bootstrap/cache`
3. Edit `.env` di server
4. Jalankan: `php artisan key:generate`
5. Jalankan: `php artisan migrate --force`
6. Ubah document root ke `public_html/public`

### VPS (Ubuntu/Nginx)

#### 1. Install Dependencies
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql \
  php8.2-mbstring php8.2-xml php8.2-bcmath \
  nginx mysql-server composer git nodejs npm
```

#### 2. Clone & Setup
```bash
cd /var/www
git clone https://github.com/han5474ni/Katalog-Sablon-Topi-Lampung.git
cd Katalog-Sablon-Topi-Lampung

composer install --no-dev --optimize-autoloader
npm install && npm run build

cp .env.example .env
php artisan key:generate

sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
```

#### 3. Nginx Config
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/Katalog-Sablon-Topi-Lampung/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

#### 4. SSL Certificate
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

---

## 🐛 Troubleshooting

### Common Issues

**Error: "No application encryption key"**
```bash
php artisan key:generate
```

**Error: "Class not found"**
```bash
composer dump-autoload
php artisan optimize:clear
```

**Permission denied (Storage)**
```bash
chmod -R 775 storage bootstrap/cache
```

**Assets tidak muncul**
```bash
npm run build
php artisan storage:link
php artisan optimize:clear
```

**Migration error: "Table exists"**
```bash
php artisan migrate:fresh  # HATI-HATI! Hapus semua data
```

**Google OAuth error**
```bash
# Pastikan callback URL sama dengan Google Console
php artisan config:clear
```

### Debug Mode
```bash
# Enable debug di .env
APP_DEBUG=true

# Check logs
tail -f storage/logs/laravel.log
```

---

## 📝 License

MIT License. Lihat file [LICENSE](LICENSE) untuk detail.

---

## 👥 Kontribusi

Kontribusi sangat diterima! Silakan:
1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'feat: Add AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📧 Kontak

Untuk pertanyaan atau dukungan, hubungi:
- Email: support@katalog-sablon-topi.com
- GitHub Issues: [Create Issue](https://github.com/han5474ni/Katalog-Sablon-Topi-Lampung/issues)

---

**Dibuat dengan ❤️ menggunakan Laravel & TailwindCSS**
