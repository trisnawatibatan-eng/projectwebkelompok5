<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Klinik Pratama</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #fafafa, #ffffff, #f5f5f5);
            font-family: 'Poppins', sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 22px;
        }

        .login-card {
            background: #ffffff;
            padding: 35px 32px;
            border-radius: 18px;
            box-shadow: 0 12px 40px rgba(128, 0, 0, 0.25);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { transform: translateY(20px); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }

        .login-title {
            color: #800000;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 15px;
            background: rgba(128, 0, 0, 0.1);
            color: #800000;
        }

        .btn-login {
            background: #800000;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px;
            transition: .2s;
        }

        .btn-login:hover {
            background: #a01f1f;
            color: #fff;
            transform: translateY(-2px);
        }

        .form-control:focus {
            border-color: #800000;
            box-shadow: 0 0 4px rgba(128, 0, 0, 0.35);
        }

        .footer-text {
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="login-wrapper">
    <div class="login-card">

        <div class="icon-circle">
            <i class="bi bi-heart-fill" style="font-size: 2rem;"></i>
        </div>

        <h4 class="text-center login-title">
            <i class="bi bi-lock-fill"></i> Login Klinik Pratama
        </h4>

        <!-- Alert -->
        @if(session('error'))
            <div class="alert alert-danger py-2 mt-3">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success py-2 mt-3">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="mt-3">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username (Email)</label>
                <input type="text" name="username" class="form-control"
                       placeholder="Masukkan email" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn btn-login w-100">Masuk</button>
        </form>

        <div class="footer-text">
            Sistem Informasi Rekam Medis Elektronik<br>
            Klinik Pratama
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
