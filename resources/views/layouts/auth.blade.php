<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Inline styles for LGI login page to ensure UI without Vite build -->
        <style>
        .lgi-login {font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background-color:#f0f2f5;min-height:100vh;display:flex;flex-direction:column}
        .lgi-login .top-bar{background-color:#0a1f3d;padding:15px 50px;display:flex;justify-content:flex-end;align-items:center}
        .lgi-login .admin-login{color:#fff;text-decoration:none;display:flex;align-items:center;gap:8px;font-size:14px;border-left:2px solid rgba(255,255,255,.3);padding-left:20px}
        .lgi-login .admin-login i{font-size:16px}
        .lgi-login>.container{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 20px;width:100%;max-width:none;margin:0}
        .lgi-login .logo-section{text-align:center;margin-bottom:40px}
        .lgi-login .logo-circle{width:80px;height:80px;background-color:#fff;border-radius:50%;border:3px solid #0a1f3d;display:inline-flex;align-items:center;justify-content:center;margin-bottom:15px}
        .lgi-login .logo-circle i{font-size:40px;color:#0a1f3d}
        .lgi-login .logo-text{font-size:42px;font-weight:900;color:#ffc107;letter-spacing:2px}
        .lgi-login .logo-tagline{font-size:11px;color:#333;font-weight:600;margin-top:5px;letter-spacing:1px}
        .lgi-login .login-card{background-color:#fff;border-radius:20px;padding:50px 60px;box-shadow:0 10px 40px rgba(0,0,0,.1);width:100%;max-width:600px}
        .lgi-login .form-group{margin-bottom:25px}
        .lgi-login .form-group label{display:block;font-size:16px;font-weight:600;color:#000;margin-bottom:10px}
        .lgi-login .form-group input{width:100%;padding:15px 20px;border:2px solid #e0e0e0;border-radius:10px;font-size:15px;transition:all .3s;background-color:#fff}
        .lgi-login .form-group input:focus{outline:none;border-color:#ffc107;box-shadow:0 0 0 3px rgba(255,193,7,.1)}
        .lgi-login .form-group input::placeholder{color:#999}
        .lgi-login .checkbox-group{display:flex;justify-content:space-between;align-items:center;margin:20px 0}
        .lgi-login .checkbox-wrapper{display:flex;align-items:center;gap:8px}
        .lgi-login .checkbox-wrapper input[type="checkbox"]{width:18px;height:18px;cursor:pointer;accent-color:#ffc107}
        .lgi-login .checkbox-wrapper label{font-size:14px;color:#333;cursor:pointer;user-select:none}
        .lgi-login .forgot-password{color:#ffc107;text-decoration:none;font-size:14px;font-weight:600;transition:color .3s}
        .lgi-login .forgot-password:hover{color:#ffb300}
        .lgi-login .login-btn{width:100%;padding:16px;background-color:#ffc107;border:none;border-radius:10px;font-size:18px;font-weight:700;color:#000;cursor:pointer;transition:all .3s;margin-top:10px;box-shadow:0 4px 15px rgba(255,193,7,.3)}
        .lgi-login .login-btn:hover{background-color:#ffb300;transform:translateY(-2px);box-shadow:0 6px 20px rgba(255,193,7,.4)}
        .lgi-login .signup-text{text-align:center;margin-top:25px;font-size:14px;color:#666}
        .lgi-login .signup-text a{color:#ffc107;text-decoration:none;font-weight:600;transition:color .3s}
        .lgi-login .signup-text a:hover{color:#ffb300}
        .lgi-login .divider{display:flex;align-items:center;margin:30px 0;color:#999;font-size:14px}
        .lgi-login .divider::before,.lgi-login .divider::after{content:'';flex:1;height:1px;background-color:#e0e0e0}
        .lgi-login .divider::before{margin-right:15px}
        .lgi-login .divider::after{margin-left:15px}
        .lgi-login .google-btn{width:100%;padding:14px;background-color:#fff;border:2px solid #e0e0e0;border-radius:10px;font-size:16px;font-weight:600;color:#333;cursor:pointer;transition:all .3s;display:flex;align-items:center;justify-content:center;gap:12px}
        .lgi-login .google-btn:hover{border-color:#ccc;box-shadow:0 4px 12px rgba(0,0,0,.1)}
        .lgi-login .google-icon{width:24px;height:24px}
        .lgi-login .password-toggle{position:relative}
        .lgi-login .password-toggle input{padding-right:50px}
        .lgi-login .toggle-icon{position:absolute;right:18px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999;font-size:18px}
        @media (max-width:768px){.lgi-login .login-card{padding:40px 30px}.lgi-login .logo-text{font-size:32px}}
        </style>
    </head>
    <body class="font-sans antialiased">
        {{ $slot }}

        <!-- Inline behavior for password visibility toggle (safe no-op on other pages) -->
        <script>
        window.addEventListener('DOMContentLoaded', function () {
            var togglePassword = document.getElementById('togglePassword');
            var passwordInput = document.getElementById('password');
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function () {
                    var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        });
        </script>
    </body>
</html>