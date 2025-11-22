@extends('layouts')

@section('title', 'Registrasi Pasien Baru')
@section('content')
<div class="card shadow-sm p-4">
    <h3 class="text-center mb-4">🩺 Form Registrasi Pasien Baru</h3>

    <form action="{{ route('registrasi.simpan') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">NIK</label>
            <input type="text" class="form-control" name="nik" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" name="tanggal_lahir" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select" required>
                <option value="">Pilih</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" name="alamat" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" name="no_tlp">
        </div>

        <div class="mb-3">
            <label class="form-label">Poli Tujuan</label>
            <select name="poli_tujuan" class="form-select">
                <option value="">Pilih</option>
                <option value="Umum">Poli Umum</option>
                <option value="Gigi">Poli Gigi</option>
                <option value="Anak">Poli Anak</option>
                <option value="Kandungan">Poli Kandungan</option>
                <option value="Syaraf">Poli Syaraf</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Pembayaran</label>
            <select name="jenis_pembayaran" class="form-select">
                <option value="">Pilih</option>
                <option value="BPJS">BPJS</option>
                <option value="Asuransi Swasta">Asuransi Swasta</option>
                <option value="Umum">Umum</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Keluhan</label>
            <textarea class="form-control" name="keluhan" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Kunjungan</label>
            <input type="date" class="form-control" name="tanggal_kunjungan">
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('pasien.index') }}" class="btn btn-secondary">← Kembali</a>
            <button type="submit" class="btn btn-success">💾 Simpan</button>
        </div>
    </form>
</div>
@endsection
