@extends('layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Daftar Kunjungan Terbaru</h3>
        <a href="{{ route('poliklinik') }}" class="btn btn-outline-secondary">Kembali ke Poliklinik</a>
    </div>

    @if($kunjungan->isEmpty())
        <div class="alert alert-info">Belum ada kunjungan yang tercatat.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>No. RM</th>
                        <th>Nama</th>
                        <th>Keluhan / Diagnosa</th>
                        <th>Terapi</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kunjungan as $k)
                        <tr>
                            <td>{{ $k->id }}</td>
                            <td>{{ $k->no_rm }}</td>
                            <td>{{ $k->nama }}</td>
                            <td>
                                <strong>Keluhan:</strong> {{ Str::limit($k->keluhan_utama ?? '-', 80) }}<br>
                                <strong>Diagnosa:</strong> {{ Str::limit($k->diagnosa ?? '-', 80) }}
                            </td>
                            <td>{{ Str::limit($k->terapi ?? '-', 60) }}</td>
                            <td>{{ $k->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .table td { vertical-align: middle; }
</style>
@endpush
