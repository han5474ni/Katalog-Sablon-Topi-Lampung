<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGI Store - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            background-color: #0a1f3d;
            padding: 15px 50px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .admin-login {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            border-left: 2px solid rgba(255, 255, 255, 0.3);
            padding-left: 20px;
        }

        .admin-login i {
            font-size: 16px;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            background-color: #fff;
            border-radius: 50%;
            border: 3px solid #0a1f3d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo-circle i {
            font-size: 40px;
            color: #0a1f3d;
        }

        .logo-text {
            font-size: 42px;
            font-weight: 900;
            color: #ffc107;
            letter-spacing: 2px;
        }

        .logo-tagline {
            font-size: 11px;
            color: #333;
            font-weight: 600;
            margin-top: 5px;
            letter-spacing: 1px;
        }

        .login-card {
            background-color: #fff;
            border-radius: 20px;
            padding: 50px 60px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            color: #000;
            margin-bottom: 10px;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            background-color: #fff;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
        }

        .checkbox-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #ffc107;
        }

        .checkbox-wrapper label {
            font-size: 14px;
            color: #333;
            cursor: pointer;
            user-select: none;
        }

        .forgot-password {
            color: #ffc107;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: #ffb300;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background-color: #ffc107;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            color: #000;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .login-btn:hover {
            background-color: #ffb300;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
        }

        .signup-text {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .signup-text a {
            color: #ffc107;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .signup-text a:hover {
            color: #ffb300;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: #999;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #e0e0e0;
        }

        .divider::before {
            margin-right: 15px;
        }

        .divider::after {
            margin-left: 15px;
        }

        .google-btn {
            width: 100%;
            padding: 14px;
            background-color: #fff;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .google-btn:hover {
            border-color: #ccc;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .google-icon {
            width: 24px;
            height: 24px;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle input {
            padding-right: 50px;
        }

        .toggle-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .login-card {
                padding: 40px 30px;
            }

            .logo-text {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="#" class="admin-login">
            <i class="fas fa-lock"></i>
            <span>Admin Login</span>
        </a>
    </div>

    <div class="container">
        <div class="logo-section">
            <div class="logo-circle">
                <i class="fas fa-glasses"></i>
            </div>
            <div class="logo-text">LGI STORE</div>
            <div class="logo-tagline">PEDULI KUALITAS, BUKAN KUANTITAS</div>
        </div>

        <div class="login-card">
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Nama Pengguna / Email</label>
                    <input 
                        type="text" 
                        id="email" 
                        name="email" 
                        placeholder="nama.email.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••••"
                            required
                        >
                        <i class="fas fa-eye toggle-icon" id="togglePassword"></i>
                    </div>
                </div>

                <div class="checkbox-group">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Tetap Masuk</label>
                    </div>
                    <a href="#" class="forgot-password">Lupa Kata Sandi?</a>
                </div>

                <button type="submit" class="login-btn">Masuk</button>

                <div class="signup-text">
                    Kamu Tidak Memiliki Akun? <a href="#">Daftar</a>
                </div>

                <div class="divider">atau</div>

                <button type="button" class="google-btn" id="googleLogin">
                    <svg class="google-icon" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Masuk Melalui Google
                </button>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Login form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;

            console.log('Login attempt:', { email, password, remember });
            alert('Login berhasil! (Demo)');
        });

        // Google login
        document.getElementById('googleLogin').addEventListener('click', function() {
            alert('Mengarahkan ke Google Sign In... (Demo)');
        });
    </script>
</body>
</html>