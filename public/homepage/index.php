<?php
// Memasukkan file data agar variabel-variabelnya bisa digunakan di halaman ini
require 'data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGI Store - Kualitas, Jujur, Kuantitas</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

    <header>
        <div class="top-bar">
            <div class="container">
                <a href="#">Status pesanan</a>
                <div class="user-menu">
                    <i class="fa-solid fa-user"></i>
                    <span>Anya</span>
                </div>
            </div>
        </div>
        <div class="main-header">
            <div class="container">
                <img src="https://placehold.co/150x50/003366/FFFFFF?text=LGI+STORE" alt="LGI Store Logo" class="logo">
                <nav>
                    <ul>
                        <?php foreach ($nav_links as $link): ?>
                            <li><a href="#" class="<?= ($link === 'JERSEY') ? 'active' : '' ?>"><?= htmlspecialchars($link) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Search...">
                        <button><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                    <a href="#" class="cart-icon"><i class="fa-solid fa-shopping-cart"></i></a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <img src="https://placehold.co/1200x400/cccccc/333333?text=Gambar+Banner+Toko" alt="Banner Toko LGI Store">
        </section>

        <section class="categories container">
            <?php foreach ($categories as $category): ?>
                <div class="category-item">
                    <div class="icon-circle"><i class="<?= htmlspecialchars($category['icon']) ?>"></i></div>
                    <p><?= htmlspecialchars($category['name']) ?></p>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="popular-products container">
            <h2>Produk Populer</h2>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="price">Rp. <?= number_format($product['price'], 2, ',', '.') ?></p>
                        <?php if (!empty($product['sizes'])): ?>
                            <p class="size"><?= htmlspecialchars($product['sizes']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="shop-by-look container">
            <h2>SHOP BY LOOK</h2>
            <div class="look-grid">
                <?php foreach ($shop_by_look_items as $item): ?>
                <div class="look-item">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                     <div class="look-info">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="price">Rp. <?= number_format($item['price'], 2, ',', '.') ?></p>
                        <?php if (!empty($item['sizes'])): ?>
                            <p class="size"><?= htmlspecialchars($item['sizes']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-left">
                <img src="https://placehold.co/150x50/FFFFFF/003366?text=LGI+STORE" alt="LGI Store Logo" class="logo">
                <div class="social-media">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                    <a href="#"><i class="fab fa-x-twitter"></i></a>
                </div>
            </div>
            <div class="footer-right">
                <div class="footer-col">
                    <h4>TOKO KAMI</h4>
                    <p>Toko Pusat: Jl. Pulau Morotai [...]</p>
                    <p>Toko Cabang: Jl. Arif Rahman Hakim [...]</p>
                </div>
                <div class="footer-col">
                    <h4>INFO PENTING</h4>
                    <p>Info Pengiriman</p>
                    <p>Pengembalian Barang</p>
                    <p>Kebijakan Privasi</p>
                </div>
                <div class="footer-col">
                    <h4>CUSTOMER CARE</h4>
                    <p>Senin-Sabtu: 09.00-17.00 WIB</p>
                    <p>Minggu: Libur</p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>