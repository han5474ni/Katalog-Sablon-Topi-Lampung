# DOKUMEN PENGUJIAN SISTEM
## Katalog Sablon Topi Lampung (LGI Store)

**Versi:** 1.0  
**Tanggal:** 5 Desember 2025  
**Penyusun:** Tim Development

---

# MODUL 1: AUTENTIKASI (AUTHENTICATION)

## 1.1 Login Customer

### Test Case ID: F-LOGIN-01

| **Test Case ID** | **F-LOGIN-01** |
|------------------|----------------|
| **Test Case Description** | Login - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Akun pengguna terdaftar sudah ada di database, user berada di halaman Login. |
| **Post-Requisite** | Pengguna dalam keadaan login dan berada di Dashboard/Halaman Utama |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman login | URL: /login | Halaman login tampil dengan form email dan password | Halaman login tampil | Chrome | PASS | - |
| 2 | Mengisi email yang valid | email: user@example.com | Field email terisi | Field email terisi | Chrome | PASS | - |
| 3 | Mengisi password yang valid | password: password123 | Field password terisi (masked) | Field password terisi | Chrome | PASS | - |
| 4 | Klik tombol Login | Klik "Login" | Redirect ke Dashboard dengan pesan sukses | Redirect ke Dashboard | Chrome | PASS | - |

---

### Test Case ID: F-LOGIN-02

| **Test Case ID** | **F-LOGIN-02** |
|------------------|----------------|
| **Test Case Description** | Login - Negative Test Case (Email Salah) |
| **Test Priority** | High |
| **Pre-Requisite** | User berada di halaman Login |
| **Post-Requisite** | User tetap di halaman Login dengan pesan error |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman login | URL: /login | Halaman login tampil | Halaman login tampil | Chrome | PASS | - |
| 2 | Mengisi email yang tidak terdaftar | email: tidakada@example.com | Field email terisi | Field email terisi | Chrome | PASS | - |
| 3 | Mengisi password | password: password123 | Field password terisi | Field password terisi | Chrome | PASS | - |
| 4 | Klik tombol Login | Klik "Login" | Menampilkan pesan error "Email atau password salah" | Pesan error muncul | Chrome | PASS | - |

---

### Test Case ID: F-LOGIN-03

| **Test Case ID** | **F-LOGIN-03** |
|------------------|----------------|
| **Test Case Description** | Login - Negative Test Case (Password Salah) |
| **Test Priority** | High |
| **Pre-Requisite** | Akun pengguna terdaftar, user berada di halaman Login |
| **Post-Requisite** | User tetap di halaman Login dengan pesan error |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman login | URL: /login | Halaman login tampil | Halaman login tampil | Chrome | PASS | - |
| 2 | Mengisi email yang valid | email: user@example.com | Field email terisi | Field email terisi | Chrome | PASS | - |
| 3 | Mengisi password yang salah | password: wrongpassword | Field password terisi | Field password terisi | Chrome | PASS | - |
| 4 | Klik tombol Login | Klik "Login" | Menampilkan pesan error "Email atau password salah" | Pesan error muncul | Chrome | PASS | - |

---

### Test Case ID: F-LOGIN-04

| **Test Case ID** | **F-LOGIN-04** |
|------------------|----------------|
| **Test Case Description** | Login - Negative Test Case (Field Kosong) |
| **Test Priority** | Medium |
| **Pre-Requisite** | User berada di halaman Login |
| **Post-Requisite** | Validasi error ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman login | URL: /login | Halaman login tampil | Halaman login tampil | Chrome | PASS | - |
| 2 | Tidak mengisi email | email: (kosong) | Field email kosong | Field email kosong | Chrome | PASS | - |
| 3 | Tidak mengisi password | password: (kosong) | Field password kosong | Field password kosong | Chrome | PASS | - |
| 4 | Klik tombol Login | Klik "Login" | Menampilkan validasi "Email wajib diisi" | Validasi muncul | Chrome | PASS | - |

---

## 1.2 Registrasi Customer

### Test Case ID: F-REG-01

| **Test Case ID** | **F-REG-01** |
|------------------|----------------|
| **Test Case Description** | Registrasi - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | User berada di halaman Register, email belum terdaftar |
| **Post-Requisite** | Akun baru terbuat dan user redirect ke halaman verifikasi email |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman register | URL: /register | Halaman register tampil | Halaman register tampil | Chrome | PASS | - |
| 2 | Mengisi nama lengkap | name: John Doe | Field nama terisi | Field nama terisi | Chrome | PASS | - |
| 3 | Mengisi email valid | email: johndoe@example.com | Field email terisi | Field email terisi | Chrome | PASS | - |
| 4 | Mengisi password | password: Password123! | Field password terisi | Field password terisi | Chrome | PASS | - |
| 5 | Mengisi konfirmasi password | password_confirmation: Password123! | Field konfirmasi terisi | Field konfirmasi terisi | Chrome | PASS | - |
| 6 | Klik tombol Daftar | Klik "DAFTAR" | Redirect ke halaman verifikasi email | Redirect berhasil | Chrome | PASS | - |

---

### Test Case ID: F-REG-02

| **Test Case ID** | **F-REG-02** |
|------------------|----------------|
| **Test Case Description** | Registrasi - Negative Test Case (Email Sudah Terdaftar) |
| **Test Priority** | High |
| **Pre-Requisite** | Email sudah terdaftar di database |
| **Post-Requisite** | User tetap di halaman register dengan pesan error |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman register | URL: /register | Halaman register tampil | Halaman register tampil | Chrome | PASS | - |
| 2 | Mengisi nama lengkap | name: Jane Doe | Field nama terisi | Field nama terisi | Chrome | PASS | - |
| 3 | Mengisi email yang sudah ada | email: existing@example.com | Field email terisi | Field email terisi | Chrome | PASS | - |
| 4 | Mengisi password | password: Password123! | Field password terisi | Field password terisi | Chrome | PASS | - |
| 5 | Mengisi konfirmasi password | password_confirmation: Password123! | Field konfirmasi terisi | Field konfirmasi terisi | Chrome | PASS | - |
| 6 | Klik tombol Daftar | Klik "DAFTAR" | Menampilkan error "Email sudah terdaftar" | Error muncul | Chrome | PASS | - |

---

### Test Case ID: F-REG-03

| **Test Case ID** | **F-REG-03** |
|------------------|----------------|
| **Test Case Description** | Registrasi - Negative Test Case (Password Tidak Cocok) |
| **Test Priority** | Medium |
| **Pre-Requisite** | User berada di halaman Register |
| **Post-Requisite** | Validasi error ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman register | URL: /register | Halaman register tampil | Halaman register tampil | Chrome | PASS | - |
| 2 | Mengisi nama lengkap | name: Test User | Field nama terisi | Field nama terisi | Chrome | PASS | - |
| 3 | Mengisi email | email: test@example.com | Field email terisi | Field email terisi | Chrome | PASS | - |
| 4 | Mengisi password | password: Password123! | Field password terisi | Field password terisi | Chrome | PASS | - |
| 5 | Mengisi konfirmasi password berbeda | password_confirmation: DifferentPass! | Field konfirmasi terisi | Field konfirmasi terisi | Chrome | PASS | - |
| 6 | Klik tombol Daftar | Klik "DAFTAR" | Menampilkan error "Konfirmasi password tidak cocok" | Error muncul | Chrome | PASS | - |

---

## 1.3 Login Admin

### Test Case ID: F-ADMIN-LOGIN-01

| **Test Case ID** | **F-ADMIN-LOGIN-01** |
|------------------|----------------|
| **Test Case Description** | Login Admin - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Akun admin terdaftar di database, user berada di halaman Admin Login |
| **Post-Requisite** | Admin dalam keadaan login dan berada di Dashboard Admin |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman admin login | URL: /admin/login | Halaman admin login tampil | Halaman admin login tampil | Chrome | PASS | - |
| 2 | Mengisi email admin | email: admin@lgistore.com | Field email terisi | Field email terisi | Chrome | PASS | - |
| 3 | Mengisi password admin | password: admin123 | Field password terisi | Field password terisi | Chrome | PASS | - |
| 4 | Klik tombol Login | Klik "Login" | Redirect ke Dashboard Admin | Redirect ke Dashboard | Chrome | PASS | - |

---

### Test Case ID: F-ADMIN-LOGIN-02

| **Test Case ID** | **F-ADMIN-LOGIN-02** |
|------------------|----------------|
| **Test Case Description** | Login Admin - Negative Test Case (Kredensial Salah) |
| **Test Priority** | High |
| **Pre-Requisite** | User berada di halaman Admin Login |
| **Post-Requisite** | User tetap di halaman login dengan pesan error |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman admin login | URL: /admin/login | Halaman admin login tampil | Halaman admin login tampil | Chrome | PASS | - |
| 2 | Mengisi email admin | email: admin@lgistore.com | Field email terisi | Field email terisi | Chrome | PASS | - |
| 3 | Mengisi password salah | password: wrongpassword | Field password terisi | Field password terisi | Chrome | PASS | - |
| 4 | Klik tombol Login | Klik "Login" | Menampilkan error "Kredensial tidak valid" | Error muncul | Chrome | PASS | - |

---

## 1.4 Logout

### Test Case ID: F-LOGOUT-01

| **Test Case ID** | **F-LOGOUT-01** |
|------------------|----------------|
| **Test Case Description** | Logout Customer - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Customer dalam keadaan login |
| **Post-Requisite** | Customer keluar dari sistem dan session dihapus |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Klik menu profil | - | Dropdown profil muncul | Dropdown muncul | Chrome | PASS | - |
| 2 | Klik tombol Logout | Klik "Keluar" | Redirect ke halaman login, session dihapus | Redirect berhasil | Chrome | PASS | - |
| 3 | Akses halaman protected | URL: /dashboard | Redirect ke halaman login | Redirect ke login | Chrome | PASS | - |

---

# MODUL 2: PRODUK (PRODUCT)

## 2.1 Lihat Produk

### Test Case ID: F-PROD-01

| **Test Case ID** | **F-PROD-01** |
|------------------|----------------|
| **Test Case Description** | Lihat Semua Produk - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Database memiliki data produk, user di halaman utama |
| **Post-Requisite** | Daftar produk ditampilkan dengan benar |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman all products | URL: /all-products | Halaman produk tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Verifikasi produk card | - | Produk card menampilkan gambar, nama, harga | Semua info tampil | Chrome | PASS | - |
| 3 | Verifikasi pagination | - | Pagination muncul jika produk > 12 | Pagination sesuai | Chrome | PASS | - |

---

### Test Case ID: F-PROD-02

| **Test Case ID** | **F-PROD-02** |
|------------------|----------------|
| **Test Case Description** | Lihat Detail Produk - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Produk ada di database |
| **Post-Requisite** | Halaman detail produk ditampilkan lengkap |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Klik produk card | Klik produk "Kaos Polos" | Redirect ke halaman detail | Redirect berhasil | Chrome | PASS | - |
| 2 | Verifikasi gambar produk | - | Gambar produk utama tampil | Gambar tampil | Chrome | PASS | - |
| 3 | Verifikasi informasi produk | - | Nama, harga, deskripsi, stok tampil | Semua info tampil | Chrome | PASS | - |
| 4 | Verifikasi pilihan varian | - | Pilihan warna dan ukuran tersedia | Varian tersedia | Chrome | PASS | - |

---

### Test Case ID: F-PROD-03

| **Test Case ID** | **F-PROD-03** |
|------------------|----------------|
| **Test Case Description** | Filter Produk Berdasarkan Kategori - Positive Test Case |
| **Test Priority** | Medium |
| **Pre-Requisite** | Produk memiliki kategori berbeda |
| **Post-Requisite** | Produk difilter sesuai kategori |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Membuka halaman catalog | URL: /catalog/topi | Halaman catalog kategori topi | Halaman tampil | Chrome | PASS | - |
| 2 | Verifikasi filter | - | Hanya produk kategori topi yang tampil | Filter benar | Chrome | PASS | - |
| 3 | Klik kategori lain | Klik "Kaos" | Produk difilter ke kategori kaos | Filter berubah | Chrome | PASS | - |

---

### Test Case ID: F-PROD-04

| **Test Case ID** | **F-PROD-04** |
|------------------|----------------|
| **Test Case Description** | Lihat Produk - Negative Test Case (Produk Tidak Ada) |
| **Test Priority** | Medium |
| **Pre-Requisite** | ID produk tidak ada di database |
| **Post-Requisite** | Halaman 404 atau pesan error ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Akses URL produk tidak ada | URL: /produk/999999 | Halaman 404 atau error tampil | Halaman 404 tampil | Chrome | PASS | - |

---

# MODUL 3: KERANJANG (CART)

## 3.1 Tambah ke Keranjang

### Test Case ID: F-CART-01

| **Test Case ID** | **F-CART-01** |
|------------------|----------------|
| **Test Case Description** | Tambah Produk ke Keranjang - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | User sudah login, produk tersedia |
| **Post-Requisite** | Produk berhasil ditambahkan ke keranjang |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka detail produk | URL: /produk/{slug} | Halaman detail tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Pilih varian warna | Klik "Merah" | Warna terpilih | Warna terpilih | Chrome | PASS | - |
| 3 | Pilih varian ukuran | Klik "L" | Ukuran terpilih | Ukuran terpilih | Chrome | PASS | - |
| 4 | Tentukan jumlah | qty: 2 | Jumlah terisi 2 | Jumlah terisi | Chrome | PASS | - |
| 5 | Klik Tambah Keranjang | Klik "Tambah ke Keranjang" | Notifikasi sukses, badge cart bertambah | Sukses | Chrome | PASS | - |

---

### Test Case ID: F-CART-02

| **Test Case ID** | **F-CART-02** |
|------------------|----------------|
| **Test Case Description** | Tambah ke Keranjang - Negative Test Case (Belum Login) |
| **Test Priority** | High |
| **Pre-Requisite** | User belum login |
| **Post-Requisite** | User redirect ke halaman login |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka detail produk | URL: /produk/{slug} | Halaman detail tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Klik Tambah Keranjang | Klik "Tambah ke Keranjang" | Redirect ke halaman login | Redirect ke login | Chrome | PASS | - |

---

### Test Case ID: F-CART-03

| **Test Case ID** | **F-CART-03** |
|------------------|----------------|
| **Test Case Description** | Tambah ke Keranjang - Negative Test Case (Stok Habis) |
| **Test Priority** | High |
| **Pre-Requisite** | User sudah login, produk stok 0 |
| **Post-Requisite** | Pesan error stok habis ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka produk stok habis | URL: /produk/{slug-stok-habis} | Halaman detail tampil, stok 0 | Halaman tampil | Chrome | PASS | - |
| 2 | Klik Tambah Keranjang | Klik "Tambah ke Keranjang" | Tombol disabled atau error "Stok habis" | Error tampil | Chrome | PASS | - |

---

## 3.2 Lihat Keranjang

### Test Case ID: F-CART-04

| **Test Case ID** | **F-CART-04** |
|------------------|----------------|
| **Test Case Description** | Lihat Keranjang - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | User login, keranjang berisi produk |
| **Post-Requisite** | Halaman keranjang menampilkan semua item |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Klik icon keranjang | Klik icon cart di navbar | Redirect ke halaman keranjang | Halaman tampil | Chrome | PASS | - |
| 2 | Verifikasi item | - | Produk, jumlah, harga tampil | Semua info tampil | Chrome | PASS | - |
| 3 | Verifikasi subtotal | - | Subtotal dihitung dengan benar | Subtotal benar | Chrome | PASS | - |

---

### Test Case ID: F-CART-05

| **Test Case ID** | **F-CART-05** |
|------------------|----------------|
| **Test Case Description** | Update Jumlah Item - Positive Test Case |
| **Test Priority** | Medium |
| **Pre-Requisite** | User login, keranjang berisi produk |
| **Post-Requisite** | Jumlah item dan total diupdate |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman keranjang | URL: /keranjang | Halaman keranjang tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Klik tombol tambah qty | Klik "+" | Jumlah bertambah 1 | Jumlah bertambah | Chrome | PASS | - |
| 3 | Verifikasi total | - | Total harga diupdate | Total terupdate | Chrome | PASS | - |

---

### Test Case ID: F-CART-06

| **Test Case ID** | **F-CART-06** |
|------------------|----------------|
| **Test Case Description** | Hapus Item dari Keranjang - Positive Test Case |
| **Test Priority** | Medium |
| **Pre-Requisite** | User login, keranjang berisi produk |
| **Post-Requisite** | Item terhapus dari keranjang |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman keranjang | URL: /keranjang | Halaman keranjang tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Klik tombol hapus item | Klik icon trash | Item terhapus, notifikasi sukses | Item terhapus | Chrome | PASS | - |
| 3 | Verifikasi keranjang | - | Item tidak ada lagi di list | Item hilang | Chrome | PASS | - |

---

# MODUL 4: PEMESANAN (ORDER)

## 4.1 Checkout

### Test Case ID: F-ORDER-01

| **Test Case ID** | **F-ORDER-01** |
|------------------|----------------|
| **Test Case Description** | Checkout - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | User login, keranjang berisi produk, alamat tersedia |
| **Post-Requisite** | Order berhasil dibuat dengan status pending |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Klik checkout di keranjang | Klik "Checkout" | Redirect ke halaman alamat | Halaman alamat tampil | Chrome | PASS | - |
| 2 | Pilih alamat pengiriman | Pilih alamat | Alamat terpilih | Alamat dipilih | Chrome | PASS | - |
| 3 | Pilih metode pengiriman | Pilih "JNE Reguler" | Ongkir dihitung | Ongkir tampil | Chrome | PASS | - |
| 4 | Lanjut ke pembayaran | Klik "Lanjutkan" | Halaman pembayaran tampil | Halaman tampil | Chrome | PASS | - |
| 5 | Pilih metode pembayaran | Pilih "Transfer Bank" | Metode terpilih | Metode dipilih | Chrome | PASS | - |
| 6 | Konfirmasi pesanan | Klik "Bayar Sekarang" | Order dibuat, redirect ke detail | Order berhasil | Chrome | PASS | - |

---

### Test Case ID: F-ORDER-02

| **Test Case ID** | **F-ORDER-02** |
|------------------|----------------|
| **Test Case Description** | Checkout - Negative Test Case (Keranjang Kosong) |
| **Test Priority** | High |
| **Pre-Requisite** | User login, keranjang kosong |
| **Post-Requisite** | Pesan error ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Akses halaman checkout | URL: /checkout | Error/redirect ke keranjang | Redirect dengan pesan | Chrome | PASS | - |

---

### Test Case ID: F-ORDER-03

| **Test Case ID** | **F-ORDER-03** |
|------------------|----------------|
| **Test Case Description** | Checkout - Negative Test Case (Alamat Tidak Ada) |
| **Test Priority** | High |
| **Pre-Requisite** | User login, keranjang ada isi, alamat belum diatur |
| **Post-Requisite** | User diminta mengisi alamat |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Klik checkout | Klik "Checkout" | Redirect ke halaman alamat | Halaman alamat tampil | Chrome | PASS | - |
| 2 | Verifikasi pesan | - | Pesan "Silakan tambah alamat" | Pesan tampil | Chrome | PASS | - |

---

## 4.2 Lihat Pesanan

### Test Case ID: F-ORDER-04

| **Test Case ID** | **F-ORDER-04** |
|------------------|----------------|
| **Test Case Description** | Lihat Daftar Pesanan - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | User login, memiliki riwayat pesanan |
| **Post-Requisite** | Daftar pesanan ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman pesanan | URL: /order-list | Halaman pesanan tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Verifikasi list pesanan | - | Daftar pesanan dengan status tampil | List tampil | Chrome | PASS | - |
| 3 | Klik detail pesanan | Klik pesanan | Modal/halaman detail tampil | Detail tampil | Chrome | PASS | - |

---

# MODUL 5: PEMBAYARAN (PAYMENT)

## 5.1 Proses Pembayaran

### Test Case ID: F-PAY-01

| **Test Case ID** | **F-PAY-01** |
|------------------|----------------|
| **Test Case Description** | Pembayaran Midtrans - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Order sudah dibuat dengan status pending |
| **Post-Requisite** | Pembayaran berhasil, status order berubah |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman pembayaran | URL: /pembayaran | Halaman pembayaran tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Pilih metode bayar | Pilih "Gopay" | Metode terpilih | Metode dipilih | Chrome | PASS | - |
| 3 | Klik bayar | Klik "Bayar" | Popup Midtrans muncul | Popup tampil | Chrome | PASS | - |
| 4 | Selesaikan pembayaran | Input data pembayaran | Pembayaran sukses | Status paid | Chrome | PASS | - |

---

### Test Case ID: F-PAY-02

| **Test Case ID** | **F-PAY-02** |
|------------------|----------------|
| **Test Case Description** | Pembayaran - Negative Test Case (Pembayaran Gagal) |
| **Test Priority** | High |
| **Pre-Requisite** | Order pending |
| **Post-Requisite** | Status tetap pending, notifikasi gagal |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman pembayaran | URL: /pembayaran | Halaman pembayaran tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Batalkan pembayaran | Klik "Batal" di popup | Kembali ke halaman pembayaran | Halaman tampil | Chrome | PASS | - |
| 3 | Verifikasi status | - | Status order tetap pending | Status pending | Chrome | PASS | - |

---

# MODUL 6: CUSTOM DESIGN

## 6.1 Upload Custom Design

### Test Case ID: F-CUSTOM-01

| **Test Case ID** | **F-CUSTOM-01** |
|------------------|----------------|
| **Test Case Description** | Upload Custom Design - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | User login, produk mendukung custom design |
| **Post-Requisite** | Desain berhasil diupload dan order custom dibuat |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman custom design | URL: /custom-design | Halaman form tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Pilih produk | Select produk "Kaos" | Produk terpilih | Produk dipilih | Chrome | PASS | - |
| 3 | Upload file desain | File: desain.png (< 5MB) | Preview desain tampil | Preview tampil | Chrome | PASS | - |
| 4 | Isi instruksi | "Sablon depan ukuran A4" | Field terisi | Field terisi | Chrome | PASS | - |
| 5 | Pilih varian | Warna: Hitam, Ukuran: L | Varian terpilih | Varian dipilih | Chrome | PASS | - |
| 6 | Submit order | Klik "Submit" | Order custom dibuat, redirect | Order berhasil | Chrome | PASS | - |

---

### Test Case ID: F-CUSTOM-02

| **Test Case ID** | **F-CUSTOM-02** |
|------------------|----------------|
| **Test Case Description** | Upload Custom Design - Negative Test Case (File Terlalu Besar) |
| **Test Priority** | Medium |
| **Pre-Requisite** | User login |
| **Post-Requisite** | Pesan error ukuran file |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman custom design | URL: /custom-design | Halaman tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Upload file besar | File: large.png (> 10MB) | Error "Ukuran file melebihi batas" | Error tampil | Chrome | PASS | - |

---

### Test Case ID: F-CUSTOM-03

| **Test Case ID** | **F-CUSTOM-03** |
|------------------|----------------|
| **Test Case Description** | Upload Custom Design - Negative Test Case (Format Tidak Valid) |
| **Test Priority** | Medium |
| **Pre-Requisite** | User login |
| **Post-Requisite** | Pesan error format file |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman custom design | URL: /custom-design | Halaman tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Upload file invalid | File: document.pdf | Error "Format file tidak didukung" | Error tampil | Chrome | PASS | - |

---

# MODUL 7: CHATBOT

## 7.1 Chat dengan Bot

### Test Case ID: F-CHAT-01

| **Test Case ID** | **F-CHAT-01** |
|------------------|----------------|
| **Test Case Description** | Chatbot - Positive Test Case (Tanya Harga) |
| **Test Priority** | Medium |
| **Pre-Requisite** | User login, popup chatbot tersedia |
| **Post-Requisite** | Bot merespon dengan informasi produk |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Klik icon chatbot | Klik icon chat | Popup chatbot muncul | Popup tampil | Chrome | PASS | - |
| 2 | Ketik pesan | "Rekomendasi produk murah" | Pesan terkirim | Pesan tampil | Chrome | PASS | - |
| 3 | Verifikasi respons bot | - | Bot merespon dengan list produk | Respons tampil | Chrome | PASS | - |

---

### Test Case ID: F-CHAT-02

| **Test Case ID** | **F-CHAT-02** |
|------------------|----------------|
| **Test Case Description** | Chatbot - Positive Test Case (Quick Reply) |
| **Test Priority** | Medium |
| **Pre-Requisite** | User login, popup chatbot terbuka |
| **Post-Requisite** | Quick reply terkirim dan bot merespon |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka popup chatbot | Klik icon chat | Popup tampil dengan quick replies | Popup tampil | Chrome | PASS | - |
| 2 | Klik quick reply | Klik "Cek stok" | Pesan terkirim otomatis | Pesan terkirim | Chrome | PASS | - |
| 3 | Verifikasi respons | - | Bot merespon tentang stok | Respons tampil | Chrome | PASS | - |

---

# MODUL 8: PROFIL PENGGUNA

## 8.1 Edit Profil

### Test Case ID: F-PROFILE-01

| **Test Case ID** | **F-PROFILE-01** |
|------------------|----------------|
| **Test Case Description** | Edit Profil - Positive Test Case |
| **Test Priority** | Medium |
| **Pre-Requisite** | User login |
| **Post-Requisite** | Data profil berhasil diupdate |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman profil | URL: /profile | Halaman profil tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Edit nama | name: "Nama Baru" | Field terisi | Field terisi | Chrome | PASS | - |
| 3 | Edit no telepon | phone: "081234567890" | Field terisi | Field terisi | Chrome | PASS | - |
| 4 | Klik simpan | Klik "Simpan" | Data tersimpan, notifikasi sukses | Update berhasil | Chrome | PASS | - |

---

### Test Case ID: F-PROFILE-02

| **Test Case ID** | **F-PROFILE-02** |
|------------------|----------------|
| **Test Case Description** | Update Avatar - Positive Test Case |
| **Test Priority** | Low |
| **Pre-Requisite** | User login |
| **Post-Requisite** | Avatar berhasil diupdate |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman profil | URL: /profile | Halaman profil tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Klik upload avatar | Klik "Ubah Foto" | Dialog file muncul | Dialog tampil | Chrome | PASS | - |
| 3 | Pilih foto | File: avatar.jpg | Preview tampil | Preview tampil | Chrome | PASS | - |
| 4 | Simpan | Klik "Simpan" | Avatar terupdate | Avatar berubah | Chrome | PASS | - |

---

### Test Case ID: F-PROFILE-03

| **Test Case ID** | **F-PROFILE-03** |
|------------------|----------------|
| **Test Case Description** | Edit Profil - Negative Test Case (Email Sudah Ada) |
| **Test Priority** | Medium |
| **Pre-Requisite** | User login, email sudah dipakai user lain |
| **Post-Requisite** | Error ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman profil | URL: /profile | Halaman profil tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Ubah email ke email yang sudah ada | email: existing@example.com | Field terisi | Field terisi | Chrome | PASS | - |
| 3 | Klik simpan | Klik "Simpan" | Error "Email sudah digunakan" | Error tampil | Chrome | PASS | - |

---

# MODUL 9: NOTIFIKASI

## 9.1 Lihat Notifikasi

### Test Case ID: F-NOTIF-01

| **Test Case ID** | **F-NOTIF-01** |
|------------------|----------------|
| **Test Case Description** | Lihat Notifikasi - Positive Test Case |
| **Test Priority** | Medium |
| **Pre-Requisite** | User login, ada notifikasi |
| **Post-Requisite** | Notifikasi ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Klik icon notifikasi | Klik icon bell | Dropdown notifikasi muncul | Dropdown tampil | Chrome | PASS | - |
| 2 | Verifikasi list | - | Daftar notifikasi tampil | List tampil | Chrome | PASS | - |
| 3 | Klik notifikasi | Klik item notifikasi | Redirect ke halaman terkait | Redirect berhasil | Chrome | PASS | - |

---

### Test Case ID: F-NOTIF-02

| **Test Case ID** | **F-NOTIF-02** |
|------------------|----------------|
| **Test Case Description** | Mark All Read - Positive Test Case |
| **Test Priority** | Low |
| **Pre-Requisite** | User login, ada notifikasi unread |
| **Post-Requisite** | Semua notifikasi menjadi read |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka notifikasi | Klik icon bell | Dropdown tampil | Dropdown tampil | Chrome | PASS | - |
| 2 | Klik tandai semua dibaca | Klik "Tandai Semua Dibaca" | Semua notifikasi menjadi read | Status berubah | Chrome | PASS | - |
| 3 | Verifikasi badge | - | Badge notifikasi hilang | Badge hilang | Chrome | PASS | - |

---

# MODUL 10: ADMIN - MANAJEMEN PRODUK

## 10.1 Tambah Produk

### Test Case ID: F-ADMIN-PROD-01

| **Test Case ID** | **F-ADMIN-PROD-01** |
|------------------|----------------|
| **Test Case Description** | Tambah Produk - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Admin login |
| **Post-Requisite** | Produk baru berhasil ditambahkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman manajemen produk | URL: /admin/management-product | Halaman tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Klik tambah produk | Klik "Tambah Produk" | Form produk muncul | Form tampil | Chrome | PASS | - |
| 3 | Isi nama produk | name: "Topi Trucker" | Field terisi | Field terisi | Chrome | PASS | - |
| 4 | Isi harga | price: 75000 | Field terisi | Field terisi | Chrome | PASS | - |
| 5 | Pilih kategori | category: "Topi" | Kategori terpilih | Kategori dipilih | Chrome | PASS | - |
| 6 | Upload gambar | File: produk.jpg | Gambar terupload | Gambar tampil | Chrome | PASS | - |
| 7 | Isi deskripsi | description: "Topi trucker..." | Field terisi | Field terisi | Chrome | PASS | - |
| 8 | Simpan produk | Klik "Simpan" | Produk tersimpan, redirect ke list | Produk berhasil | Chrome | PASS | - |

---

### Test Case ID: F-ADMIN-PROD-02

| **Test Case ID** | **F-ADMIN-PROD-02** |
|------------------|----------------|
| **Test Case Description** | Tambah Produk - Negative Test Case (Field Wajib Kosong) |
| **Test Priority** | High |
| **Pre-Requisite** | Admin login |
| **Post-Requisite** | Validasi error ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka form tambah produk | Klik "Tambah Produk" | Form tampil | Form tampil | Chrome | PASS | - |
| 2 | Tidak mengisi field wajib | (kosong) | Form kosong | Form kosong | Chrome | PASS | - |
| 3 | Klik simpan | Klik "Simpan" | Validasi error muncul | Error tampil | Chrome | PASS | - |

---

## 10.2 Edit Produk

### Test Case ID: F-ADMIN-PROD-03

| **Test Case ID** | **F-ADMIN-PROD-03** |
|------------------|----------------|
| **Test Case Description** | Edit Produk - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Admin login, produk ada di database |
| **Post-Requisite** | Produk berhasil diupdate |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka list produk | URL: /admin/all-products | List produk tampil | List tampil | Chrome | PASS | - |
| 2 | Klik edit produk | Klik icon edit | Form edit tampil | Form tampil | Chrome | PASS | - |
| 3 | Ubah harga | price: 80000 | Field terisi nilai baru | Field terupdate | Chrome | PASS | - |
| 4 | Simpan perubahan | Klik "Update" | Produk terupdate | Update berhasil | Chrome | PASS | - |

---

## 10.3 Hapus Produk

### Test Case ID: F-ADMIN-PROD-04

| **Test Case ID** | **F-ADMIN-PROD-04** |
|------------------|----------------|
| **Test Case Description** | Hapus Produk - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Admin login, produk ada di database |
| **Post-Requisite** | Produk berhasil dihapus |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka list produk | URL: /admin/all-products | List produk tampil | List tampil | Chrome | PASS | - |
| 2 | Klik hapus produk | Klik icon delete | Dialog konfirmasi muncul | Dialog tampil | Chrome | PASS | - |
| 3 | Konfirmasi hapus | Klik "Ya, Hapus" | Produk terhapus | Produk terhapus | Chrome | PASS | - |

---

# MODUL 11: ADMIN - MANAJEMEN PESANAN

## 11.1 Lihat Pesanan

### Test Case ID: F-ADMIN-ORDER-01

| **Test Case ID** | **F-ADMIN-ORDER-01** |
|------------------|----------------|
| **Test Case Description** | Lihat Daftar Pesanan Admin - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Admin login, ada data pesanan |
| **Post-Requisite** | Daftar pesanan ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman pesanan | URL: /admin/orders | Halaman pesanan tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Verifikasi list | - | Daftar pesanan dengan filter status | List tampil | Chrome | PASS | - |
| 3 | Filter by status | Pilih "Pending" | Pesanan difilter | Filter berhasil | Chrome | PASS | - |

---

## 11.2 Update Status Pesanan

### Test Case ID: F-ADMIN-ORDER-02

| **Test Case ID** | **F-ADMIN-ORDER-02** |
|------------------|----------------|
| **Test Case Description** | Update Status Pesanan - Positive Test Case |
| **Test Priority** | High |
| **Pre-Requisite** | Admin login, pesanan dengan status pending |
| **Post-Requisite** | Status pesanan berubah |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka detail pesanan | Klik pesanan | Detail pesanan tampil | Detail tampil | Chrome | PASS | - |
| 2 | Klik approve | Klik "Approve" | Dialog konfirmasi | Dialog tampil | Chrome | PASS | - |
| 3 | Konfirmasi | Klik "Ya" | Status berubah ke "approved" | Status berubah | Chrome | PASS | - |
| 4 | Verifikasi notifikasi | - | Customer menerima notifikasi | Notifikasi dikirim | Chrome | PASS | - |

---

# MODUL 12: ADMIN - MANAJEMEN USER

## 12.1 Lihat User

### Test Case ID: F-ADMIN-USER-01

| **Test Case ID** | **F-ADMIN-USER-01** |
|------------------|----------------|
| **Test Case Description** | Lihat Daftar User - Positive Test Case |
| **Test Priority** | Medium |
| **Pre-Requisite** | Admin login |
| **Post-Requisite** | Daftar user ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman user | URL: /admin/management-users | Halaman user tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Verifikasi tab | - | Tab Admin dan Customer tersedia | Tab tersedia | Chrome | PASS | - |
| 3 | Klik tab Customer | Klik "Customer" | List customer tampil | List tampil | Chrome | PASS | - |

---

## 12.2 Hapus User

### Test Case ID: F-ADMIN-USER-02

| **Test Case ID** | **F-ADMIN-USER-02** |
|------------------|----------------|
| **Test Case Description** | Hapus User - Positive Test Case |
| **Test Priority** | Medium |
| **Pre-Requisite** | Admin login, user ada di database |
| **Post-Requisite** | User berhasil dihapus |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka list user | URL: /admin/management-users | List user tampil | List tampil | Chrome | PASS | - |
| 2 | Klik hapus user | Klik icon delete | Dialog konfirmasi | Dialog tampil | Chrome | PASS | - |
| 3 | Konfirmasi hapus | Klik "Ya, Hapus" | User terhapus | User terhapus | Chrome | PASS | - |

---

# MODUL 13: ADMIN - ANALYTICS

## 13.1 Lihat Dashboard Analytics

### Test Case ID: F-ADMIN-ANALYTICS-01

| **Test Case ID** | **F-ADMIN-ANALYTICS-01** |
|------------------|----------------|
| **Test Case Description** | Lihat Analytics - Positive Test Case |
| **Test Priority** | Medium |
| **Pre-Requisite** | Admin login |
| **Post-Requisite** | Dashboard analytics ditampilkan |

**Test Execution Steps:**

| S.No | Action | Inputs | Expected Output | Actual Output | Test Browser | Test Result | Test Comments |
|------|--------|--------|-----------------|---------------|--------------|-------------|---------------|
| 1 | Buka halaman analytics | URL: /admin/analytics | Halaman analytics tampil | Halaman tampil | Chrome | PASS | - |
| 2 | Verifikasi chart | - | Grafik penjualan tampil | Chart tampil | Chrome | PASS | - |
| 3 | Verifikasi statistik | - | Total order, revenue tampil | Stats tampil | Chrome | PASS | - |
| 4 | Filter periode | Pilih "30 hari" | Data difilter | Filter berhasil | Chrome | PASS | - |

---

# RINGKASAN DOKUMEN PENGUJIAN

| Modul | Total Test Case | Positive | Negative |
|-------|----------------|----------|----------|
| 1. Autentikasi | 7 | 4 | 3 |
| 2. Produk | 4 | 3 | 1 |
| 3. Keranjang | 6 | 4 | 2 |
| 4. Pemesanan | 4 | 2 | 2 |
| 5. Pembayaran | 2 | 1 | 1 |
| 6. Custom Design | 3 | 1 | 2 |
| 7. Chatbot | 2 | 2 | 0 |
| 8. Profil | 3 | 2 | 1 |
| 9. Notifikasi | 2 | 2 | 0 |
| 10. Admin - Produk | 4 | 3 | 1 |
| 11. Admin - Pesanan | 2 | 2 | 0 |
| 12. Admin - User | 2 | 2 | 0 |
| 13. Admin - Analytics | 1 | 1 | 0 |
| **TOTAL** | **42** | **29** | **13** |

---

**Catatan:**
- Dokumen ini dibuat berdasarkan analisis fitur sistem Katalog Sablon Topi Lampung (LGI Store)
- Test Result diisi dengan PASS/FAIL setelah pengujian dilakukan
- Test Comments diisi dengan catatan jika ada issue atau anomali
- Browser yang digunakan untuk testing: Google Chrome (Latest Version)
