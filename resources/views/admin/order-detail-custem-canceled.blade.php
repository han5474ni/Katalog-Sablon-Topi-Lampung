<x-admin-layout title="Detail Pesanan Dibatalkan">
    @push('styles')
        {{-- Pastikan path ini sesuai dengan struktur proyek Anda --}}
        @vite(['resources/css/admin/order-detail-custom-canceled.css'])
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @endpush

<div class="order-detail-container">
    {{-- Header Halaman dengan Breadcrumb --}}
    <header class="page-header">
        <h1 class="page-title">
            <a href="#">Home</a> 
            <span class="breadcrumb-separator">></span> 
            <a href="#">Order List</a> 
            <span class="breadcrumb-separator">></span> 
            Detail
            <span class="breadcrumb-separator">></span>
            <span class="breadcrumb-canceled">Dibatalkan</span>
        </h1>
        <div class="date-range-picker">
            <i class="far fa-calendar-alt"></i>
            <span>Oktober 16, 2025 - November 11, 2025</span>
        </div>
    </header>

    {{-- Kartu Detail Custom --}}
    <div class="card custom-detail-card">
        
        {{-- Stempel Dibatalkan --}}
        <div class="stamp-container">
            <div class="stamp-dibatalkan">DIBATALKAN</div>
        </div>

        <div class="card-body">
            <div class="main-content">
                <table class="custom-details-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Detail</th>
                            <th>Preview & Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Item Custom 1 --}}
                        <tr>
                            <td class="category-col">Posisi Cetak</td>
                            <td>Depan Dada (Area A)</td>
                            <td>Panduan Area Cetak: <a href="#">[ Lihat Gambar Panduan ]</a></td>
                        </tr>
                        <tr>
                            <td class="category-col">Jenis Cetak</td>
                            <td>Sablon Digital (Printing)</td>
                            <td>Ukuran Cetak: A4 (21 x 29.7 cm)</td>
                        </tr>
                        <tr>
                            <td class="category-col">File Desain</td>
                            <td><a href="#" class="file-link">Logo_PT_ABC.png</a></td>
                            <td>
                                Preview: <a href="#">[ Tampilkan Pratinjau Desain ]</a><br>
                                Download: <a href="#">[ Unduh File Asli (.png) ]</a>
                            </td>
                        </tr>
                        {{-- Item Custom 2 --}}
                        <tr class="custom-item-divider">
                            <td class="category-col">Posisi Cetak</td>
                            <td>Lengan Kiri (Area A)</td>
                            <td>Panduan Area Cetak: <a href="#">[ Lihat Gambar Panduan ]</a></td>
                        </tr>
                        <tr>
                            <td class="category-col">Jenis Cetak</td>
                            <td>Sablon Digital (Printing)</td>
                            <td>Ukuran Cetak: A4 (15 x 29.7 cm)</td>
                        </tr>
                        <tr>
                            <td class="category-col">File Desain</td>
                            <td><a href="#" class="file-link">Logo_PT_ABC.png</a></td>
                            <td>
                                Preview: <a href="#">[ Tampilkan Pratinjau Desain ]</a><br>
                                Download: <a href="#">[ Unduh File Asli (.png) ]</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="product-image-section">
                {{-- Ganti src gambar ini --}}
                <img src="httpspre-logo.jpg" alt="Product Image">
            </div>
        </div>
    </div>

    {{-- Kartu Rincian Biaya dan Log --}}
    <div class="card summary-card">
        <div class="summary-table">
            <div class="summary-header">
                <div class="summary-col">Kategori</div>
                <div class="summary-col">Detail</div>
            </div>
            <div class="summary-body">
                <div class="summary-row">
                    <div class="summary-col">Biaya Produk</div>
                    <div class="summary-col">Rp 70.000</div>
                </div>
                <div class="summary-row">
                    <div class="summary-col">Biaya Custom</div>
                    <div class="summary-col">Rp 30.000</div>
                </div>
                <div class="summary-row">
                    <div class="summary-col">Subtotal</div>
                    <div class="summary-col">Rp 100.000</div>
                </div>
                <div class="summary-row">
                    <div class="summary-col">Diskon</div>
                    <div class="summary-col">Rp 0</div>
                </div>
                <div class="summary-row">
                    <div class="summary-col">Log Status Pesanan</div>
                    <div class="summary-col log-status">
                        Nov 8th, 2023 10:00: Pesanan dibuat. 
                        Nov 8th, 2023 10:15: diterima. 
                        Nov 9th, 2023 14:00: Status diubah ke Produksi.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Deskripsi Dibatalkan/Ditolak --}}
    {{-- (Menggunakan class yang sama dengan halaman 'ditolak' untuk konsistensi) --}}
    <div class="card deskripsi-ditolak-card">
        <div class="card-body">
            <h3 class="deskripsi-title">Deskripsi Ditolak</h3> 
            {{-- ^ Di gambar Anda, judulnya masih "Ditolak", jika ingin ganti, ubah di sini --}}
            <p class="deskripsi-text">
                Gambar/desain yang anda kirim tidak memenuhi syarat (tidak bisa di baca / tidak PNG mohon untuk lakukan perbaikan dan pesan kembali)
            </p>
        </div>
    </div>

</div>

</x-admin-layout>