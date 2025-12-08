@extends('layout')

@section('content')
{{-- Pastikan header tetap menggunakan warna Maroon --}}
<div class="container-fluid py-5 poliklinik-header-bg mb-4">
    <div class="text-center">
        <h1 class="display-4 text-white fw-bolder" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
            <i class="fas fa-stethoscope fa-beat me-3 text-white"></i> 
            PILIH LAYANAN POLIKLINIK
        </h1>
        <p class="lead text-white opacity-90">Jelajahi berbagai layanan spesialis yang kami sediakan.</p>
    </div>
</div>

<div class="container">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('poliklinik.daftar_kunjungan') }}" class="btn btn-primary">
            <i class="fas fa-list"></i> Daftar Kunjungan
        </a>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
        
        {{-- 1. Poli Umum (Biru) --}}
        <div class="col">
            <a href="{{ route('pemeriksaan.create') }}?poli=umum"
               class="text-decoration-none d-block h-100">
                
                <div class="card poli-card h-100 shadow-lg border-0 rounded-4 overflow-hidden bg-white">
                    <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                        
                        <div>
                            {{-- Logo untuk Umum: fa-user-md dengan warna Biru --}}
                            <div class="icon-circle mx-auto mb-4 p-3 rounded-circle shadow-sm" style="background-color: #3498db; color: white;">
                                <i class="fas fa-user-md fa-3x"></i>
                            </div>

                            <h4 class="card-title fw-bold fs-4 mb-2 text-dark">Poli Umum</h4>
                            <p class="card-text small opacity-90 text-secondary">Pelayanan kesehatan umum, konsultasi, dan pengobatan bagi semua usia.</p>
                        </div>

                        <div class="mt-4">
                            {{-- Tombol Maroon --}}
                            <span class="btn btn-primary btn-lg w-100 rounded-pill fw-bold hover-effect-maroon">
                                Masuk Poliklinik
                                <i class="fas fa-arrow-right ms-2"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- 2. Poli Gigi & Mulut (Hijau) --}}
        <div class="col">
            <a href="{{ route('pemeriksaan.create') }}?poli=gigi"
               class="text-decoration-none d-block h-100">
                
                <div class="card poli-card h-100 shadow-lg border-0 rounded-4 overflow-hidden bg-white">
                    <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                        
                        <div>
                            {{-- Logo untuk Gigi: fa-tooth dengan warna Hijau --}}
                            <div class="icon-circle mx-auto mb-4 p-3 rounded-circle shadow-sm" style="background-color: #2ecc71; color: white;">
                                <i class="fas fa-tooth fa-3x"></i>
                            </div>

                            <h4 class="card-title fw-bold fs-4 mb-2 text-dark">Poli Gigi & Mulut</h4>
                            <p class="card-text small opacity-90 text-secondary">Perawatan gigi, scaling, penambalan, pencabutan, dan konsultasi kesehatan mulut.</p>
                        </div>

                        <div class="mt-4">
                            {{-- Tombol Maroon --}}
                            <span class="btn btn-primary btn-lg w-100 rounded-pill fw-bold hover-effect-maroon">
                                Masuk Poliklinik
                                <i class="fas fa-arrow-right ms-2"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- 3. Poli KIA / KB (Oranye) --}}
        <div class="col">
            <a href="{{ route('pemeriksaan.create') }}?poli=kia"
               class="text-decoration-none d-block h-100">
                
                <div class="card poli-card h-100 shadow-lg border-0 rounded-4 overflow-hidden bg-white">
                    <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                        
                        <div>
                            {{-- Logo untuk KIA/KB: fa-baby dengan warna Oranye --}}
                            <div class="icon-circle mx-auto mb-4 p-3 rounded-circle shadow-sm" style="background-color: #f39c12; color: white;">
                                <i class="fas fa-baby fa-3x"></i>
                            </div>

                            <h4 class="card-title fw-bold fs-4 mb-2 text-dark">Poli KIA / KB</h4>
                            <p class="card-text small opacity-90 text-secondary">Ibu hamil, nifas, imunisasi bayi, dan program keluarga berencana (KB).</p>
                        </div>

                        <div class="mt-4">
                            {{-- Tombol Maroon --}}
                            <span class="btn btn-primary btn-lg w-100 rounded-pill fw-bold hover-effect-maroon">
                                Masuk Poliklinik
                                <i class="fas fa-arrow-right ms-2"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Menggunakan variabel warna Maroon dari layout untuk konsistensi */
    :root {
        --primary-maroon: #800000;
        --light-maroon: #a00000;
        --dark-maroon: #5c0000;
    }
    
    .poliklinik-header-bg {
        background: linear-gradient(135deg, var(--primary-maroon) 0%, var(--light-maroon) 100%); 
        border-radius: 0 0 15px 15px;
        padding-top: 4rem !important;
        padding-bottom: 4rem !important;
    }

    /* Styling Kartu Poliklinik */
    .poli-card {
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        transform: translateY(0);
        border: 1px solid rgba(0,0,0,0.08);
    }

    .poli-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 18px 36px rgba(0,0,0,0.2) !important;
        z-index: 10;
        cursor: pointer;
    }

    /* Efek untuk lingkaran ikon */
    .icon-circle {
        transition: transform 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        font-size: 2.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .poli-card:hover .icon-circle {
        transform: scale(1.1);
    }

    /* Efek untuk tombol Maroon (btn-primary harus maroon) */
    .btn-primary {
        background-color: var(--primary-maroon) !important;
        border-color: var(--primary-maroon) !important;
    }
    .btn-primary:hover {
        background-color: var(--dark-maroon) !important;
        border-color: var(--dark-maroon) !important;
    }
    .hover-effect-maroon {
        transition: all 0.3s ease;
    }
    .hover-effect-maroon:hover {
        background-color: var(--dark-maroon) !important;
        border-color: var(--dark-maroon) !important;
        color: white !important; 
    }
    
    .fa-beat {
        animation: fa-beat 2s ease infinite;
    }
    @keyframes fa-beat {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" integrity="sha512-RIT8aXlO/C2b404wH+iQ6yA5W5o02Kx9Hl733/Q5P5w/H2H5g4uGk4Jz1eG9R5Sg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush