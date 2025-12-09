# 🔗 FIX SYMLINK DI HOSTINGER (exec() disabled)

## 🚨 MASALAH

```
Error: Call to undefined function Illuminate\Filesystem\exec()
```

**Penyebab**: Hostinger disable `exec()` function untuk security. Laravel default `storage:link` command menggunakan `exec()` untuk membuat symlink.

---

## ✅ SOLUSI (3 PILIHAN)

### **PILIHAN 1: Via cPanel File Manager (PALING MUDAH)**

#### Step 1: Login ke cPanel
- Buka: https://hostinger.com
- Login dengan akun Anda

#### Step 2: Buka File Manager
- Cari "File Manager" atau "File Manager"
- Buka folder: `/public_html/public`

#### Step 3: Buat Symbolic Link
- Di folder `/public_html/public`, klik kanan
- Pilih "Create Symbolic Link" atau "Link"
- Link name: `storage`
- Target: `/home/u157843933/domains/sablontopilampung.com/public_html/storage/app/public`
- Click "Create"

#### Step 4: Verify
```bash
# SSH dan check
ls -la public/storage
# Expected: lrwxrwxrwx ... storage -> ../storage/app/public ✅
```

---

### **PILIHAN 2: Via Custom PHP Script (OTOMATIS)**

Gunakan command Laravel baru yang saya buat:

```bash
# SSH ke server
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Run command baru
php artisan symlink:create

# Expected output:
# ✅ Symlink created successfully!
# ✅ Verified: /public/storage -> ../storage/app/public
```

**Jika masih error**, use fallback .htaccess:
```bash
php artisan symlink:create --createHtaccess
```

---

### **PILIHAN 3: Manual SSH (PALING AMAN)**

```bash
# SSH
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Cek status sekarang
ls -la public/storage

# Jika ada folder/file lama, hapus
rm -rf public/storage

# Test symlink() function availability
php -r "echo symlink('test', 'test_link') ? 'OK' : 'FAILED';"

# Jika OK, run command
php artisan symlink:create

# Jika masih error, try relative path manual
cd public
ln -s ../storage/app/public storage
cd ..

# Verify
ls -la public/storage
```

---

## 🔧 FALLBACK: .htaccess Alias Method

Jika symlink tidak support, gunakan .htaccess sebagai alternatif.

**Buat file**: `/public_html/public/storage/.htaccess`

Isi:
```apache
# Redirect storage requests to actual storage folder
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /storage/
    RewriteRule ^(.*)$ ../../../storage/app/public/$1 [L]
</IfModule>
```

---

## ✅ VERIFY SOLUTION

```bash
# Step 1: SSH check
ssh u157843933@sablontopilampung.com
cd /home/u157843933/domains/sablontopilampung.com/public_html

# Symlink status
readlink public/storage
# Expected: ../storage/app/public

# File exists
ls -la storage/app/public/variants/ | head -3
# Should show .webp files

# Step 2: HTTP test
curl -I https://sablontopilampung.com/storage/variants/Oda6LAjQSWBlI9krQeTOi6KD5wHmnk7RPIrGxKw5.webp
# Expected: HTTP/2 200 OK ✅

# Step 3: Browser test
# Open: https://sablontopilampung.com/admin/management-product
# Edit product → image harus muncul
```

---

## 📝 COMMAND YANG TERSEDIA

```bash
# Buat symlink (try multiple methods automatically)
php artisan symlink:create

# Dengan fallback .htaccess
php artisan symlink:create --createHtaccess

# Debug info
php artisan images:analyze --deep
```

---

## 🆘 JIKA MASIH ERROR

```bash
# Debug: Check function availability
php -i | grep "exec\|symlink"

# Jika exec disabled:
# Gunakan solusi via cPanel atau manual ln -s

# Jika symlink disabled:
# Gunakan .htaccess fallback

# Check Hostinger restrictions
php -r "var_dump(function_exists('exec'), function_exists('symlink'));"
```

---

## 🎯 RECOMMENDED FLOW

1. **Coba automatic**: `php artisan symlink:create`
2. **Jika gagal**: Coba manual symlink di cPanel File Manager (paling reliable)
3. **Jika symlink tidak support**: Use .htaccess fallback
4. **Verify**: `curl -I https://sablontopilampung.com/storage/variants/[FILENAME]` → 200 OK

---

**Status**: Ready to deploy  
**Last Updated**: December 9, 2025
