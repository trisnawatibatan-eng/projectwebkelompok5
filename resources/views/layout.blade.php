<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Klinik Pratama</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Link Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- PENTING: Untuk menggunakan Auth::check() di view --}}
    @php
        use Illuminate\Support\Facades\Auth;
        $userRole = Auth::check() ? Auth::user()->role : 'guest';
        // Ambil nama pengguna yang login
        $userName = Auth::check() ? Auth::user()->name : 'Guest';
    @endphp


    <style>
        :root {
            --primary-maroon: #800000; /* Warna Utama Merah Maroon */
            --light-maroon: #a00000; /* Maroon yang sedikit lebih terang */
            --dark-maroon: #5c0000;   /* Maroon yang sedikit lebih gelap */
            --sidebar-bg: linear-gradient(180deg, var(--primary-maroon), var(--dark-maroon)); /* Gradien Sidebar Maroon */
            --body-bg: #edf2f8ff; /* Latar Belakang Konten default (tetap abu-abu muda) */
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px; /* Lebar saat ciut */
            --header-height: 65px; /* Ketinggian Header Bar */
            --buffer-padding: 30px; 
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }
        
        /* --- Sidebar Styling --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: width 0.3s;
        }
        
        /* Gaya saat sidebar dicutkan */
        .sidebar-collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar-header {
            padding: 2.5rem 1rem 1.5rem; 
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: padding 0.3s;
        }
        
        .sidebar-header h2 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-top: 15px;
            color: #fff;
        }
        
        /* Sembunyikan Judul saat dicutkan */
        .sidebar-collapsed .sidebar-header h2,
        .sidebar-collapsed .nav-link span,
        .sidebar-collapsed .sidebar-header .text-center {
            display: none;
        }
        
        /* Menu Link Styling */
        .nav-link {
            color: #ffcccc !important;
            padding: 12px 20px !important;
            border-radius: 8px; 
            margin: 8px 15px;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.15) !important;
            transform: none; 
        }
        
        /* Menu aktif yang terpilih */
        .nav-link.active {
            background: rgba(255,255,255,0.25) !important; 
            color: #fff !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
        }
        
        .nav-link i {
            width: 20px;
            font-size: 1.1rem;
            margin-right: 10px;
            transition: margin 0.3s;
        }
        
        /* Pusatkan ikon saat sidebar dicutkan */
        .sidebar-collapsed .nav-link {
            padding: 12px 10px !important;
            text-align: center;
        }
        .sidebar-collapsed .nav-link i {
            margin: 0;
        }
        
        /* Tombol Logout */
        .nav-link.logout-btn {
            background-color: var(--light-maroon) !important;
        }
        .nav-link.logout-btn:hover {
            background-color: var(--primary-maroon) !important;
        }

        /* --- Content Styling --- */
        .content {
            margin-left: var(--sidebar-width);
            padding-top: calc(var(--header-height) + var(--buffer-padding)); 
            padding-right: 0;
            padding-bottom: 0;
            padding-left: 0;
            transition: margin-left 0.3s;
            min-height: 100vh;
        }

        /* Geser konten saat sidebar dicutkan */
        .content-moved {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Header Bar di Atas Konten (Putih) */
        .content-header {
            background-color: #fff; 
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #dee2e6;
            
            position: fixed;
            top: 0;
            right: 0;
            z-index: 999;
            left: var(--sidebar-width); /* Mulai dari akhir sidebar */
            height: var(--header-height);
            transition: left 0.3s;
        }

        /* Geser header saat sidebar dicutkan */
        .content-header-moved {
            left: var(--sidebar-collapsed-width);
        }
        
        /* Isi Halaman Wrapper: Menghilangkan padding top bawaan */
        .page-content-wrapper {
            padding: 30px; 
            padding-top: 0;
        }

        /* Mengubah warna teks 'Admin' di header menjadi maroon */
        .content-header .text-primary {
            color: var(--primary-maroon) !important;
        }
        
        /* Tambahan: Style untuk tombol toggle */
        .btn-toggle-custom {
            color: var(--primary-maroon);
            border-color: #dee2e6;
        }
        .btn-toggle-custom:hover {
            color: white;
            background-color: var(--primary-maroon);
            border-color: var(--primary-maroon);
        }

        /* --- Responsive Design --- */
        @media (max-width: 992px) {
            /* Di layar kecil, sidebar defaultnya sudah kecil, jadi kita pakai logic collapse ini. */
            .sidebar:not(.sidebar-collapsed) {
                 width: 80px; /* Sidebar kecil by default di mobile */
            }
             .content {
                margin-left: 80px;
            }
             .content-header {
                left: 80px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="text-center">
                <i class="bi bi-plus-circle-fill fs-1 text-white mb-2"></i>
            </div>
            <h2>KLINIK PRATAMA</h2>
        </div>

        <nav class="mt-4">
            
            {{-- AKSES ADMIN SAJA --}}
            @if (in_array($userRole, ['admin']))
            <a href="{{ route('dashboard') }}" 
                class="nav-link d-flex align-items-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span class="ms-3">Dashboard</span>
            </a>
            @endif

            {{-- AKSES PETUGAS PENDAFTARAN & ADMIN --}}
            @if (in_array($userRole, ['admin', 'pendaftaran']))
            <a href="{{ route('data.master') }}" 
                class="nav-link d-flex align-items-center {{ request()->routeIs('data.master') ? 'active' : '' }}">
                <i class="bi bi-database-fill"></i>
                <span class="ms-3">Data Master</span>
            </a>

            <a href="{{ route('pasien.baru') }}" 
                class="nav-link d-flex align-items-center {{ request()->routeIs('pasien.baru') ? 'active' : '' }}">
                <i class="bi bi-person-add"></i>
                <span class="ms-3">Pasien Baru</span>
            </a>

            <a href="{{ route('pasien.lama') }}" 
                class="nav-link d-flex align-items-center {{ request()->routeIs('pasien.lama') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span class="ms-3">Pasien Lama</span>
            </a>
            @endif
            
            {{-- AKSES DOKTER/PERAWAT & ADMIN --}}
            @if (in_array($userRole, ['admin', 'dokter']))
            <a href="{{ route('poliklinik') }}" 
                class="nav-link d-flex align-items-center {{ request()->routeIs('poliklinik') ? 'active' : '' }}">
                <i class="bi bi-hospital-fill"></i>
                <span class="ms-3">Poliklinik</span>
            </a>
            @endif
            
            {{-- AKSES KASIR & ADMIN --}}
            @if (in_array($userRole, ['admin', 'kasir']))
            <a href="{{ route('kasir.index') }}" 
                class="nav-link d-flex align-items-center {{ request()->routeIs('kasir.index') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> 
                <span class="ms-3">Kasir</span>
            </a>
            @endif
            
            {{-- AKSES APOTEK & ADMIN --}}
            @if (in_array($userRole, ['admin', 'apotek']))
            <a href="{{ route('apotek.index') }}" 
                class="nav-link d-flex align-items-center {{ request()->routeIs('apotek.index') ? 'active' : '' }}">
                <i class="bi bi-bag-plus-fill"></i> 
                <span class="ms-3">Apotek</span>
            </a>
            @endif

            <hr style="border-color: rgba(255,255,255,0.2); margin: 20px 15px;">

            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="nav-link d-flex align-items-center text-white logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                <span class="ms-3">Logout</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </nav>
    </div>

    <div id="content" class="content">
        
        <div id="content-header" class="content-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                {{-- Tombol Toggle Sidebar --}}
                <button class="btn btn-toggle-custom me-3" id="sidebar-toggle" style="height: 35px; width: 35px; padding: 0;">
                    <i class="bi bi-list fs-5"></i>
                </button>
                
                {{-- JUDUL HALAMAN TELAH DIHILANGKAN --}}
            </div>
            <span class="text-primary fw-semibold">
                {{-- MENGAMBIL NAMA PENGGUNA YANG SEDANG LOGIN --}}
                <i class="bi bi-person-circle me-1"></i> {{ $userName }}
            </span>
        </div>

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: {!! json_encode(session('success')) !!}, 
                    timer: 2000,
                    showConfirmButton: false
                });
            </script>
        @endif
        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: {!! json_encode(session('error')) !!}
                });
            </script>
        @endif
        @if (session('info'))
            <script>
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: {!! json_encode(session('info')) !!}
                });
            </script>
        @endif

        <div class="page-content-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const contentHeader = document.getElementById('content-header');
            const toggleButton = document.getElementById('sidebar-toggle');
            
            // Logika Toggle Sidebar
            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
                content.classList.toggle('content-moved');
                contentHeader.classList.toggle('content-header-moved');

                // Simpan status di localStorage agar status tetap saat berpindah halaman
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    localStorage.setItem('sidebarCollapsed', 'true');
                } else {
                    localStorage.setItem('sidebarCollapsed', 'false');
                }
            });

            // Muat Status Sidebar dari localStorage saat halaman dimuat
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('sidebar-collapsed');
                content.classList.add('content-moved');
                contentHeader.classList.add('content-header-moved');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>