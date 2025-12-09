# ⚡ QUICK ACTION GUIDE: FIX IMAGE 404 ERROR

## 🚨 MASALAH UTAMA

```
Error: GET https://sablontopilampung.com/storage/variants/Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp 404
```

**Root Cause**: Symlink di `/public/storage` RUSAK atau MISSING di Hostinger production.

---

## 🎯 DIAGNOSIS (5 MENIT)

### Opsi A: Diagnose via Laravel Command (LOCAL)
```bash
cd c:\laragon\www\hostinger\Katalog-Sablon-Topi-Lampung
php artisan images:analyze --deep
```

**Expected output**:
- ✅ atau ❌ di setiap section
- Jika ada ❌, baca solusi di bagian berikutnya

### Opsi B: Diagnose via SSH (PRODUCTION)
```powershell
# Run PowerShell script
.\image-diagnostic.ps1 -Mode analyze
```

---

## 🔧 FIX #1: SYMLINK RUSAK (PALING UMUM)

### Jika output menunjukkan:
```
❌ Symlink MISSING: /public/storage
atau
⚠️  /public/storage is a DIRECTORY (not symlink)
```

### SOLUSI:
```bash
# SSH ke server
ssh u157843933@sablontopilampung.com

# Navigate ke project
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Hapus symlink/folder lama
rm -rf public/storage

# Buat symlink baru
php artisan storage:link

# Verify
ls -la public/storage
# Expected: lrwxrwxrwx ... storage -> ../storage/app/public ✅

# Clear cache
php artisan optimize:clear
php artisan config:cache
```

**Verifikasi berhasil** (buka di browser):
```
https://sablontopilampung.com/storage/variants/[FILENAME].webp
# Should be 200 OK, not 404
```

---

## 🔧 FIX #2: FILE TIDAK TERSIMPAN

### Jika output menunjukkan:
```
❌ variants/: DIRECTORY NOT FOUND
atau
❌ Missing: variants/Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp
```

### PENYEBAB:
- Upload gagal
- Storage permission denied
- Disk space full

### SOLUSI:
```bash
# SSH ke server
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# 1. Fix permission
chmod -R 775 storage/app/public
chmod -R 775 bootstrap/cache

# 2. Clear cache
php artisan optimize:clear

# 3. Test dengan re-upload gambar via admin
# Admin → Management Product → Edit produk → Upload gambar baru

# 4. Verify file tersimpan
ls -la storage/app/public/variants/ | head -5
# Should show files with proper permission (rw-)
```

---

## 🔧 FIX #3: PERMISSION DENIED

### Jika SSH menunjukkan:
```bash
ls -la storage/app/public/variants/
# Output: dr-xr-xr-- (tidak bisa write)
```

### SOLUSI:
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Fix permission untuk storage
chmod -R 755 storage/
chmod -R 755 bootstrap/

# Make sure web server can write
chmod -R 775 storage/app/public
chmod -R 775 bootstrap/cache

# Verify
ls -la storage/app/public/ | grep d
# Expected: drwxrwxr-x
```

---

## 🔧 FIX #4: PATH GENERATOR SALAH

### Jika symlink OK tapi gambar masih 404

**Check di browser DevTools (F12)**:
```javascript
// Open Console tab, paste:
document.querySelectorAll('img[src*="/storage/"]').forEach(img => {
    console.log('URL:', img.src);
});
```

**Jika output**:
```
❌ https://sablontopilampung.com/storage/variants/...
```

**Expected**:
```
✅ https://sablontopilampung.com/public/storage/variants/...
```

### SOLUSI:
```bash
# Check apakah sudah ada perbaikan di:
# - app/Helpers/ImageHelper.php ✅ (sudah fix)
# - app/Http/Controllers/CatalogController.php ✅ (sudah fix)
# - resources/views/catalog.blade.php ✅ (sudah fix)

# Jika masih error, clear cache:
php artisan optimize:clear

# Clear browser cache:
# Ctrl+Shift+Delete → Select "All time" → Clear
```

---

## ✅ VERIFIKASI SOLUSI

### Step 1: SSH Test
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Ambil nama file variant yang ada
TESTFILE=$(ls storage/app/public/variants/ | head -1)
echo $TESTFILE

# Test via symlink
test -f public/storage/variants/$TESTFILE && echo "✅ File accessible" || echo "❌ File not accessible"

# Test via web
curl -I https://sablontopilampung.com/storage/variants/$TESTFILE
# Expected: HTTP/2 200 OK
```

### Step 2: Browser Test
1. Buka: https://sablontopilampung.com/admin/management-product
2. Edit produk dengan gambar
3. Buka DevTools (F12) → Network tab
4. Refresh halaman
5. Cari request ke `/storage/variants/`
6. Status harus **200**, bukan 404

### Step 3: Admin Test
1. Upload produk baru
2. Lihat preview gambar di halaman admin
3. Harus muncul, tidak ada broken image icon

### Step 4: Customer Test  
1. Buka: https://sablontopilampung.com/catalog/topi (atau kategori lain)
2. Lihat kartu produk
3. Gambar harus muncul
4. DevTools → Console tidak ada error 404

---

## 📊 CHECKLIST FINAL

- [ ] Jalankan: `php artisan images:analyze --deep`
- [ ] Baca output dan identifikasi masalah
- [ ] SSH ke production
- [ ] Hapus & buat ulang symlink: `rm -rf public/storage && php artisan storage:link`
- [ ] Fix permission: `chmod -R 775 storage/app/public`
- [ ] Clear cache: `php artisan optimize:clear && php artisan config:cache`
- [ ] Clear browser cache: Ctrl+Shift+Delete
- [ ] Refresh halaman admin
- [ ] Test halaman customer: /catalog/topi
- [ ] Verify DevTools Console (F12) tidak ada error 404

---

## 🆘 MASIH TIDAK BISA?

### Debug Lebih Lanjut:
```bash
# 1. Lihat recent errors
ssh u157843933@sablontopilampung.com
tail -100 /home/u157843933/domains/sablontopilampung.com/public_html/storage/logs/laravel.log

# 2. Check disk space
df -h /home

# 3. Validate file exists
find storage/app/public -name "*.webp" | wc -l

# 4. Check Apache/Nginx config
# Ask Hostinger support if symbolic links enabled in cPanel
```

### Hubungi Support:
Jika semua steps sudah dilakukan tapi masih error:
1. Collect logs: `tail -200 storage/logs/laravel.log > /tmp/logs.txt`
2. SSH dan download logs
3. Contact Hostinger support dengan mention:
   - "Symbolic links not working"
   - "storage/link command creates relative symlink tapi tidak accessible via HTTP"

---

## 📝 NOTES

- **Error "Reset currentEditId to null"**: Ini terpisah dari masalah gambar, tidak perlu diperbaiki
- **Gambar terus 404 meski sudah di-fix**: Mungkin browser cache, buka private/incognito window
- **Symlink berfungsi tapi masih 404**: Check `ImageHelper.php` path prefix, pastikan pakai `image_url()` helper

---

**Last Updated**: December 9, 2025
**Status**: Ready for production deployment
