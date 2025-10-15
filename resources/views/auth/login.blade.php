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

                <div class="social-login">
                    <button type="button" class="google-btn" onclick="window.location.href='{{ route('google.login') }}'">
                        <svg class="google-icon" viewBox="0 0 24 24">
                            <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                        </svg>
                        Masuk Melalui Google
                    </button>
                </div>
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