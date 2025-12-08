@extends('layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Edit Kunjungan: {{ $pemeriksaan->nama }} ({{ $pemeriksaan->no_rm }})</h3>
        <a href="{{ route('poliklinik.kunjungan') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <form method="POST" action="{{ route('kunjungan.update', $pemeriksaan->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Keluhan Utama</label>
            <textarea name="keluhan_utama" class="form-control" required>{{ old('keluhan_utama', $pemeriksaan->keluhan_utama) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Suhu</label>
                <input name="suhu" type="number" step="any" class="form-control" value="{{ old('suhu', $pemeriksaan->suhu) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">TD (tekanan darah)</label>
                <input name="td" type="text" class="form-control" value="{{ old('td', $pemeriksaan->tekanan_darah) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Nadi</label>
                <input name="nadi" type="number" class="form-control" value="{{ old('nadi', $pemeriksaan->nadi) }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Diagnosa</label>
            <input name="diagnosa" class="form-control" required value="{{ old('diagnosa', $pemeriksaan->diagnosa) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Terapi</label>
            <input name="terapi" class="form-control" value="{{ old('terapi', $pemeriksaan->terapi) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Rujukan</label>
            <input name="rujukan" class="form-control" value="{{ old('rujukan', $pemeriksaan->rujukan) }}">
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('poliklinik.kunjungan') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
