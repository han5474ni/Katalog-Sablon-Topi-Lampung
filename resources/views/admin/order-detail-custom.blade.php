<x-admin-layout title="Custom Order Detail">
    @push('styles')
        {{-- Pastikan path ini sesuai dengan struktur proyek Anda --}}
        @vite(['resources/css/admin/order-detail-custom.css'])
        
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
        </h1>
        <div class="date-range-picker">
            <i class="far fa-calendar-alt"></i>
            <span>Oktober 16, 2025 - November 11, 2025</span>
        </div>
    </header>

    {{-- Kartu Detail Custom --}}
    <div class="card custom-detail-card">
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
                        {{-- Item Custom 2 (Contoh jika ada > 1) --}}
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

    {{-- Tombol Tindakan di Footer Halaman --}}
    <footer class="page-actions-footer">
        {{-- Tombol ini akan memicu modal --}}
        <button class="btn btn-reject" id="show-reject-modal-btn">Ditolak</button>
        <button class="btn btn-approve">Disetujui</button>
    </footer>
</div>

{{-- ======================================================= --}}
{{-- MODAL UNTUK MENOLAK PESANAN (Tambahan)                 --}}
{{-- ======================================================= --}}
<div class="modal-overlay" id="reject-modal-overlay"></div>
<div class="modal" id="reject-modal">
    <div class="modal-body">
        <label for="reject-reason" class="modal-label">Silahkan tambahkan deskripsi!</label>
        <textarea id="reject-reason" class="modal-textarea" placeholder="Silahkan tambahkan keterangan kenapa pesanan di tolak...."></textarea>
    </div>
    <div class="modal-footer">
        <button class="btn btn-subtle" id="modal-cancel-btn">Kembali</button>
        <button class="btn btn-warning" id="modal-confirm-btn">Selesai</button>
    </div>
</div>

</x-admin-layout>