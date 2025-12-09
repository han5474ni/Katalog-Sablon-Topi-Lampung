# COMMAND SSH UNTUK FIX SYMLINK HOSTINGER

Copy-paste command berikut langsung ke SSH terminal Hostinger:

```bash
# Step 1: Navigate ke project
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Step 2: Force remove directory (bukan symlink)
rm -rf public/storage

# Step 3: Verify sudah dihapus
ls -la public/ | grep storage

# Step 4: Create symlink baru
php artisan storage:link

# Step 5: Verify symlink tercipta
ls -la public/storage

# Step 6: Clear Laravel cache
php artisan config:cache
php artisan cache:clear
php artisan route:cache

# Step 7: Verify file accessible
ls -la storage/app/public/variants/ | head -3

echo "✅ Fix completed!"
```

---

## 📝 PENJELASAN YANG TERJADI:

Error yang Anda dapat:
```
rm: cannot remove 'public/storage': Is a directory
```

**Penyebab:** `public/storage` adalah DIRECTORY biasa, bukan symlink.

**Solusi:** 
- Gunakan `rm -rf public/storage` untuk remove directory
- Bukan `rm -f` (yang hanya untuk file)
- `-r` = recursive (remove directory dan isinya)

Error berikutnya:
```
ERROR  The [public/storage] link already exists.
```

**Penyebab:** Laravel artisan command tidak bisa overwrite directory yang sudah ada

**Solusi:**
- Hapus dulu directory dengan `rm -rf`
- Baru buat symlink dengan `php artisan storage:link`

---

## ✅ SETELAH FIX:

1. SSH ke Hostinger
2. Copy-paste semua command di atas
3. Tunggu sampai selesai
4. Buka browser: https://sablontopilampung.com/admin/management-product
5. Tekan Ctrl+Shift+Delete (clear cache)
6. Refresh halaman
7. **Gambar seharusnya sudah muncul** ✅

---

## 🧪 TEST SETELAH FIX:

Untuk memverifikasi symlink bekerja, jalankan:

```bash
# Test 1: Check symlink exists dan points ke target
ls -la public/storage

# Expected output:
# lrwxrwxrwx ... public/storage -> ../storage/app/public

# Test 2: Access sample file
curl -I https://sablontopilampung.com/storage/variants/XL3ExZIp8NIqCsUwBAZp2mWLCwxCbPJ6jdQXZv2Z.webp

# Expected output:
# HTTP/2 200 OK
# (bukan 404)

# Test 3: List files
ls -la storage/app/public/variants/ | wc -l
# Should show: 6 files + 2 (. dan ..)
```
