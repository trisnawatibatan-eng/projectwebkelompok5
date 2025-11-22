@extends('layouts')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Resume Rawat Jalan Pasien</h2>
    <a href="{{ url('/pemeriksaan') }}" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    <div class="card p-4 shadow-sm">
        <form onsubmit="alert('Resume pasien berhasil disimpan!'); return false;">
            <!-- Data Pasien -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="no_rm" class="form-label">No Rekam Medis</label>
                    <input type="text" class="form-control" id="no_rm" name="no_rm" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama_pasien" class="form-label">Nama Pasien</label>
                    <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" required>
                </div>
            </div>

            <!-- Diagnosa -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="diagnosa_utama" class="form-label">Diagnosa Utama</label>
                    <textarea class="form-control" id="diagnosa_utama" name="diagnosa_utama" rows="2"></textarea>
                </div>
                <div class="col-md-4">
                    <label for="icd10_utama" class="form-label">Kode ICD-10</label>
                    <input type="text" class="form-control" id="icd10_utama" name="icd10_utama">
                </div>
            </div>

            <!-- Diagnosa Sekunder -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="diagnosa_sekunder" class="form-label">Diagnosa Sekunder</label>
                    <textarea class="form-control" id="diagnosa_sekunder" name="diagnosa_sekunder" rows="2"></textarea>
                </div>
                <div class="col-md-4">
                    <label for="icd10_sekunder" class="form-label">Kode ICD-10 Sekunder</label>
                    <input type="text" class="form-control" id="icd10_sekunder" name="icd10_sekunder">
                </div>
            </div>

            <!-- Tindakan -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="tindakan_medis" class="form-label">Tindakan Medis / Prosedur</label>
                    <textarea class="form-control" id="tindakan_medis" name="tindakan_medis" rows="3"></textarea>
                </div>
                <div class="col-md-4">
                    <label for="icd9_tindakan" class="form-label">Kode ICD-9</label>
                    <input type="text" class="form-control" id="icd9_tindakan" name="icd9_tindakan">
                </div>
            </div>

            <!-- Terapi -->
            <div class="mb-3">
                <label for="terapi_diberikan" class="form-label">Terapi / Obat yang Diberikan</label>
                <textarea class="form-control" id="terapi_diberikan" name="terapi_diberikan" rows="3"></textarea>
            </div>

            <!-- Hasil Lab -->
            <div class="mb-3">
                <label for="hasil_lab" class="form-label">Hasil Pemeriksaan Penunjang</label>
                <textarea class="form-control" id="hasil_lab" name="hasil_lab" rows="3"></textarea>
            </div>

            <!-- Tanggal -->
            <div class="mb-3">
                <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan</label>
                <input type="date" class="form-control" id="tanggal_kunjungan" name="tanggal_kunjungan" required>
            </div>

            <!-- Saran -->
            <div class="mb-3">
                <label for="saran_tindak_lanjut" class="form-label">Saran / Rencana Kontrol</label>
                <textarea class="form-control" id="saran_tindak_lanjut" name="saran_tindak_lanjut" rows="3"></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Simpan Resume</button>
                <a href="{{ url('/pemeriksaan') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
