@extends('layout')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4"><i class="bi bi-journal-medical"></i> Formulir Pemeriksaan Dokter (SOAP)</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pemeriksaan.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="row g-3">
            {{-- Identitas Pasien --}}
            <div class="col-md-6">
                <label for="no_rm" class="form-label">No. Rekam Medis</label>
                <input type="text" name="no_rm" id="no_rm" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="nama" class="form-label">Nama Pasien</label>
                <input type="text" name="nama" id="nama" class="form-control" readonly>
            </div>

            {{-- S: Subjective --}}
            <div class="col-md-12">
                <h5 class="mt-4">S - Subjective</h5>
                <label for="keluhan_utama" class="form-label">Keluhan Utama</label>
                <textarea name="keluhan_utama" id="keluhan_utama" class="form-control" rows="2" required></textarea>

                <label for="riwayat_penyakit" class="form-label mt-3">Riwayat Penyakit</label>
                <textarea name="riwayat_penyakit" id="riwayat_penyakit" class="form-control" rows="2"></textarea>
            </div>

            {{-- O: Objective --}}
            <div class="col-md-12">
                <h5 class="mt-4">O - Objective</h5>
            </div>
            <div class="col-md-3">
                <label for="suhu" class="form-label">Suhu (°C)</label>
                <input type="number" step="0.1" name="suhu" id="suhu" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="tekanan_darah" class="form-label">Tekanan Darah</label>
                <input type="text" name="tekanan_darah" id="tekanan_darah" class="form-control" placeholder="120/80">
            </div>
            <div class="col-md-3">
                <label for="nadi" class="form-label">Nadi (/menit)</label>
                <input type="number" name="nadi" id="nadi" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="respirasi" class="form-label">Respirasi (/menit)</label>
                <input type="number" name="respirasi" id="respirasi" class="form-control">
            </div>

            {{-- A: Assessment --}}
            <div class="col-md-12">
                <h5 class="mt-4">A - Assessment</h5>
                <label for="diagnosa" class="form-label">Diagnosa</label>
                <textarea name="diagnosa" id="diagnosa" class="form-control" rows="2" required></textarea>
            </div>

            {{-- P: Plan --}}
            <div class="col-md-12">
                <h5 class="mt-4">P - Plan</h5>
                <label for="terapi" class="form-label">Terapi / Rencana Tindakan</label>
                <textarea name="terapi" id="terapi" class="form-control" rows="2" required></textarea>

                <label for="rujukan" class="form-label mt-3">Rujukan (jika ada)</label>
                <input type="text" name="rujukan" id="rujukan" class="form-control" placeholder="Nama RS/Faskes tujuan">
            </div>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save2"></i> Simpan Pemeriksaan
            </button>
        </div>
    </form>
</div>
@endsection