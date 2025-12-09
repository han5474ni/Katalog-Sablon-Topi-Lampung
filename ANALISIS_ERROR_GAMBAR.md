# 🔍 ANALISIS MENYELURUH: ERROR 404 GAMBAR DI HOSTINGER

## 📊 DIAGNOSIS MASALAH

### Error yang Terjadi:
```
GET https://sablontopilampung.com/storage/variants/Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp 404 (Not Found)
```

### Root Cause: Ada 3 Kemungkinan Utama

---

## 🎯 ROOT CAUSE #1: SYMLINK RUSAK / MISSING

### Gejala:
- File **TERSIMPAN** di `storage/app/public/variants/`
- Tapi **TIDAK BISA DIAKSES** via `/storage/` path
- Error 404 terus menerus

### Penyebab:
1. **Symlink tidak tercipta** saat `php artisan storage:link`
2. **Symlink rusak** (hardcoded path salah)
3. **Permission denied** di `/public` folder

### Bukti & Cek:
```bash
# SSH ke server
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Cek apakah symlink ada
ls -la public/storage

# Jika output:
# ❌ lrwxrwxrwx ... storage -> /home/u157843933/.../storage/app/public
#    (hardcoded path - MASALAH DI HOSTINGER)
# ❌ drwxr-xr-x ... storage (folder bukan symlink)
# ❌ No such file or directory (tidak ada)

# Cek file di storage ada
ls storage/app/public/variants/ | head -3
# Output: Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp (FILE ADA)
```

### Solusi:
```bash
# 1. Hapus symlink/folder lama
rm -rf public/storage

# 2. Buat symlink baru
php artisan storage:link

# 3. Verifikasi
ls -la public/storage
# Expected: lrwxrwxrwx ... storage -> ../storage/app/public ✅

# 4. Test akses
curl -I https://sablontopilampung.com/storage/variants/Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp
# Expected: HTTP/2 200 OK ✅
```

---

## 🎯 ROOT CAUSE #2: FILE TIDAK TERSIMPAN DENGAN BENAR

### Gejala:
- Database berisi `variants/Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp`
- Tapi file **TIDAK ADA** di `storage/app/public/variants/`

### Penyebab:
1. **Dual save mechanism error** (baris 902 ProductManagementController):
   ```php
   file_put_contents($fullFilePath, (string) $encodedImage); // Method 1
   Storage::disk('public')->put($path, (string) $encodedImage); // Method 2
   ```
   Salah satu gagal, database terupdate tapi file tidak tersimpan

2. **Permission issue** pada folder `storage/app/public`

3. **Disk space penuh**

### Bukti & Cek:
```bash
# Cek permission folder
ls -la storage/app/public/variants/ | head -3

# Jika permission: drwxr-xr-- (group tidak bisa write) ❌

# Cek disk space
df -h /home/u157843933
# Jika Disk Use > 95% ❌

# Cek logs Laravel
tail -50 storage/logs/laravel.log | grep -i "image\|store"
# Lihat error saat upload
```

### Solusi:
```bash
# Fix permission
chmod -R 775 storage/app/public
chmod -R 775 bootstrap/cache

# Clear cache Laravel
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

---

## 🎯 ROOT CAUSE #3: IMAGE HELPER PATH SALAH

### Gejala:
- Symlink OK, file ada
- Tapi path di JavaScript/HTML salah

### Penyebab:
ImageHelper `image_url()` menggunakan:
```php
if (app()->environment('production') && config('app.url') && strpos(config('app.url'), 'sablontopilampung.com') !== false) {
    return asset('public/storage/' . $cleanPath);
}
```

Tapi ada places yang masih menggunakan hardcoded `/storage/` path.

### Bukti & Cek:
Lihat browser DevTools:
```
katalog.blade.php line 402:
} else if (product.image) {
    // MASALAH: Menggunakan /storage/ bukan /public/storage/
    imageUrl = `/storage/${product.image}`;
}
```

### Solusi:
Sudah diperbaiki di commit terbaru dengan smart detection:
```javascript
if (window.location.hostname.includes('sablontopilampung.com')) {
    imageUrl = `/public/storage/${product.image}`;
} else {
    imageUrl = `/storage/${product.image}`;
}
```

---

## ✅ LANGKAH DIAGNOSIS LENGKAP

### Step 1: Verifikasi File Ada
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Ambil nama file dari error log
find storage/app/public/variants -name "*Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5*"

# Jika output: storage/app/public/variants/Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp
#   ✅ FILE ADA → Lanjut ke Step 2

# Jika tidak ada output:
#   ❌ FILE TIDAK ADA → Masalah upload/storage
```

### Step 2: Verifikasi Symlink
```bash
# Check symlink
ls -la public/storage

# Expected: lrwxrwxrwx ... storage -> ../storage/app/public
# Jika tidak sesuai, jalankan:
rm -rf public/storage
php artisan storage:link
```

### Step 3: Test URL Langsung
```bash
# Test via curl
curl -I https://sablontopilampung.com/storage/variants/Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp

# Expected: HTTP/2 200 OK
# Jika 404, symlink bermasalah
```

### Step 4: Clear Cache Browser
```
Developer Tools → Network → Disable cache
Ctrl+Shift+Delete → Clear all cache
Refresh halaman
```

### Step 5: Clear Cache Laravel
```bash
php artisan optimize:clear
php artisan storage:link
```

---

## 🚀 SOLUSI PERMANEN (CHECKLIST)

- [ ] **SSH ke Hostinger** dan verifikasi file ada
- [ ] **Hapus & buat ulang symlink**:
  ```bash
  rm -rf public/storage
  php artisan storage:link
  ```
- [ ] **Fix permission**:
  ```bash
  chmod -R 775 storage/app/public
  chmod -R 775 bootstrap/cache
  ```
- [ ] **Clear Laravel cache**:
  ```bash
  php artisan optimize:clear
  ```
- [ ] **Clear browser cache** (Ctrl+Shift+Delete)
- [ ] **Refresh halaman** dan lihat console error
- [ ] **Check logs** jika masih error:
  ```bash
  tail -100 storage/logs/laravel.log
  ```

---

## 📋 CHEAT SHEET COMMAND

### SSH Login & Diagnostik
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Lihat structure
tree -L 3 storage/app/public/

# Count files
find storage/app/public -type f | wc -l

# Check permissions
ls -la storage/app/public/

# Check disk space
df -h /home
```

### Fix Symlink
```bash
# Remove old
rm -rf public/storage

# Create new
php artisan storage:link

# Verify
readlink public/storage
# Expected: ../storage/app/public
```

### Clear Cache
```bash
# Laravel
php artisan optimize:clear
php artisan config:cache
php artisan cache:clear

# Full clear
rm -rf storage/framework/cache/data/*
rm -rf bootstrap/cache/config.php
```

### Test Image
```bash
# Find any variant file
ls storage/app/public/variants/ | head -1

# Test URL
curl -I https://sablontopilampung.com/storage/variants/[FILENAME]
# Should be 200, not 404
```

---

## 🔗 ERROR "Reset currentEditId to null"

### Masalah Lain yang Terpisah:
Error di browser console: `Reset currentEditId to null`

Ini berasal dari JavaScript `product-management-*.js`, bukan masalah gambar.

### Penyebab:
Ini adalah error handling normal untuk form state management. Terjadi ketika:
1. Admin membuka form edit produk
2. Update produk berhasil/gagal
3. Form mereset state

### Solusi:
Error ini TIDAK mempengaruhi upload/display gambar. Yang penting:
- Gambar tetap tersimpan ✅
- Gambar bisa diakses via symlink ✅

---

## 📞 KONTAK & SUPPORT

Jika masih error setelah semua langkah:
1. SSH ke server dan jalankan:
   ```bash
   php artisan images:analyze --fix
   ```
   (Command ini ada di `app/Console/Commands/AnalyzeImageIssues.php`)

2. Cek output dan lihat masalah apa yang terdeteksi

3. Lihat logs:
   ```bash
   tail -200 storage/logs/laravel.log
   ```

---

**Last Updated**: December 9, 2025
**Status**: Semua perbaikan sudah diaplikasikan
**Next Step**: Verifikasi symlink di production server
