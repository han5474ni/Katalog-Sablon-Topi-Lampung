# 🔍 DEBUGGING: Gambar Masih 404 Setelah Symlink

## Problem
```
curl -I https://sablontopilampung.com/storage/variants/[FILENAME].webp
HTTP/2 404
```

Masih 404 padahal sudah mencoba membuat symlink.

---

## 🚨 Root Cause Checklist

### Check 1: Apakah symlink sudah ada?
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Cek symlink
ls -la public/storage
```

**Expected output**:
```
lrwxrwxrwx ... storage -> ../storage/app/public
```

**Jika output**:
- ❌ `No such file or directory` → Symlink belum dibuat
- ❌ `drwxr-xr-x ... storage` (folder) → Symlink belum diganti
- ✅ `lrwxrwxrwx ... storage -> ...` → Symlink OK, cek hal lain

---

### Check 2: Apakah file benar-benar ada?
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Cek file di storage
ls -la storage/app/public/variants/ | head -10
# Harus ada file .webp

# Ambil nama file pertama
TESTFILE=$(ls storage/app/public/variants/ | head -1)
echo $TESTFILE
# Contoh output: Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp
```

---

### Check 3: Apakah symlink akses file via symlink?
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

TESTFILE=$(ls storage/app/public/variants/ | head -1)

# Cek apakah file accessible via symlink path
ls -la public/storage/variants/$TESTFILE

# Jika berhasil → file visible via symlink
# Jika error → symlink tidak work properly
```

---

### Check 4: Apakah server config allow access ke symlink?
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Cek Apache/LiteSpeed config
cat public/.htaccess 2>/dev/null | head -20

# Atau cek jika ada restriction
ls -la public/ | grep -E "\.htaccess|index"
```

---

### Check 5: Apakah permission correct?
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Cek permission storage folder
ls -la storage/app/public/variants/
# Expected: drwxr-xr-x atau drwxrwxr-x (readable for all)

# Jika permission salah:
chmod -R 755 storage/app/public
chmod -R 755 bootstrap/cache
```

---

### Check 6: Test akses langsung ke storage folder
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

TESTFILE=$(ls storage/app/public/variants/ | head -1)

# Test 1: Apakah file readable
file storage/app/public/variants/$TESTFILE
# Expected: data, not errors

# Test 2: Apakah accessible via symlink
file public/storage/variants/$TESTFILE 2>&1
# Jika berhasil → shows "data", symlink OK
# Jika fail → "No such file or directory", symlink problem

# Test 3: Apakah bisa direct akses ke storage folder
curl -I "https://sablontopilampung.com/../../storage/app/public/variants/$TESTFILE" 2>&1 | head -1
# Jika 200 OK → means files accessible tapi maybe symlink issue
# Jika 403/404 → permission atau path issue
```

---

## 🔧 Solutions

### Solution 1: Symlink belum dibuat
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Hapus folder/file lama jika ada
rm -rf public/storage

# Buat symlink baru
cd public
ln -s ../storage/app/public storage
cd ..

# Verify
ls -la public/storage
# Expected: lrwxrwxrwx ... storage -> ../storage/app/public
```

### Solution 2: Symlink ada tapi tidak work (permission issue)
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Fix permission
chmod -R 755 storage/app/public
chmod -R 755 bootstrap/cache

# Test
curl -I https://sablontopilampung.com/storage/variants/Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp
```

### Solution 3: LiteSpeed server blocking symlink (last resort)
```bash
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Jika symlink tidak support, gunakan .htaccess rewrite sebagai fallback

# Buat .htaccess di public folder
cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Redirect storage requests ke actual storage folder
    RewriteRule ^storage/(.*)$ ../storage/app/public/$1 [L]
</IfModule>
EOF

# Atau create simlink folder dengan hidden files
mkdir -p public/storage/variants
mkdir -p public/storage/products
mkdir -p public/storage/custom-designs

# Symlink each subdirectory
ln -sf ../../storage/app/public/variants/* public/storage/variants/ 2>/dev/null || true
ln -sf ../../storage/app/public/products/* public/storage/products/ 2>/dev/null || true
ln -sf ../../storage/app/public/custom-designs/* public/storage/custom-designs/ 2>/dev/null || true
```

---

## 🎯 QUICK DIAGNOSTIC SCRIPT

Copy-paste ini langsung ke SSH:

```bash
#!/bin/bash
set -e

echo "=== DIAGNOSTIC REPORT ==="
echo ""

echo "1. Check symlink:"
ls -la public/storage 2>/dev/null || echo "❌ NO SYMLINK"

echo ""
echo "2. Check files exist:"
ls storage/app/public/variants/ 2>/dev/null | wc -l | xargs echo "Files found:"

echo ""
echo "3. Get test filename:"
TESTFILE=$(ls storage/app/public/variants/ 2>/dev/null | head -1)
echo "Testing: $TESTFILE"

echo ""
echo "4. Check via symlink:"
ls -la public/storage/variants/$TESTFILE 2>/dev/null && echo "✅ SYMLINK WORKS" || echo "❌ SYMLINK BROKEN"

echo ""
echo "5. Test via HTTP:"
curl -I https://sablontopilampung.com/storage/variants/$TESTFILE 2>/dev/null | head -1

echo ""
echo "=== END REPORT ==="
```

---

## 📋 NEXT STEPS

1. **Copy diagnostic script** ke SSH
2. **Jalankan** dan lihat output
3. **Berdasarkan output**, pilih solusi yang sesuai
4. **Clear cache**: `php artisan optimize:clear`
5. **Test lagi**: `curl -I https://sablontopilampung.com/storage/variants/[FILENAME].webp`

---

## 🆘 Still 404?

Jika masih 404 setelah semua ini, kemungkinannya:

1. **LiteSpeed config** tidak allow symlink
   - Hubungi Hostinger support untuk enable symlinks
   
2. **SELinux** atau security module blocking
   - Check: `getenforce` (jika output "Enforcing", bisa jadi masalah)
   
3. **File tidak tersimpan di storage** (upload problem)
   - Check logs: `tail -100 storage/logs/laravel.log | grep -i "image\|store"`

4. **Symlink path salah**
   - Verify manual: `readlink -f public/storage` harus point ke actual directory

---

**Status**: Diagnostic & troubleshooting guide ready
