@extends('layouts')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Form Pembayaran Pasien</h2>
    <a href="{{ url('/kasir') }}" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    <div class="card p-4 shadow-sm">
        <form onsubmit="alert('Transaksi berhasil disimpan!'); return false;">
            <!-- No RM dan Nama -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="no_rm" class="form-label">No Rekam Medis</label>
                    <input type="text" class="form-control" id="no_rm" name="no_rm" required>
                </div>
                <div class="col-md-6">
                    <label for="nama_pasien" class="form-label">Nama Pasien</label>
                    <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" required>
                </div>
            </div>

            <!-- Total Biaya -->
            <div class="mb-3">
                <label for="total_biaya" class="form-label">Total Biaya (Rp)</label>
                <input type="number" class="form-control" id="total_biaya" name="total_biaya" required>
            </div>

            <!-- Metode Pembayaran -->
            <div class="mb-3">
                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                <select class="form-select" id="metode_pembayaran" name="metode_pembayaran">
                    <option value="">-- Pilih Metode --</option>
                    <option value="Tunai">Tunai</option>
                    <option value="BPJS">BPJS</option>
                    <option value="Transfer">Transfer Bank</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
            </div>

            <!-- Tombol -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                <a href="{{ url('/kasir') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection