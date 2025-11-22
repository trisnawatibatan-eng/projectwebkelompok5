@extends('layouts')

@section('title', 'Edit Pasien')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Edit Pasien</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('pasien.update', $id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- NIK -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">NIK</label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                               value="{{ old('nik', $pasien['nik']) }}" maxlength="16" required>
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Nama -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $pasien['nama']) }}" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $pasien['jenis_kelamin']) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $pasien['jenis_kelamin']) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                               value="{{ old('tanggal_lahir', $pasien['tanggal_lahir']) }}" required>
                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2" required>{{ old('alamat', $pasien['alamat']) }}</textarea>
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Poli Tujuan -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Poli Tujuan</label>
                        <input type="text" name="poli_tujuan" class="form-control @error('poli_tujuan') is-invalid @enderror"
                               value="{{ old('poli_tujuan', $pasien['poli_tujuan']) }}" required>
                        @error('poli_tujuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Jenis Pembayaran -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jenis Pembayaran</label>
                        <input type="text" name="jenis_pembayaran" class="form-control @error('jenis_pembayaran') is-invalid @enderror"
                               value="{{ old('jenis_pembayaran', $pasien['jenis_pembayaran']) }}" required>
                        @error('jenis_pembayaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Tanggal Kunjungan -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                        <input type="date" name="tanggal_kunjungan" class="form-control @error('tanggal_kunjungan') is-invalid @enderror"
                               value="{{ old('tanggal_kunjungan', $pasien['tanggal_kunjungan']) }}" required>
                        @error('tanggal_kunjungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">Simpan Perubahan</button>
                    <a href="{{ route('pasien.index') }}" class="btn btn-secondary px-4">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection