<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fdfdfdff;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #4ed2f3ff;
            color: black;
            padding: 20px 15px;
        }
        .sidebar h4 {
            font-size: 1.2rem;
            margin-top: 10px;
        }
        .sidebar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
        .nav-link {
            color: #020202cc;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #0e0d0dff;
            background-color: #4aa04bff;
            border-radius: 8px;
        }
        .content {
            padding: 30px;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            font-weight: 600;
            border: none;
            width: 100%;
            border-radius: 8px;
            padding: 10px 0;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #b52a37;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar d-flex flex-column align-items-center">
            <!-- Logo dan Nama Rumah Sakit -->
            <img src="{{ asset('image/logo.jpg') }}" alt="Logo" width="500" height="500">
            <h4 class="mt-2 text-center">Sehat Mandiri</h4>
            <hr class="border border-light w-100">

            <!-- Navigasi -->
            <nav class="nav flex-column w-100">
                <a class="nav-link" href="/">🏠 Dashboard</a>

                <!-- Registrasi -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="registrasi" data-bs-toggle="dropdown" aria-expanded="false">
                        📝 Registrasi
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="registrasi">
                        <li><a class="dropdown-item" href="/registrasi/baru">➕ Pasien Baru</a></li>
                        <li><a class="dropdown-item" href="/registrasi/lama">📁 Pasien Lama</a></li>
                    </ul>
                </div>

                <!-- Pemeriksaan -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pemeriksaan" data-bs-toggle="dropdown" aria-expanded="false">
                        🩺 Pemeriksaan
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="pemeriksaan">
                        <li><a class="dropdown-item" href="/pemeriksaan/soap">🩺 SOAP</a></li>
                        <li><a class="dropdown-item" href="/pemeriksaan/resume">📋 Resume</a></li>
                    </ul>
                </div>

                <a class="nav-link" href="/pasien">👨‍⚕️ Master Pasien</a>
                <a class="nav-link" href="/farmasi">💊 Farmasi</a>
                <a class="nav-link" href="/kasir">💰 Kasir</a>
            </nav>

            <!-- Tombol Logout -->
            <form id="logout-form" action="{{ route('logout') }}" method="GET" class="mt-auto w-100">
                @csrf
                <button type="submit" class="logout-btn">🚪 Logout</button>
            </form>
        </div>

        <!-- Konten Utama -->
        <div class="col-md-9 col-lg-10 content">
            <div class="p-3 mb-4 bg-success text-white border-bottom border-light rounded-2 shadow-sm">
                <h2 class="mb-0 fw-semibold">@yield('header')</h2>
            </div>
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
