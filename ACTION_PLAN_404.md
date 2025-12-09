# ⚡ LANGKAH SEGERA: Masih 404 - Debug & Fix

## 🚨 Status Saat Ini
```
curl -I https://sablontopilampung.com/storage/variants/[FILENAME].webp
HTTP/2 404
```

Masih 404. Ini berarti **symlink belum dibuat** atau **file tidak ada**.

---

## 🔧 ACTION: Jalankan Diagnostic Script

### Step 1: SSH ke server
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html
```

### Step 2: Download & run diagnostic script
```bash
# Jika file sudah ada di git:
bash diagnostic.sh

# Atau manual check:
echo "=== Check 1: Symlink ==="
ls -la public/storage

echo ""
echo "=== Check 2: Files in storage ==="
ls -la storage/app/public/variants/ | head -5

echo ""
echo "=== Check 3: Test file via symlink ==="
TESTFILE=$(ls storage/app/public/variants/ | head -1)
ls -la public/storage/variants/$TESTFILE

echo ""
echo "=== Check 4: Test via HTTP ==="
curl -I https://sablontopilampung.com/storage/variants/$TESTFILE
```

---

## 📋 Expected Outputs & What to Do

### Scenario 1: ❌ Symlink TIDAK ADA
```bash
ls -la public/storage
# Output: No such file or directory
```

**SOLUSI**:
```bash
# Buat symlink
rm -rf public/storage
cd public
ln -s ../storage/app/public storage
cd ..

# Verify
ls -la public/storage
# Expected: lrwxrwxrwx ... storage -> ../storage/app/public
```

---

### Scenario 2: ❌ Symlink ADA TAPI ADALAH FOLDER
```bash
ls -la public/storage
# Output: drwxr-xr-x ... storage (folder, bukan link)
```

**SOLUSI**:
```bash
# Hapus folder
rm -rf public/storage

# Buat symlink
cd public
ln -s ../storage/app/public storage
cd ..

# Clear cache
php artisan optimize:clear
```

---

### Scenario 3: ❌ File TIDAK ADA DI STORAGE
```bash
ls storage/app/public/variants/ | wc -l
# Output: 0
```

**SOLUSI**:
- Admin panel: upload produk dengan gambar
- Database check: lihat apakah ada `product_variants` dengan `image` field terisi

---

### Scenario 4: ✅ Semua OK tapi Masih 404
Jika diagnostic menunjukkan:
- ✅ Symlink ada
- ✅ File ada
- ✅ Symlink work (file accessible)
- ❌ Tapi curl masih 404

**KEMUNGKINAN**:
- LiteSpeed server blocking symlink
- .htaccess conflict
- Caching issue

**SOLUSI**:
```bash
# 1. Clear all cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache

# 2. Fix permission
chmod -R 755 storage/app/public
chmod -R 755 bootstrap/cache

# 3. Create .htaccess fallback di public/
cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteRule ^storage/(.*)$ ../storage/app/public/$1 [L]
</IfModule>
EOF

# 4. Test lagi
curl -I https://sablontopilampung.com/storage/variants/[FILENAME].webp
```

---

## 🎯 NEXT STEPS (BY PRIORITY)

1. **RUN DIAGNOSTIC SCRIPT** (2 menit)
   ```bash
   ssh u157843933@sablontopilampung.com
   cd /home/u157843933/domains/sablontopilampung.com/public_html
   bash diagnostic.sh
   ```

2. **BASED ON OUTPUT, FIX ACCORDINGLY** (5 menit)
   - Scenario 1 or 2: Create/recreate symlink
   - Scenario 3: Upload gambar
   - Scenario 4: Apply fallback .htaccess + clear cache

3. **VERIFY** (2 menit)
   ```bash
   curl -I https://sablontopilampung.com/storage/variants/[FILENAME].webp
   # Expected: HTTP/2 200 OK
   ```

4. **CLEAR BROWSER CACHE**
   - Ctrl+Shift+Delete
   - Refresh page

---

## 🆘 Still Not Working?

**Kumpulkan info ini**:
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

echo "=== 1. Symlink ==="
ls -la public/storage

echo ""
echo "=== 2. Files ==="
find storage/app/public/variants -type f | wc -l

echo ""
echo "=== 3. Test file ==="
TESTFILE=$(ls storage/app/public/variants/ | head -1)
echo "Filename: $TESTFILE"
ls -la public/storage/variants/$TESTFILE 2>&1

echo ""
echo "=== 4. HTTP test ==="
curl -I https://sablontopilampung.com/storage/variants/$TESTFILE

echo ""
echo "=== 5. Symlink target ==="
readlink -f public/storage

echo ""
echo "=== 6. Permission ==="
ls -ld storage/app/public/variants
```

Copy output semua, kirim untuk analisis lebih lanjut.

---

## 📚 Reference Files

- `diagnostic.sh` - Automated diagnostic script
- `DEBUG_404_MASIH_TERJADI.md` - Troubleshooting guide detail
- `FIX_SYMLINK_EXEC_DISABLED.md` - Jika masalah exec()
- `FIX_SUMMARY.md` - Complete fix procedures

---

**ESTIMATED TIME**: 15 menit untuk fix + verify

Mulai dengan diagnostic script sekarang! 🚀
