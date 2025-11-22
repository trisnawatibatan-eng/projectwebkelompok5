@extends('layouts')

@section('content')
<div class="card shadow-sm p-4">
  <h3 class="text-center mb-4">🩺 Form Registrasi Pasien Baru</h3>

  <!-- Form akan kirim ke route registrasi.simpan -->
  <form action="{{ route('registrasi.simpan') }}" method="POST">
    @csrf

    <!-- NIK -->
    <div class="mb-3">
      <label class="form-label">NIK</label>
      <input type="text" class="form-control" name="nik" placeholder="Masukkan NIK pasien" required>
    </div>

    <!-- Nama -->
    <div class="mb-3">
      <label class="form-label">Nama Lengkap</label>
      <input type="text" class="form-control" name="nama" placeholder="Masukkan nama pasien" required>
    </div>

    <!-- Tanggal Lahir -->
    <div class="mb-3">
      <label class="form-label">Tanggal Lahir</label>
      <input type="date" class="form-control" name="tanggal_lahir" required>
    </div>

    <!-- Jenis Kelamin -->
    <div class="mb-3">
      <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
      <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
        <option value="">--- Pilih ---</option>
        <option value="Perempuan">Perempuan</option>
        <option value="Laki-laki">Laki-laki</option>
      </select>
    </div>

    <!-- Alamat -->
    <div class="mb-3">
      <label class="form-label">Alamat</label>
      <textarea class="form-control" name="alamat" rows="3" required></textarea>
    </div>

    <!-- Nomor Telepon -->
    <div class="mb-3">
      <label class="form-label">Nomor Telepon</label>
      <input type="text" class="form-control" name="no_tlp" placeholder="081xxxxxxxxx">
    </div>

    <!-- Poli Tujuan -->
    <div class="mb-3">
      <label for="poli_tujuan" class="form-label">Poli Tujuan</label>
      <select class="form-select" id="poli_tujuan" name="poli_tujuan" required>
        <option value="">-- Pilih --</option>
        <option value="Umum">Poli Umum</option>
        <option value="Gigi">Poli Gigi</option>
        <option value="Anak">Poli Anak</option>
        <option value="Kandungan">Poli Kandungan</option>
        <option value="Syaraf">Poli Syaraf</option>
      </select>
    </div>

    <!-- Jenis Pembayaran -->
    <div class="mb-3">
      <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
      <select class="form-select" id="jenis_pembayaran" name="jenis_pembayaran" required>
        <option value="">-- Pilih --</option>
        <option value="BPJS">BPJS</option>
        <option value="Asuransi Swasta">Asuransi Swasta</option>
        <option value="Umum">Umum</option>
      </select>
    </div>

    <!-- Keluhan -->
    <div class="mb-3">
      <label class="form-label">Keluhan</label>
      <textarea class="form-control" name="keluhan" rows="4"></textarea>
    </div>

    <!-- Tanggal Kunjungan -->
    <div class="mb-3">
      <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan</label>
      <input type="date" class="form-control" id="tanggal_kunjungan" name="tanggal_kunjungan" required>
    </div>

    <!-- Tombol -->
    <div class="d-flex justify-content-between mt-4">
      <a href="{{ route('registrasi.index') }}" class="btn btn-secondary">← Kembali</a>
      <button type="submit" class="btn btn-success">💾 Simpan</button>
    </div>
  </form>
</div>
@endsection
