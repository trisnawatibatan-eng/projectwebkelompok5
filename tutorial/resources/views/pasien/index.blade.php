@extends('layouts')

@section('title', 'Master Pasien')
@section('content')
<div class="card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Data Master Pasien</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-success">
                <tr>
                    <th>No. RM</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>Poli Tujuan</th>
                    <th>Jenis Pembayaran</th>
                    <th>Tanggal Kunjungan</th>
                    <th width="150" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse(session('data_pasien', []) as $index => $pasien)
                <tr>
                    <td>{{ $pasien['no_rm'] ?? 'RM' . str_pad($index + 1, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $pasien['nik'] }}</td>
                    <td>{{ $pasien['nama'] }}</td>
                    <td>{{ $pasien['jenis_kelamin'] }}</td>
                    <td>{{ $pasien['tanggal_lahir'] }}</td>
                    <td>{{ $pasien['alamat'] }}</td>
                    <td>{{ $pasien['poli_tujuan'] }}</td>
                    <td>{{ $pasien['jenis_pembayaran'] }}</td>
                    <td>{{ $pasien['tanggal_kunjungan'] }}</td>
                    <td class="text-center">
                        <!-- DETAIL -->
                        <a href="{{ route('pasien.show', $index) }}" 
                           class="btn btn-info btn-sm text-white" title="Lihat Detail">
                            Detail
                        </a>

                        <!-- EDIT -->
                        <a href="{{ route('pasien.edit', $index) }}" 
                           class="btn btn-warning btn-sm" title="Edit">
                            Edit
                        </a>

                        <!-- HAPUS -->
                        <form action="{{ route('pasien.destroy', $index) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger btn-sm" 
                                    title="Hapus"
                                    onclick="return confirm('Yakin ingin menghapus pasien ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        Belum ada data pasien.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection