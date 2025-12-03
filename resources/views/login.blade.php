<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | puskesmas pratama </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            /* Warna Primer diubah menjadi Maroon */
            --primary-maroon: #800000; 
            --primary-shadow: rgba(128, 0, 0, 0.5); /* Shadow untuk Maroon */
            --bg-start: #f8f9fa; /* Light Grayish */
            --bg-end: #ffffff;
        }
        body {
            /* Background gradasi yang lebih halus dan netral */
            background: linear-gradient(135deg, var(--bg-start), var(--bg-end));
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif; 
            margin: 0;
        }
        .login-card {
            background: white;
            border-radius: 20px; 
            /* Shadow Maroon yang lembut */
            box-shadow: 0 15px 35px var(--primary-shadow); 
            width: 90%;
            max-width: 420px;
            padding: 2.5rem;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }
        .login-card:hover {
            transform: translateY(-5px); 
        }
        .login-card h3 {
            margin-bottom: 2rem;
            color: var(--primary-maroon); /* Judul menggunakan Maroon */
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .login-card img.logo {
            width: 90px;
            height: 90px;
            border: 4px solid var(--primary-maroon); /* Border logo Maroon */
            object-fit: cover;
        }
        .btn-login {
            background-color: var(--primary-maroon); /* Tombol menggunakan Maroon */
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: background-color 0.3s, transform 0.1s;
        }
        .btn-login:hover {
            background-color: #a02040; /* Maroon sedikit lebih gelap saat hover */
            color: white;
            transform: translateY(-2px);
        }
        .form-control:focus {
            border-color: var(--primary-maroon);
            box-shadow: 0 0 0 0.25rem rgba(128, 0, 0, 0.25); /* Focus shadow Maroon */
        }
        .form-label {
            font-weight: 400;
            color: #555;
            margin-bottom: 0.5rem;
        }
        .text-danger {
            color: var(--primary-maroon) !important; /* Icon di header diubah ke Maroon */
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="" 
         alt="" 
         width="80" 
         class="rounded-circle mb-3">
    <h3></i>❤️Login puskesmas</h3>

    @if(session('error'))
        <div class="alert alert-danger py-2 rounded-lg" role="alert">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success py-2 rounded-lg" role="alert">{{ session('success') }}</div>
    @endif

    <form action="{{ route('login.process') }}" method="POST">
        @csrf
        <div class="mb-3 text-start">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
        </div>
        <div class="mb-4 text-start">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
        </div>

        <button type="submit" class="btn btn-login w-100">Masuk</button>
    </form>

    <p class="mt-4 text-muted" style="font-size: 13px; font-weight: 300;">
        Sistem Rekam Medis Elektronik Klinik Pratama
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>