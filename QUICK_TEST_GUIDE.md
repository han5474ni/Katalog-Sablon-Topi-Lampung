# 🧪 Quick Test Guide - Sinkronisasi Produk Admin ke Katalog

## 📝 Test Manual (Recommended)

### Step 1: Login sebagai Admin
```
URL: http://localhost/admin/login
Email: admin@lgistore.com (sesuaikan dengan data admin Anda)
Password: [password admin]
```

### Step 2: Buka Product Management
```
URL: http://localhost/admin/management-product
```

### Step 3: Tambah Produk Baru
1. Klik tombol **"Tambah Produk"** (biru, kanan atas)
2. Isi form:
   ```
   Nama Produk: Kaos Premium Test
   Kategori: kaos
   Harga: 75000
   Stok: 20
   Warna: Pilih beberapa (hitam, putih, biru)
   Ukuran: Pilih beberapa (S, M, L, XL)
   Deskripsi: Kaos premium dengan bahan cotton combed 30s
   Status: Active (toggle harus ON/hijau)
   ```
3. Upload gambar produk (opsional tapi recommended)
4. Klik **"Simpan Produk"**
5. ✅ Jika berhasil, akan muncul alert "Product created successfully"

### Step 4: Verifikasi di Katalog Customer
1. Buka tab baru atau window baru
2. Akses: `http://localhost/public/catalog/kaos`
3. ✅ **Cari produk "Kaos Premium Test"** → Harus muncul!

### Step 5: Test Edit Produk
1. Kembali ke Product Management
2. Klik ikon **Edit** (pensil) pada produk yang baru dibuat
3. Ubah harga menjadi: `65000`
4. Klik **"Simpan Produk"**
5. Refresh halaman catalog
6. ✅ **Harga harus berubah menjadi Rp 65.000**

### Step 6: Test Arsipkan Produk
1. Edit produk lagi
2. Toggle **Status** menjadi OFF (Draft)
3. Klik **"Simpan Produk"**
4. Refresh halaman catalog
5. ✅ **Produk harus HILANG dari katalog**

### Step 7: Test Aktifkan Lagi
1. Di Product Management, klik tab **"Draft"**
2. Produk yang tadi di-nonaktifkan akan muncul
3. Edit produk → Toggle Status ON
4. Klik **"Simpan Produk"**
5. Refresh catalog
6. ✅ **Produk muncul kembali di katalog**

---

## 🔧 Test via Tinker (Untuk Developer)

### 1. Buat Produk Test via Code
```bash
php artisan tinker
```

Kemudian jalankan:
```php
$product = App\Models\Product::create([
    'name' => 'Kaos Test via Tinker',
    'slug' => 'kaos-test-via-tinker',
    'category' => 'kaos',
    'price' => 50000,
    'stock' => 15,
    'description' => 'Produk test dari tinker',
    'colors' => ['hitam', 'putih'],
    'sizes' => ['M', 'L', 'XL'],
    'is_active' => true,
    'custom_design_allowed' => false
]);

echo "Product ID: " . $product->id;
```

### 2. Verifikasi Produk Tersimpan
```php
$count = App\Models\Product::active()->count();
echo "Total Active Products: " . $count;
```

### 3. Lihat Produk Kategori Kaos
```php
$kaos = App\Models\Product::active()->category('kaos')->get();
foreach($kaos as $item) {
    echo $item->name . " - Rp " . $item->formatted_price . "\n";
}
```

### 4. Test Update Produk
```php
$product = App\Models\Product::find(1); // Ganti 1 dengan ID produk
$product->update(['price' => 60000]);
echo "Updated! New price: Rp " . $product->formatted_price;
```

### 5. Test Nonaktifkan Produk
```php
$product = App\Models\Product::find(1);
$product->update(['is_active' => false]);

$activeCount = App\Models\Product::active()->count();
echo "Active products now: " . $activeCount;
```

---

## 🌐 Test URL Endpoints

### Catalog URLs (Customer View)
```
✅ Kaos:   http://localhost/public/catalog/kaos
✅ Topi:   http://localhost/public/catalog/topi
✅ Jaket:  http://localhost/public/catalog/jaket
✅ Jersey: http://localhost/public/catalog/jersey
✅ Tas:    http://localhost/public/catalog/tas
```

### Admin URLs
```
✅ Login:              http://localhost/admin/login
✅ Dashboard:          http://localhost/admin/dashboard
✅ Product Management: http://localhost/admin/management-product
```

### API Endpoints (Test dengan Postman/Insomnia)
```
GET  /admin/api/products              - List all products
POST /admin/api/products              - Create new product
GET  /admin/api/products/{id}         - Get single product
POST /admin/api/products/{id}         - Update product (with _method=PUT)
DELETE /admin/api/products/{id}       - Delete product
```

---

## 📊 Expected Results

### ✅ Skenario 1: Tambah Produk Active
- **Input**: Produk baru dengan `is_active = true`, `stock = 10`
- **Expected**: Produk **muncul** di katalog

### ✅ Skenario 2: Tambah Produk dengan Stok 0
- **Input**: Produk baru dengan `is_active = true`, `stock = 0`
- **Expected**: 
  - Backend auto-set `is_active = false`
  - Produk **tidak muncul** di katalog

### ✅ Skenario 3: Edit Harga Produk
- **Input**: Update harga dari 50000 → 75000
- **Expected**: Harga di katalog langsung berubah setelah refresh

### ✅ Skenario 4: Nonaktifkan Produk
- **Input**: Set `is_active = false`
- **Expected**: Produk **hilang** dari katalog

### ✅ Skenario 5: Filter by Category
- **Input**: Buka `/public/catalog/kaos`
- **Expected**: Hanya produk kategori "kaos" yang muncul

### ✅ Skenario 6: Search Product
- **Input**: Ketik "test" di search box
- **Expected**: Filter produk yang mengandung kata "test"

---

## 🐛 Common Issues & Solutions

### Issue 1: Produk Tidak Muncul
**Kemungkinan Penyebab:**
- ❌ `is_active = false` → Set menjadi `true`
- ❌ Kategori tidak sesuai → Pastikan kategori benar
- ❌ Cache browser → Hard refresh (Ctrl+Shift+R)

**Solusi:**
```sql
-- Cek status produk di database
SELECT id, name, category, is_active, stock FROM products WHERE id = [ID];

-- Update manual jika perlu
UPDATE products SET is_active = 1 WHERE id = [ID];
```

### Issue 2: Gambar Tidak Muncul
**Kemungkinan Penyebab:**
- ❌ Storage link belum dibuat

**Solusi:**
```bash
php artisan storage:link
```

### Issue 3: Error 500 saat Save Product
**Kemungkinan Penyebab:**
- ❌ CSRF token tidak valid
- ❌ Validation error

**Solusi:**
- Buka browser console (F12)
- Lihat error message
- Pastikan semua field required terisi

### Issue 4: Pagination Tidak Bekerja
**Solusi:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rebuild assets
npm run build
```

---

## 📸 Screenshot Checklist

Saat testing, pastikan Anda capture:
1. ✅ Form tambah produk (sebelum submit)
2. ✅ Success message setelah save
3. ✅ Produk muncul di table Product Management
4. ✅ Produk muncul di Catalog Customer
5. ✅ Detail produk saat diklik

---

## ✅ Final Verification

Jalankan checklist ini:

- [ ] Admin bisa login ke `/admin/login`
- [ ] Product Management page terbuka
- [ ] Bisa tambah produk baru
- [ ] Toggle Status bekerja (Active/Draft)
- [ ] Upload gambar berhasil
- [ ] Produk tersimpan di database
- [ ] Produk muncul di katalog customer
- [ ] Bisa edit produk
- [ ] Perubahan langsung terlihat di catalog
- [ ] Bisa nonaktifkan produk
- [ ] Produk hilang dari catalog saat dinonaktifkan
- [ ] Filter & search bekerja
- [ ] Pagination bekerja
- [ ] Klik produk → redirect ke detail page

**Jika semua ✅, sistem berhasil 100%!** 🎉

---

## 💡 Pro Tips

1. **Test dengan data real**: Gunakan gambar produk asli dan deskripsi lengkap
2. **Test di multiple browser**: Chrome, Firefox, Edge
3. **Test responsiveness**: Buka di mobile view (F12 → Toggle device)
4. **Monitor logs**: `tail -f storage/logs/laravel.log`
5. **Use Postman**: Test API endpoints untuk debugging

---

Selamat testing! 🚀
