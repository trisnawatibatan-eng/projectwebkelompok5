@extends('layouts')

@section('header', 'Form Pemeriksaan SOAP')
@section('content')
<div class="container mt-5">
    <h3 class="mb-4 text-center">Form Pemeriksaan Pasien (SOAP)</h3>
    <a href="{{ url('/pemeriksaan') }}" class="btn btn-secondary mb-3">← Kembali ke Pemeriksaan</a>

    <div class="card p-4 shadow-sm">
        <form onsubmit="alert('Data SOAP berhasil disimpan!'); return false;">
            <!-- Identitas Pasien -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="no_rm" class="form-label">No Rekam Medis</label>
                    <input type="text" class="form-control" id="no_rm" name="no_rm" required>
                </div>
                <div class="col-md-4">
                    <label for="nama_pasien" class="form-label">Nama Pasien</label>
                    <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" required>
                </div>
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Tanggal Pemeriksaan</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <!-- Tanda Vital -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="suhu" class="form-label">Suhu (°C)</label>
                    <input type="text" class="form-control" id="suhu" name="suhu">
                </div>
                <div class="col-md-3">
                    <label for="tekanan_darah" class="form-label">Tekanan Darah</label>
                    <input type="text" class="form-control" id="tekanan_darah" name="tekanan_darah">
                </div>
                <div class="col-md-3">
                    <label for="nadi" class="form-label">Nadi (x/menit)</label>
                    <input type="text" class="form-control" id="nadi" name="nadi">
                </div>
                <div class="col-md-3">
                    <label for="respirasi" class="form-label">Respirasi (x/menit)</label>
                    <input type="text" class="form-control" id="respirasi" name="respirasi">
                </div>
            </div>

            <!-- Subjektif -->
            <div class="mb-3">
                <label for="subjektif" class="form-label">Subjektif</label>
                <textarea class="form-control" id="subjektif" name="subjektif" rows="3" placeholder="Keluhan utama, riwayat penyakit, dll."></textarea>
            </div>

            <!-- Objektif -->
            <div class="mb-3">
                <label for="objektif" class="form-label">Objektif</label>
                <textarea class="form-control" id="objektif" name="objektif" rows="3" placeholder="Hasil pemeriksaan fisik, tanda vital, dll."></textarea>
            </div>

            <!-- Assessment -->
            <div class="mb-3">
                <label for="assessment" class="form-label">Assessment (Diagnosis)</label>
                <textarea class="form-control" id="assessment" name="assessment" rows="2" placeholder="Diagnosis atau kesimpulan klinis."></textarea>
            </div>

            <!-- Plan -->
            <div class="mb-3">
                <label for="plan" class="form-label">Plan (Rencana Terapi)</label>
                <textarea class="form-control" id="plan" name="plan" rows="2" placeholder="Rencana terapi, pemeriksaan lanjutan, edukasi."></textarea>
            </div>

            <!-- Rujukan -->
            <div class="mb-3">
                <label for="rujukan" class="form-label">Rujukan (jika ada)</label>
                <input type="text" class="form-control" id="rujukan" name="rujukan" placeholder="Nama fasilitas rujukan">
            </div>

            <!-- Tombol -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary">💾 Simpan SOAP</button>
                <a href="{{ url('/pemeriksaan') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection