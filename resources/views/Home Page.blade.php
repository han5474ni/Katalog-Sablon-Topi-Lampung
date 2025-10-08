<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGI Store - Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        /* Top Banner */
        .top-banner {
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            position: relative;
        }

        .top-banner a {
            color: #fff;
            text-decoration: underline;
            margin-right: 30px;
        }

        .top-banner .close-btn {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
        }

        /* Header */
        header {
            background-color: #0a1f3d;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-text-container {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .logo-circle {
            width: 45px;
            height: 45px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
        }

        .logo-text {
            color: #ffc107;
            font-weight: 900;
            font-size: 20px;
        }

        .logo-tagline {
            color: #e0e0e0;
            font-weight: 500;
            font-size: 10px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .main-nav {
            display: flex;
            gap: 30px;
        }

        .main-nav a {
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .main-nav a:hover {
            color: #ffc107;
        }

        .search-container {
            flex: 1;
            max-width: 400px;
            position: relative;
        }

        .search-box {
            width: 100%;
            padding: 12px 20px 12px 50px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .search-box:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.25);
        }

        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #e8eef5 0%, #f5f7fa 100%);
            padding: 60px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hero-content {
            max-width: 500px;
        }

        .hero-content h1 {
            font-size: 48px;
            color: #0a1f3d;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero-content p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .shop-btn {
            background-color: #ffc107;
            color: #000;
            padding: 15px 40px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .shop-btn:hover {
            background-color: #ffb300;
            transform: translateY(-2px);
        }

        .shop-btn-link {
            text-decoration: none;
        }

        .stats {
            display: flex;
            gap: 60px;
            margin-top: 50px;
        }

        .stat-item {
            text-align: left;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 900;
            color: #0a1f3d;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Hero Image Animation */
        .hero-image {
            animation: float 6s ease-in-out infinite;
        }

        .hero-image img {
            transition: transform 0.3s ease;
        }

        .hero-image img:hover {
            transform: scale(1.05);
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* New Arrivals Section */
        .new-arrivals {
            padding: 60px 50px;
            background-color: #0a1f3d;
        }

        .section-title {
            text-align: center;
            font-size: 36px;
            font-weight: 900;
            color: #ffc107;
            margin-bottom: 40px;
        }

        .product-container {
            background-color: #fff;
            border-radius: 20px;
            padding: 40px;
            max-width: 1300px;
            margin: 0 auto 40px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 30px;
        }

        .product-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .product-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: #ffc107;
        }



        .view-all-btn {
            display: block;
            margin: 30px auto 0;
            padding: 12px 40px;
            background-color: #0a1f3d;
            border: 2px solid #0a1f3d;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            color: #ffc107;
            cursor: pointer;
            transition: all 0.3s;
        }

        .view-all-btn:hover {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }

        /* Top Selling View All Button - Gold background for better contrast on dark background */
        .top-selling-view-all {
            background-color: #ffc107;
            border: 2px solid #ffc107;
            color: #000;
        }

        .top-selling-view-all:hover {
            background-color: #0a1f3d;
            border-color: #0a1f3d;
            color: #ffc107;
        }

        /* Products Grid Section */
        .products-section {
            padding: 60px 50px;
            background-color: #0a1f3d;
        }

        /* Custom Design Section */
        .custom-design {
            padding: 60px 50px;
            background-color: #0a1f3d;
        }

        .design-blue-container {
            background-color: #fff;
            border-radius: 20px;
            padding: 40px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .custom-design h2 {
            text-align: center;
            font-size: 28px;
            font-weight: 900;
            color: #ffc107;
            margin-bottom: 40px;
        }

        .design-container {
            display: flex;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
            align-items: center;
        }

        .jersey-preview {
            flex: 1;
            background-color: #f8f9fa;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
        }

        .jersey-preview img {
            width: 100%;
            max-width: 350px;
            height: auto;
        }

        .customization-panel {
            flex: 1;
            background-color: #f8f9fa;
            padding: 40px;
            border-radius: 15px;
        }

        .jersey-options {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .jersey-option {
            width: 80px;
            height: 80px;
            border: 2px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            overflow: hidden;
        }

        .jersey-option img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .jersey-option.active {
            border-color: #ffc107;
        }

        .option-group {
            margin-bottom: 25px;
        }

        .option-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .color-options {
            display: flex;
            gap: 10px;
        }

        .color-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #ddd;
            cursor: pointer;
            transition: all 0.3s;
        }

        .color-btn.active {
            border-color: #000;
            border-width: 3px;
        }

        .size-options {
            display: flex;
            gap: 10px;
        }

        .size-btn {
            padding: 10px 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .size-btn:hover {
            border-color: #ffc107;
        }

        .size-btn.active {
            background-color: #000;
            color: #fff;
            border-color: #000;
        }

        /* Footer */
        footer {
            background-color: #000;
            color: #fff;
            padding: 60px 50px 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            max-width: 1200px;
            margin: 0 auto 50px;
        }

        .footer-section h3 {
            color: #ffc107;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-section h4 {
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            margin: 20px 0 10px 0;
        }

        /* Store Locations Styling */
        .store-locations {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .store-item {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            border-left: 3px solid #ffc107;
        }

        .store-item p {
            color: #ccc;
            font-size: 14px;
            line-height: 1.6;
            margin: 8px 0;
        }

        .store-item strong {
            color: #ffc107;
        }

        /* Contact Info Styling */
        .contact-info {
            margin-bottom: 30px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .contact-item i {
            color: #ffc107;
            font-size: 16px;
            width: 20px;
        }

        .contact-item span {
            color: #ccc;
            font-size: 14px;
        }

        /* Social Section */
        .social-section h4 {
            color: #ffc107;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            background-color: #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
        }

        .social-icon:hover {
            background-color: #ffc107;
            color: #000;
            transform: translateY(-3px);
        }

        /* Footer Bottom */
        .footer-bottom {
            border-top: 1px solid #333;
            padding-top: 30px;
            text-align: center;
        }

        .copyright {
            max-width: 600px;
            margin: 0 auto;
        }

        .copyright p {
            color: #ccc;
            font-size: 13px;
            margin: 5px 0;
            line-height: 1.5;
        }

        .chat-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #000;
            color: #fff;
            padding: 15px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            transition: all 0.3s;
            width: 55px;
            height: 55px;
        }

        .chat-btn:hover {
            background-color: #ffc107;
            color: #000;
            transform: translateY(-3px);
        }

        /* Profile Popup */
        .profile-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 1000;
            backdrop-filter: blur(2px);
        }

        .profile-popup-content {
            position: absolute;
            top: 80px;
            right: 50px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 280px;
            overflow: hidden;
        }

        .profile-header {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .profile-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid #ffc107;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }

        .profile-email {
            font-size: 12px;
            color: #666;
        }

        .profile-menu {
            padding: 10px 0;
        }

        .profile-menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.3s;
            color: #555;
        }

        .profile-menu-item:hover {
            background-color: #f8f9fa;
        }

        .profile-menu-item.active {
            background-color: #fff3cd;
            color: #856404;
            border-left: 3px solid #ffc107;
        }

        .profile-menu-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        .profile-menu-item span {
            font-size: 14px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }

            .hero-content h1 {
                font-size: 32px;
            }

            .design-container {
                flex-direction: column;
            }

            .design-blue-container {
                padding: 20px;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 15px;
            }

            .product-container {
                padding: 20px;
            }

            .new-arrivals .product-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 15px;
            }

            /* Profile Popup Mobile Responsiveness */
            .profile-popup-content {
                position: absolute;
                top: 60px;
                right: 10px;
                left: 10px;
                width: auto;
                max-width: 280px;
            }

            header {
                padding: 15px 20px;
                position: relative;
                flex-wrap: wrap;
                justify-content: space-between;
            }

            .main-nav {
                display: none; /* Hide navigation on mobile for space */
            }

            .search-container {
                display: none; /* Hide search on mobile for space */
            }

            /* Mobile header adjustments */
            .logo {
                order: 1;
                flex: 1;
            }

            header > div:last-child {
                order: 2;
                margin-left: auto;
            }
        }

        @media (max-width: 768px) {
            /* Footer Mobile Responsive */
            .footer-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            footer {
                padding: 40px 20px 20px;
            }

            .footer-section h3 {
                font-size: 18px;
                margin-bottom: 20px;
            }

            .store-item {
                padding: 15px;
            }

            .contact-item {
                margin-bottom: 12px;
            }

            .social-icons {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            /* Extra small screens */
            .profile-popup-content {
                top: 50px;
                right: 5px;
                left: 5px;
                max-width: none;
            }

            .top-banner {
                padding: 8px 15px;
                font-size: 12px;
            }

            /* Footer extra small screens */
            .store-item {
                padding: 12px;
                margin-bottom: 15px;
            }

            .store-item h4 {
                font-size: 14px;
                margin: 15px 0 8px 0;
            }

            .store-item p {
                font-size: 13px;
            }

            .contact-item span {
                font-size: 13px;
            }

            .social-icon {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }

            .copyright p {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Banner -->
    <div class="top-banner">
        <a href="{{ route('login') }}">Sign In now!</a>
        <span class="close-btn">✕</span>
    </div>

    <!-- Header -->
    <header>
        <div class="logo">
            <div class="logo-circle"></div>
            <div class="logo-text-container">
                <span class="logo-text">LGI STORE</span>
                <span class="logo-tagline">PEDULI KUALITAS, BUKAN KUANTITAS</span>
            </div>
        </div>
        
        <nav class="main-nav">
            <a href="#">Topi</a>
            <a href="#">Kaos</a>
            <a href="#">Sablon</a>
            <a href="#">Jaket</a>
            <a href="#">Jersey</a>
            <a href="#">Tas</a>
        </nav>

        <div class="search-container">
            <i class="fas fa-search" style="color: #999; position: absolute; left: 20px; top: 50%; transform: translateY(-50%);"></i>
            <input type="text" class="search-box" placeholder="Search for products...">
        </div>
        
        <div style="display: flex; gap: 20px;">
            <i class="fas fa-shopping-cart" style="font-size: 22px; cursor: pointer; color: #fff;"></i>
            <i id="profile-icon" class="fas fa-user-circle" style="font-size: 22px; cursor: pointer; color: #fff;"></i>
        </div>
    </header>

    <!-- Profile Popup -->
    <div id="profile-popup" class="profile-popup">
        <div class="profile-popup-content">
            <div class="profile-header">
                <img src="https://via.placeholder.com/60x60/ffc107/000?text=U" alt="Profile Picture" class="profile-avatar">
                <div class="profile-info">
                    <div class="profile-name">John Doe</div>
                    <div class="profile-email">john.doe@example.com</div>
                </div>
            </div>
            <div class="profile-menu">
                <div class="profile-menu-item active">
                    <i class="fas fa-user"></i>
                    <span>Profil Saya</span>
                </div>
                <div class="profile-menu-item">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </div>
                <div class="profile-menu-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>CARI STYLE JERSEY FAVORITMU</h1>
            <p>Bukan cuma jersey, topi, celana, dan lain-lain juga ada loh. Kamu juga bisa kustom mereka tanpa minimal pembelian. Buruan, daftarkan akunmu dan checkout sekarang!</p>
            <a href="{{ route('login') }}" class="shop-btn-link"><button class="shop-btn">Shop Now</button></a>

            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">2</div>
                    <div class="stat-label">Cabang</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">200+</div>
                    <div class="stat-label">Custom Design</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1,000+</div>
                    <div class="stat-label">Pembelian</div>
                </div>
            </div>
        </div>

        <div class="hero-image">
            <img src="https://i.pinimg.com/originals/e9/04/53/e904533ed00df550bb4fc87064217f18.png" alt="Minimalist Jersey" style="width: 400px; height: auto; display: block; margin: 0 auto;">
        </div>
    </section>

    <!-- New Arrivals -->
    <section class="new-arrivals">
        <h2 class="section-title">NEW ARRIVALS</h2>
        <div class="product-container">
            <div class="product-grid">
                <div class="product-card">
                    <img src="https://i.pinimg.com/1200x/3e/6b/f5/3e6bf5378b6ae4d43263dfb626d37588.jpg" alt="Product Image" class="product-image">
                    <div class="product-name">T-shirt with Tape Details</div>
                    <div class="product-price">Rp 60.000</div>
                </div>

                <div class="product-card">
                    <img src="https://i.pinimg.com/736x/45/4c/92/454c92dc87e9774bed336c9ea9d132ed.jpg" alt="Product Image" class="product-image">
                    <div class="product-name">Skinny Fit Jeans</div>
                    <div class="product-price">Rp 60.000</div>
                </div>

                <div class="product-card">
                    <img src="https://i.pinimg.com/736x/4f/7e/bf/4f7ebfdc234afefe71d5f4a8ec8ba408.jpg" alt="Product Image" class="product-image">
                    <div class="product-name">Checkered Shirt</div>
                    <div class="product-price">Rp 60.000</div>
                </div>

                <div class="product-card">
                    <img src="https://i.pinimg.com/736x/69/92/5a/69925a28d7d2cbb1caacb62ad74c4206.jpg" alt="Product Image" class="product-image">
                    <div class="product-name">Sleeve Striped T-shirt</div>
                    <div class="product-price">Rp 60.000</div>
                </div>

                <div class="product-card">
                    <img src="https://i.pinimg.com/1200x/e0/62/b6/e062b626075c2d7191d6dbee36b5b697.jpg" alt="Product Image" class="product-image">
                    <div class="product-name">Classic Polo Shirt</div>
                    <div class="product-price">Rp 75.000</div>
                </div>
            </div>
            <button class="view-all-btn">View All</button>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-section">
        <h2 class="section-title">TOP SELLING</h2>
        <div class="product-grid">
            <div class="product-card">
                <img src="https://i.pinimg.com/736x/80/e1/c7/80e1c79f5a22e663bf5b5507e9ef293c.jpg" alt="Product Image" class="product-image">
                <div class="product-name">Formal Shirt</div>
                <div class="product-price">Rp 80.000</div>
            </div>

            <div class="product-card">
                <img src="https://i.pinimg.com/1200x/e1/4f/84/e14f84017368f18fba24c18f4da36fef.jpg" alt="Product Image" class="product-image">
                <div class="product-name">Popular hat</div>
                <div class="product-price">Rp 100.000</div>
            </div>

            <div class="product-card">
                <img src="https://i.pinimg.com/1200x/fd/20/e2/fd20e257b019babc8014be0dea3766c6.jpg" alt="Product Image" class="product-image">
                <div class="product-name">Sneakers</div>
                <div class="product-price">Rp 200.000</div>
            </div>

            <div class="product-card">
                <img src="https://i.pinimg.com/1200x/e5/89/6c/e5896cd98ae66ee8580699d0425d1dd5.jpg" alt="Product Image" class="product-image">
                <div class="product-name">Denim Jeans</div>
                <div class="product-price">Rp 150.000</div>
            </div>

            <div class="product-card">
                <img src="https://i.pinimg.com/736x/2f/85/1a/2f851aebd8b3fac0b0dd60d304dd1578.jpg" alt="Product Image" class="product-image">
                <div class="product-name">Casual Jacket</div>
                <div class="product-price">Rp 180.000</div>
            </div>
        </div>
        <button class="view-all-btn top-selling-view-all">View All</button>
    </section>

    <!-- Custom Design Section -->
    <section class="custom-design">
        <h2>DESIGN BAJUMU SENDIRI</h2>
        <div class="design-blue-container">
            <div class="design-container">
            <div class="jersey-preview">
                <img src="https://i.pinimg.com/1200x/e0/62/b6/e062b626075c2d7191d6dbee36b5b697.jpg" alt="Custom Jersey" class="jersey-image">
            </div>

            <div class="customization-panel">
                <div class="jersey-options">
                    <div class="jersey-option">
                        <div style="width: 100%; height: 100%; background: #333; display: flex; align-items: center; justify-content: center; color: #fff;">
                            <i class="fas fa-tshirt" style="font-size: 30px;"></i>
                        </div>
                    </div>
                    <div class="jersey-option active">
                        <div style="width: 100%; height: 100%; background: #dc143c; display: flex; align-items: center; justify-content: center; color: #fff;">
                            <i class="fas fa-tshirt" style="font-size: 30px;"></i>
                        </div>
                    </div>
                    <div class="jersey-option">
                        <div style="width: 100%; height: 100%; background: #fff; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; color: #333;">
                            <i class="fas fa-tshirt" style="font-size: 30px;"></i>
                        </div>
                    </div>
                </div>

                <div class="option-group">
                    <div class="option-label">Select Color</div>
                    <div class="color-options">
                        <div class="color-btn" style="background-color: #8b6f47;"></div>
                        <div class="color-btn active" style="background-color: #333;"></div>
                        <div class="color-btn" style="background-color: #1e3a5f;"></div>
                    </div>
                </div>

                <div class="option-group">
                    <div class="option-label">Choose Size</div>
                    <div class="size-options">
                        <button class="size-btn">Small</button>
                        <button class="size-btn">Medium</button>
                        <button class="size-btn active">Large</button>
                        <button class="size-btn">X-Large</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>📍 LOKASI & JAM OPERASIONAL</h3>
                <div class="store-locations">
                    <div class="store-item">
                        <h4>🏢 Toko Pusat (LGI SPORT)</h4>
                        <p>Jl. Bumi Manti 2 (Sebelah Musholla Al Huda) Kampung Baru Unila, Labuhan Ratu, Bandarlampung</p>
                        <p>⏰ <strong>Buka:</strong> Setiap hari 08.00 - 21.00 WIB (Minggu: 12.00 - 21.00)</p>
                        <p>🗺️ <strong>Google Maps:</strong> "LGI SPORT"</p>
                    </div>

                    <div class="store-item">
                        <h4>🏪 Toko Cabang (LGI STORE)</h4>
                        <p>Jl. Ratu Dibalau (Seberang Panglong Kayu Jaya Abadi) Way Kandis, Bandarlampung</p>
                        <p>⏰ <strong>Buka:</strong> Setiap hari 08.00 - 21.00 WIB (Sabtu: tutup 17.00, Minggu: 12.00 - 21.00)</p>
                        <p>🗺️ <strong>Google Maps:</strong> "LGI STORE"</p>
                    </div>
                </div>
            </div>

            <div class="footer-section">
                <h3>📞 HUBUNGI KAMI</h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span><strong>Email:</strong> info@example.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span><strong>Phone:</strong> +62 123 4567 8900</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span><strong>Customer Service:</strong> 24/7 Available</span>
                    </div>
                </div>

                <div class="social-section">
                    <h4>📱 Ikuti Kami</h4>
                    <div class="social-icons">
                        <div class="social-icon"><i class="fab fa-twitter"></i></div>
                        <div class="social-icon"><i class="fab fa-facebook-f"></i></div>
                        <div class="social-icon"><i class="fab fa-tiktok"></i></div>
                        <div class="social-icon"><i class="fab fa-instagram"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="copyright">
                <p>© 2024 LGI STORE - Temukan Kami di Google Maps</p>
                <p>Melayani dengan sepenuh hati untuk kepuasan pelanggan</p>
            </div>
        </div>
    </footer>

    <!-- Chat Button -->
    <div class="chat-btn">
        <i class="fas fa-comment"></i>
    </div>

    <script>
        // Close banner
        document.querySelector('.close-btn').addEventListener('click', function() {
            document.querySelector('.top-banner').style.display = 'none';
        });

        // Size selection
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Color selection
        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Jersey option selection
        document.querySelectorAll('.jersey-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.jersey-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Chat button
        document.querySelector('.chat-btn').addEventListener('click', function() {
            alert('fitur chat segera tibaa ');
        });

        // Profile popup
        const profileIcon = document.getElementById('profile-icon');
        const profilePopup = document.getElementById('profile-popup');

        // Function to toggle popup
        function toggleProfilePopup() {
            const isVisible = profilePopup.style.display === 'block';
            profilePopup.style.display = isVisible ? 'none' : 'block';
        }

        // Function to close popup
        function closeProfilePopup() {
            profilePopup.style.display = 'none';
        }

        // Toggle popup when clicking profile icon
        profileIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleProfilePopup();
        });

        // Close popup when clicking outside
        document.addEventListener('click', function(e) {
            if (profilePopup.style.display === 'block') {
                // Check if click is outside both popup and profile icon
                if (!profilePopup.contains(e.target) && e.target !== profileIcon) {
                    closeProfilePopup();
                }
            }
        });

        // Prevent popup from closing when clicking inside it
        profilePopup.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Close popup on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && profilePopup.style.display === 'block') {
                closeProfilePopup();
            }
        });

    </script>
</body>
</html>