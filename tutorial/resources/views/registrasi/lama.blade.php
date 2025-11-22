@extends('layouts')

@section('content')
<div class="card shadow-sm p-4">
  <h3 class="text-center mb-4">🗂️ Form Registrasi Pasien Lama</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->has('not_found'))
    <div class="alert alert-danger">{{ $errors->first('not_found') }}</div>
  @endif

  {{-- Form pencarian pasien --}}
  <form action="{{ route('registrasi.cariPasien') }}" method="POST" class="mb-4">
    @csrf
    <div class="row">
      <div class="col-md-6">
        <label class="form-label">Nomor Rekam Medis</label>
        <input type="text" class="form-control" name="no_rm" placeholder="Masukkan nomor RM" required>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Cari</button>
      </div>
    </div>
  </form>

  {{-- Form detail pasien --}}
  @if(isset($pasien))
  <form action="{{ route('registrasi.simpanLama') }}" method="POST">
    @csrf

    <div class="mb-3">
      <label class="form-label">Nama Lengkap</label>
      <input type="text" class="form-control" name="nama" value="{{ $pasien['nama'] }}" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Tanggal Lahir</label>
      <input type="date" class="form-control" name="tanggal_lahir" value="{{ $pasien['tanggal_lahir'] }}" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Jenis Kelamin</label>
      <input type="text" class="form-control" name="jenis_kelamin" value="{{ $pasien['jenis_kelamin'] }}" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Alamat</label>
      <textarea class="form-control" name="alamat" rows="3" readonly>{{ $pasien['alamat'] }}</textarea>
    </div>

    {{-- Tambahan: tanggal kunjungan baru --}}
    <div class="mb-3">
      <label class="form-label">Tanggal Kunjungan</label>
      <input type="date" class="form-control" name="tanggal_kunjungan" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Poli Tujuan</label>
      <select class="form-select" name="poli_tujuan" required>
        <option value="">-- Pilih Poli --</option>
        <option value="Umum">Poli Umum</option>
        <option value="Gigi">Poli Gigi</option>
        <option value="Anak">Poli Anak</option>
        <option value="Kandungan">Poli Kandungan</option>
        <option value="Kandungan">Poli Syaraf</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Jenis Pembayaran</label>
      <select class="form-select" name="jenis_pembayaran" required>
        <option value="">-- Pilih --</option>
        <option value="BPJS">BPJS</option>
        <option value="Asuransi Swasta">Asuransi Swasta</option>
        <option value="Umum">Umum</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Keluhan</label>
      <textarea class="form-control" name="keluhan" rows="4"></textarea>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="{{ url('/registrasi') }}" class="btn btn-secondary">Kembali</a>
    </div>
  </form>
  @endif
</div>
@endsection
