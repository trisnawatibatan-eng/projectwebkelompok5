@extends('layout')

@section('title', 'Daftar Kunjungan Pasien')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="text-maroon fw-bold">
                <i class="bi bi-list-check me-2"></i>
                @if(isset($filter_poli))
                    Daftar Tunggu - {{ $filter_poli }}
                @else
                    Daftar Kunjungan Pasien
                @endif
            </h2>
            <p class="text-muted">@if(isset($filter_poli)) Berikut pasien yang menunggu di {{ $filter_poli }} @else Berikut adalah pasien yang terdaftar dan menunggu pemeriksaan di Poliklinik @endif</p>
        </div>
    </div>

    @if ($kunjungans->isEmpty())
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>Tidak ada pasien yang menunggu pemeriksaan saat ini.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No RM</th>
                        <th>Nama Pasien</th>
                        <th>Poli Tujuan</th>
                        <th>Dokter</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Keluhan Utama</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kunjungans as $kunjungan)
                        <tr>
                            <td><strong>{{ $kunjungan->no_rm }}</strong></td>
                            <td>{{ $kunjungan->pasien->nama }}</td>
                            <td><span class="badge bg-primary">{{ $kunjungan->poli }}</span></td>
                            <td>{{ $kunjungan->dokter ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d-m-Y') }}</td>
                            <td>{{ $kunjungan->keluhan_utama ?? '-' }}</td>
                            <td>
                                @if ($kunjungan->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($kunjungan->status === 'proses')
                                    <span class="badge bg-info">Proses</span>
                                @elseif ($kunjungan->status === 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                            <td>
                                @if ($kunjungan->status !== 'selesai' && $kunjungan->status !== 'batal')
                                    <a href="{{ route('poliklinik.periksa_by_poli', ['kunjunganId' => $kunjungan->id]) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-stethoscope"></i> Periksa
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $kunjungans->links() }}
        </div>
    @endif
</div>

<style>
    .text-maroon {
        color: #800000;
    }
</style>
@endsection
