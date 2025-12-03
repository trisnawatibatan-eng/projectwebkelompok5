@extends('layout')

@section('title', 'Beranda')

@section('content')

<div class="container mt-4">

    <nav class="navbar navbar-expand-lg navbar-dark"
        style="background-color: #800000; border-radius: 0.75rem; box-shadow: 0 2px 6px rgba(0,0,0,0.15); margin-bottom: 1.5rem; padding: 0.75rem 1rem;">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold text-white" href="#">Klinik Pratama Mulia</a>
        </div>
    </nav>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="container-fluid">

                <div class="mt-4 mb-3 text-center">
                    <h2 class="fw-semibold text-maroon">Sistem Rekam Medis Elektronik Klinik Pratama</h2>
                    <hr class="mx-auto" style="width: 60px; height: 3px; background-color: #800000; border: none;">
                </div>

                <div class="alert d-flex align-items-center justify-content-between shadow-sm rounded-3 p-3"
                    style="background-color: #f2dede; color: #800000;">
                    <div>
                        <h5 class="mb-1 fw-semibold">👋 Halo, Admin!</h5>
                        <p class="mb-0 small text-secondary">
                            Selamat datang di Aplikasi Rekam Medis Elektronik <strong>Klinik Pratama</strong>.
                        </p>
                    </div>
                    <img src="https://cdn-icons-png.flaticon.com/512/2922/2922510.png" width="55" alt="Admin Icon">
                </div>

                <div class="row mt-4 g-4">
                    
                    <div class="col-md-4 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 hover-card">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3 text-maroon">
                                    <i class="bi bi-people-fill" style="font-size: 1.8rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold text-muted mb-1">Total Pasien</h6>
                                    <h4 class="fw-bold mb-0 text-maroon">
                                        {{ $totalPasien ?? 0 }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 hover-card">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3 text-maroon">
                                    <i class="bi bi-gender-female" style="font-size: 1.8rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold text-muted mb-1">Perempuan</h6>
                                    <h4 class="fw-bold mb-0 text-maroon">
                                        {{ $perempuan ?? 0 }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 hover-card">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3 text-maroon">
                                    <i class="bi bi-gender-male" style="font-size: 1.8rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold text-muted mb-1">Laki-Laki</h6>
                                    <h4 class="fw-bold mb-0 text-maroon">
                                        {{ $lakiLaki ?? 0 }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-5 border-0 shadow-sm rounded-4">
                    <div class="card-header text-white fw-semibold" style="background-color: #800000;">
                        Informasi Sistem
                    </div>
                    <div class="card-body">
                        <p class="text-secondary mb-2 small">
                            Aplikasi RME ini membantu tenaga medis mencatat data pasien dan mengelola rekam medis dengan efisien di
                            <strong>Klinik Pratama</strong>.
                        </p>
                        <ul class="small mb-0">
                            <li>Menu <strong>Pendaftaran</strong> untuk input pasien baru/lama.</li>
                            <li>Menu <strong>Poliklinik</strong> untuk mencatat hasil pemeriksaan masing-masing poli.</li>
                            <li>Menu <strong>Apotek</strong> untuk membuat resep obat pasien.</li>
                            <li>Menu <strong>Kasir</strong> untuk memcatat pembayaran pasien.</li>
                            <li>Menu <strong>Resume Medis</strong> untuk ringkasan rekam medis pasien.</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<style>
    .text-maroon {
        color: #800000 !important;
    }

    .hover-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .hover-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(128, 0, 0, 0.25);
    }

    @media (max-width: 768px) {
        .navbar {
            text-align: center;
        }
        .alert {
            flex-direction: column;
            text-align: center;
        }
        .alert img {
            margin-top: 10px;
        }
    }
</style>

@endsection