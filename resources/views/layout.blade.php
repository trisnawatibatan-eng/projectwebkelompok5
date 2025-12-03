<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pendaftaran Puskesmas Pratama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #51c8ffff, #2df3f3ff);
            font-family: 'Poppins', sans-serif;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 240px;
            background-color: #018ca5ff;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1rem;
            position: fixed;
            height: 100vh;
        }
        .sidebar h2 {
            font-weight: 600;
            font-size: 1.4rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        .sidebar a {
            color: #e1cbd5ff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 10px;
            transition: all 0.2s;
            margin-bottom: 0.5rem;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #11ffb890;
            color: #fff;
        }
        .content {
            margin-left: 260px;
            padding: 2rem;
            width: 100%;
        }
        .btn-primary {
            background-color: #8f8d22ff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #faaf0089;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header text-center mb-4">
            <img src="" alt="" width="80" class="rounded-circle mb-2">
            <h2>➕PUSKESMAS PRATAMA</h2>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('data.master') }}" class="{{ request()->routeIs('data.master') ? 'active' : '' }}">Data Master</a>
            <a href="{{ route('pasien.baru') }}" class="{{ request()->routeIs('pasien.baru') ? 'active' : '' }}">Pasien Baru</a>
            <a href="{{ route('pasien.lama') }}" class="{{ request()->routeIs('pasien.lama') ? 'active' : '' }}">Pasien Lama</a>
            <a href="{{ route('pemeriksaan.create') }}" class="{{ request()->routeIs('pemeriksaan.create') ? 'active' : '' }}">Pemeriksaan Dokter</a>
            <hr style="border-color: #694758ff;">
            <a href="{{ route('logout') }}">Logout</a>
        </div>
    </div>

    <div class="content">
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            </script>
        @endif

        @if (session('info'))
            <script>
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info') }}',
                    showConfirmButton: true
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}'
                });
            </script>
        @endif

        @yield('content')
    </div>
</body>
</html>