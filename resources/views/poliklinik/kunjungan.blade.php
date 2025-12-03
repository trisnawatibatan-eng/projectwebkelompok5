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
                                <td>{{ $kunjungan->firstItem() + $loop->index }}</td>
                            <td>{{ $k->no_rm }}</td>
                            <td>{{ $k->nama }}</td>
                            <td>
                                <strong>Keluhan:</strong> {{ \Illuminate\Support\Str::limit($k->keluhan_utama ?? '-', 80) }}<br>
                                <strong>Diagnosa:</strong> {{ \Illuminate\Support\Str::limit($k->diagnosa ?? '-', 80) }}
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($k->terapi ?? '-', 60) }}</td>
                                <td>{{ $k->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('kunjungan.edit', $k->id) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>

                                    <form method="POST" action="{{ route('kunjungan.destroy', $k->id) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus kunjungan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination links --}}
            <div class="d-flex justify-content-center">
                {{ $kunjungan->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .table td { vertical-align: middle; }
</style>
@endpush
