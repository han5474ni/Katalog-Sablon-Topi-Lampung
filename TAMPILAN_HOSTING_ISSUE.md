# Analisis Perbedaan Tampilan: Hosting vs Localhost

## Ringkasan Masalah

Tampilan halaman utama (homepage) berbeda antara hosting dan localhost:

### Hosting (Produksi)
- Hero section menampilkan background abu-abu polos
- Hanya menampilkan teks "CARI STYLE JERSEY FAVORITMU"
- Tombol "Shop Now" sederhana
- Tidak ada background image
- Tidak ada overlay gradient
- Tidak ada badge "Custom Design Available"
- Tidak ada fitur list (Custom Design, Kualitas Premium)

### Localhost (Development)
- Hero section menampilkan background image dengan overlay
- Teks "Ekspresikan Gayamu" dengan highlight warna kuning
- Badge "Custom Design Available" di atas teks
- Dua tombol: "Mulai Belanja" dan "Lihat Koleksi"
- Fitur list dengan icon checkmark
- Animasi fade-in yang smooth

## Penyebab Masalah

### 1. **CSS Hero Section Tidak Ter-load**
File CSS `resources/css/guest/home.css` sudah ter-build di `public/build/assets/home-VEXRblBe.css`, tetapi styling untuk `.hero.hero-elegant` tidak diterapkan di hosting.

**Styling yang hilang:**
```css
.hero.hero-elegant {
    position: relative;
    min-height: 85vh;
    padding: 0;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: flex-start;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-overlay {
    background: linear-gradient(
        135deg,
        rgba(26, 32, 44, 0.9) 0%,
        rgba(26, 32, 44, 0.75) 30%,
        rgba(26, 32, 44, 0.5) 60%,
        rgba(26, 32, 44, 0.25) 100%
    );
}
```

### 2. **Asset Path Mungkin Tidak Benar**
File gambar `hero-products.png` ada di `public/images/hero-products.png`, tetapi path di view menggunakan `{{ asset('images/hero-products.png') }}` yang mungkin tidak bekerja dengan benar di hosting.

### 3. **Vite Manifest Tidak Ter-load**
File `public/build/manifest.json` sudah ada dan berisi entry untuk `resources/css/guest/home.css`, tetapi mungkin ada issue dengan:
- Cache browser
- Path relatif yang salah
- File tidak ter-serve dengan benar

## File yang Terlibat

### View
- `resources/views/pages/home.blade.php` - Menggunakan `@vite()` untuk load CSS/JS

### CSS
- `resources/css/guest/home.css` - Berisi styling untuk hero section
- Build output: `public/build/assets/home-VEXRblBe.css`

### JavaScript
- `resources/js/guest/home.js` - Untuk slider functionality
- `resources/js/guest/product-slider.js` - Untuk product carousel

### Assets
- `public/images/hero-products.png` - Background image (2.5MB)

## Solusi

### 1. **Clear Cache di Hosting**
```bash
# Di hosting, jalankan:
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 2. **Rebuild Assets**
```bash
# Di local development:
npm run build

# Kemudian push ke hosting
git add public/build/
git commit -m "Rebuild assets"
git push origin hostinger
```

### 3. **Verifikasi .env di Hosting**
Pastikan file `.env` di hosting memiliki:
```
APP_ENV=production
APP_DEBUG=false
```

### 4. **Verifikasi File Permissions**
Pastikan folder `public/build/` dan `public/images/` memiliki permission yang benar (644 untuk files, 755 untuk directories).

### 5. **Check Vite Configuration**
Verifikasi `vite.config.js` sudah benar:
```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/guest/home.css',
                'resources/js/guest/home.js',
                // ... other assets
            ],
            refresh: true,
        }),
    ],
});
```

## Debugging Steps

### 1. Buka DevTools di Hosting
- Buka browser DevTools (F12)
- Cek tab "Network" untuk melihat apakah CSS ter-load
- Cek tab "Console" untuk error messages

### 2. Cek HTML Source
- Lihat apakah `<link>` tag untuk CSS ada di HTML
- Verifikasi path CSS benar

### 3. Cek File Permissions
```bash
# Di hosting, jalankan:
ls -la public/build/assets/
ls -la public/images/
```

### 4. Test Asset Path
Buka di browser:
```
https://sablon-topi-lampung.com/build/assets/home-VEXRblBe.css
https://sablon-topi-lampung.com/images/hero-products.png
```

Jika tidak bisa diakses, ada issue dengan path atau permissions.

## Rekomendasi

1. **Immediate**: Clear cache dan rebuild assets
2. **Short-term**: Verifikasi file permissions dan .env configuration
3. **Long-term**: Setup CI/CD pipeline untuk automatic build dan deploy

## Catatan Teknis

- File CSS sudah ter-build dengan benar (manifest.json ada)
- File gambar sudah ada di server
- Kemungkinan besar issue adalah cache atau path configuration
- Tidak ada perubahan kode yang diperlukan, hanya deployment/configuration

---

**Status**: Perlu investigasi lebih lanjut di hosting environment
**Priority**: High - Mempengaruhi user experience
**Assigned to**: DevOps/Hosting Team
