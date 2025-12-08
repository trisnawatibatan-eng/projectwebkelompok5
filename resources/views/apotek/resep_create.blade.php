@extends('layout')

@section('title', 'Buat Resep')

@section('content')
<div class="container py-5">
    <div class="card p-4">
        <h4>Buat Resep untuk Pemeriksaan #{{ $pemeriksaan->id }}</h4>
        <p>No RM: {{ $pemeriksaan->no_rm }} — Nama: {{ $pemeriksaan->nama }}</p>

        <form action="{{ route('apotek.resep.store') }}" method="POST">
            @csrf
            <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->id }}">

            <div class="mb-3">
                <label class="form-label">Daftar Obat (contoh input terpisah dengan koma)</label>
                <input type="text" name="items[0]" class="form-control" placeholder="Contoh: Paracetamol 500mg x 10">
            </div>

            <button class="btn btn-primary">Simpan Resep</button>
            <a href="{{ route('apotek.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
