@extends('layout')

@section('content')
<div class="container mt-4">
    <h3>📋 Pendaftaran - Pasien Lamaaa</h3>

    {{-- Form Pencarian --}}
    <form action="{{ route('pasien.cari') }}" method="GET" class="d-flex gap-2 mt-3">
        <input type="text" name="keyword" class="form-control" placeholder="Masukkan No RM Pasien" required>
        <button type="submit" class="btn btn-success">🔍 Cari</button>
    </form>

    {{-- Info jika tidak ditemukan --}}
    @if(session('info'))
        <div class="alert alert-warning mt-3">
            {{ session('info') }}
            <a href="{{ route('pasien.baru') }}" class="btn btn-sm btn-primary ms-2">Daftar Pasien Baru</a>
        </div>
    @endif

    {{-- Form otomatis terisi jika pasien ditemukan --}}
    @isset($pasien)
    <form action="#" method="POST" class="mt-4">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">No RM</label>
                <input type="text" class="form-control" value="{{ $pasien->no_rm }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" value="{{ $pasien->nama }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">NIK</label>
                <input type="text" class="form-control" value="{{ $pasien->nik }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jenis Kelamin</label>
                <input type="text" class="form-control" value="{{ $pasien->jenis_kelamin }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Lahir</label>
                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d-m-Y') }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">No Telepon</label>
                <input type="text" class="form-control" value="{{ $pasien->no_telepon }}" readonly>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">💾 Lanjutkan Pendaftaran</button>
        </div>
    </form>
    @endisset
</div>
@endsection