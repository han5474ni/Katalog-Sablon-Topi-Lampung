# 🔧 FIX SYMLINK 404 ERROR DI HOSTINGER

## 🚨 MASALAH

Browser menampilkan 404 untuk semua request ke `/storage/`:

```
GET https://sablontopilampung.com/storage/variants/XL3ExZIp8NIqCsUwBAZp2mWLCwxCbPJ6jdQXZv2Z.webp 404 (Not Found)
GET https://sablontopilampung.com/storage/products/oIOI8jjBIwMoxKtqQEV4Z66YtBc6sP332c2iml6x.webp 404 (Not Found)
```

✅ **File gambar TERSIMPAN** di `/storage/app/public/`
❌ **Symlink RUSAK atau MISSING** di `/public/storage`

---

## ✨ SOLUSI (2 PILIHAN)

### **OPTION A: SSH / SSH Command (RECOMMENDED)**

#### Step 1: SSH ke Hostinger
```bash
ssh u157843933@sablontopilampung.com
```

#### Step 2: Navigate ke folder project
```bash
cd /home/u157843933/domains/sablontopilampung.com/public_html
```

#### Step 3: Verifikasi gambar ada di storage
```bash
ls -la storage/app/public/variants/ | head -5
# Output: 
# XL3ExZIp8NIqCsUwBAZp2mWLCwxCbPJ6jdQXZv2Z.webp
# y6rflEbyZai8V9eMXArMffi4qsYvRg3ULdfnRAh5.webp
# ... (file ada)
```

#### Step 4: Cek status symlink sekarang
```bash
ls -la public/storage
```

**Jika output:**
- `lrwxrwxrwx ... storage -> ../storage/app/public` → Symlink OK, masalah lain
- `drwxr-xr-x ... storage` → Itu folder, bukan symlink ❌
- `No such file or directory` → Symlink missing ❌

#### Step 5: Hapus symlink/folder lama
```bash
rm -f public/storage
```

#### Step 6: Buat symlink baru
```bash
php artisan storage:link
```

**Expected output:**
```
The [public/storage] link has been connected to [storage/app/public].
Success
```

#### Step 7: Verifikasi symlink berfungsi
```bash
# Check symlink tercipta
ls -la public/storage
# Output: lrwxrwxrwx ... storage -> ../storage/app/public ✅

# Test akses gambar
curl -I https://sablontopilampung.com/storage/variants/XL3ExZIp8NIqCsUwBAZp2mWLCwxCbPJ6jdQXZv2Z.webp
# Output: HTTP/2 200 OK ✅ (bukan 404)
```

#### Step 8: Clear cache Laravel
```bash
php artisan config:cache
php artisan cache:clear
php artisan route:cache
```

#### Step 9: Refresh halaman admin
- Buka: `https://sablontopilampung.com/admin/management-product`
- Tekan `Ctrl+Shift+Delete` (clear browser cache)
- Refresh halaman
- **Gambar seharusnya sudah muncul** ✅

---

### **OPTION B: CPanel File Manager (Jika tidak bisa SSH)**

#### Step 1: Buka Hostinger CPanel
- Login ke: https://hostinger.com (atau domain dashboard)
- Masuk ke File Manager

#### Step 2: Navigate ke `/public_html/public`
```
public_html/
└── public/
    ├── index.php
    ├── .htaccess
    └── storage  ← CHECK INI
```

#### Step 3: Jika ada `storage` folder (bukan symlink):
- **Rename**: `storage` → `storage_old` (backup)
- **Hapus**: `storage_old` folder

#### Step 4: Via Terminal dalam CPanel:
Buka "Terminal" di CPanel:
```bash
cd /home/u157843933/domains/sablontopilampung.com/public_html
rm -f public/storage
php artisan storage:link
```

#### Step 5: Verify di browser
```
https://sablontopilampung.com/storage/variants/[filename].webp
```
Should return **200 OK**, not 404

---

## 🔍 TROUBLESHOOTING

### **Jika masih 404 setelah symlink dibuat:**

#### 1. Cek permission folder
```bash
ls -la storage/app/public/
# Harus bisa dibaca: drwxrwxr-x atau drwxr-xr-x
```

#### 2. Cek .htaccess di public folder
```bash
cat public/.htaccess | head -20
# Pastikan tidak ada block untuk /storage
```

#### 3. Cek Apache/Nginx symlink support
```bash
# Di .htaccess, tambahkan jika belum ada:
Options FollowSymLinks
```

#### 4. Force re-cache
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
```

#### 5. Restart PHP-FPM (jika ada akses)
```bash
systemctl restart php-fpm
```

---

## ✅ VERIFIKASI SUKSES

Setelah fix, cek:

### **1. Symlink tercipta:**
```bash
ls -la public/storage
# ✅ Harus menunjuk ke ../storage/app/public
```

### **2. File bisa diakses via HTTP:**
```bash
curl -I https://sablontopilampung.com/storage/variants/XL3ExZIp8NIqCsUwBAZp2mWLCwxCbPJ6jdQXZv2Z.webp
# ✅ HTTP/2 200 OK (bukan 404)
```

### **3. Gambar muncul di halaman admin:**
- Admin Dashboard → Management Product
- **Gambar preview harus tampil** ✅
- Console browser tidak ada 404 errors

### **4. Halaman public juga menampilkan gambar:**
- https://sablontopilampung.com/products
- Product cards menampilkan gambar ✅

---

## 📋 CHECKLIST FINAL

- [ ] SSH ke Hostinger atau akses CPanel
- [ ] Verifikasi file di `storage/app/public/` ada
- [ ] Hapus symlink/folder lama di `public/storage`
- [ ] Jalankan `php artisan storage:link`
- [ ] Cek symlink tercipta dengan `ls -la public/storage`
- [ ] Test URL gambar dengan `curl -I` atau browser
- [ ] Clear cache Laravel: `php artisan optimize:clear`
- [ ] Refresh halaman admin di browser (Ctrl+Shift+Delete)
- [ ] Verifikasi gambar tampil dan tidak ada 404 di console

---

## 🎯 QUICK FIX (COPY-PASTE)

Jalankan langsung di SSH Hostinger:

```bash
cd /home/u157843933/domains/sablontopilampung.com/public_html
rm -f public/storage
php artisan storage:link
php artisan optimize:clear
echo "✅ Fix completed. Check browser now."
```

Setelah itu, refresh halaman admin di browser.

---

## 📞 JIKA MASIH ERROR

1. **Cek error log:**
   ```bash
   tail -50 storage/logs/laravel.log | grep -i storage
   ```

2. **Cek symlink terciptanya:**
   ```bash
   readlink public/storage
   # Output: ../storage/app/public ✅
   ```

3. **Cek permission:**
   ```bash
   ls -la storage/app/ | grep public
   # Harus readable
   ```

4. **Contact Hostinger support** jika symlink tidak bisa dibuat (hosting limitation)
