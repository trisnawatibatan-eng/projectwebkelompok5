@extends('layout')

@section('title', 'Riwayat Pendaftaran - ' . $pasien->nama)

@section('content')
<div class="container">
    <!-- Success Message -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><strong>Sukses!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Kartu Informasi Pasien -->
    <div class="card mb-4 border-maroon">
        <div class="card-header bg-maroon text-white">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-0"><i class="bi bi-person-circle me-2"></i>{{ $pasien->nama }}</h3>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-primary fs-6">No RM: {{ $pasien->no_rm }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>NIK:</strong> {{ $pasien->nik ?? '-' }}</p>
                    <p><strong>Jenis Kelamin:</strong> {{ $pasien->jenis_kelamin ?? '-' }}</p>
                    <p><strong>Tanggal Lahir:</strong> 
                        @if ($pasien->tanggal_lahir)
                            {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d-m-Y') }} 
                            ({{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} tahun)
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Alamat:</strong> {{ $pasien->alamat ?? '-' }}</p>
                    <p><strong>No Telepon:</strong> {{ $pasien->no_telepon ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $pasien->email ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Kunjungan -->
    <div class="card mb-4">
        <div class="card-header bg-maroon text-white">
            <h4 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Riwayat Pendaftaran & Kunjungan</h4>
        </div>
        <div class="card-body">
            @if ($kunjungans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 15%">Tanggal</th>
                                <th style="width: 20%">Poli Tujuan</th>
                                <th style="width: 25%">Keluhan Utama</th>
                                <th style="width: 15%">Status</th>
                                <th style="width: 25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kunjungans as $kunjungan)
                            <tr>
                                <td class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d M Y') }}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $kunjungan->poli }}</span>
                                </td>
                                <td>
                                    <small>{{ Str::limit($kunjungan->keluhan_utama, 50) }}</small>
                                </td>
                                <td>
                                    @if ($kunjungan->status === 'pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock me-1"></i>Menunggu
                                        </span>
                                    @elseif ($kunjungan->status === 'proses')
                                        <span class="badge bg-info">
                                            <i class="bi bi-hourglass-split me-1"></i>Proses
                                        </span>
                                    @elseif ($kunjungan->status === 'selesai')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Selesai
                                        </span>
                                    @elseif ($kunjungan->status === 'batal')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Batal
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($kunjungan->status === 'pending' || $kunjungan->status === 'proses')
                                                     <a href="{{ route('poliklinik.periksa_by_poli', ['kunjunganId' => $kunjungan->id]) }}" 
                                                         class="btn btn-sm btn-primary"
                                                         title="Buka Form Pemeriksaan">
                                            <i class="bi bi-stethoscope me-1"></i>Periksa
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="bi bi-check2 me-1"></i>{{ $kunjungan->status === 'selesai' ? 'Selesai' : 'Batal' }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Info Box -->
                <div class="alert alert-info mt-3" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Catatan:</strong> Klik tombol "Periksa" untuk membuka formulir pemeriksaan pasien. 
                    Data pasien sudah otomatis ter-isi sesuai dengan pendaftaran Anda.
                </div>
            @else
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Belum Ada Data Kunjungan</strong><br>
                    Pasien belum memiliki riwayat kunjungan. Silakan lakukan pendaftaran kembali jika diperlukan.
                </div>
            @endif
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="row mt-4 mb-4">
        <div class="col-md-12">
            <a href="{{ route('pasien.baru') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Daftar Pasien Baru Lagi
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="bi bi-house-door me-2"></i>Kembali ke Dashboard
            </a>
                @if ($kunjungans->count() > 0 && $kunjungans->first()->status !== 'selesai')
                <a href="{{ route('poliklinik.periksa_by_poli', ['kunjunganId' => $kunjungans->first()->id]) }}" 
                    class="btn btn-success">
                <i class="bi bi-stethoscope me-2"></i>Lanjut Pemeriksaan
            </a>
            @endif
        </div>
    </div>
</div>

<style>
    .text-maroon {
        color: #800000;
    }
    .bg-maroon {
        background-color: #800000;
    }
    .border-maroon {
        border-left: 5px solid #800000 !important;
    }
</style>
@endsection
