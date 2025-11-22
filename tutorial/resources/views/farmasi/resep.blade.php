@extends('layouts')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Form Resep Obat</h2>
    <a href="{{ url('/farmasi') }}" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    <div class="card p-4 shadow-sm">
        <form onsubmit="alert('Resep berhasil disimpan!'); return false;">
            <div class="mb-3">
                <label for="no_rm" class="form-label">No Rekam Medis</label>
                <input type="text" class="form-control" id="no_rm" name="no_rm" required>
            </div>

            <div class="mb-3">
                <label for="nama_pasien" class="form-label">Nama Pasien</label>
                <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" required>
            </div>

            <div class="mb-3">
                <label for="nama_obat" class="form-label">Nama Obat</label>
                <input type="text" class="form-control" id="nama_obat" name="nama_obat" required>
            </div>

            <div class="mb-3">
                <label for="dosis" class="form-label">Dosis</label>
                <input type="text" class="form-control" id="dosis" name="dosis">
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah">
            </div>

            <div class="mb-3">
                <label for="aturan_pakai" class="form-label">Aturan Pakai</label>
                <textarea class="form-control" id="aturan_pakai" name="aturan_pakai" rows="2"></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Simpan Resep</button>
                <a href="{{ url('/farmasi') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection