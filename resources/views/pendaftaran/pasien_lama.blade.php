@extends('layout')

@section('content')
<div class="container mt-4">
    <h3>📋 Pendaftaran - Pasien Lamaaa</h3>

        {{-- Form Pencarian --}}
        <form action="{{ route('pasien.cari') }}" method="GET" class="d-flex gap-2 justify-content-center mt-4">
            <input type="text" name="keyword" class="form-control w-50 rounded-pill px-3" placeholder="🔎 Masukkan No RM Pasien" required>
            <button type="submit" class="btn btn-success rounded-pill px-4">Cari</button>
        </form>

        {{-- Info jika tidak ditemukan --}}
        @if(session('info'))
            <div class="alert alert-warning mt-4 text-center fw-semibold">
                {{ session('info') }}
                <a href="{{ route('pasien.baru') }}" class="btn btn-sm btn-primary rounded-pill ms-2">Daftar Pasien Baru</a>
            </div>
        @endif
    </div>


    {{-- Jika Pasien Ditemukan --}}
    @isset($pasien)
    <div class="card shadow-lg border-0 rounded-4 p-4 mt-4">
        <h4 class="fw-bold mb-3">📄 Data Pasien</h4>

        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">No RM</label>
                <input type="text" class="form-control form-control-lg rounded-3" value="{{ $pasien->no_rm }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Nama</label>
                <input type="text" class="form-control form-control-lg rounded-3" value="{{ $pasien->nama }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">NIK</label>
                <input type="text" class="form-control form-control-lg rounded-3" value="{{ $pasien->nik }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Jenis Kelamin</label>
                <input type="text" class="form-control form-control-lg rounded-3" value="{{ $pasien->jenis_kelamin }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Lahir</label>
                <input type="text" class="form-control form-control-lg rounded-3" value="{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d-m-Y') }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">No Telepon</label>
                <input type="text" class="form-control form-control-lg rounded-3" value="{{ $pasien->no_telepon }}" readonly>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill">💾 Lanjutkan Pendaftaran</button>
        </div>
    </div>
    @endisset

</div>
@endsection
