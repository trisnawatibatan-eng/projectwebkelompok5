@extends('layouts')

@section('title', 'Detail Pasien')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Pasien</h5>
            <a href="{{ route('pasien.index') }}" class="btn btn-light btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">No. RM</th>
                    <td>{{ $pasien['no_rm'] ?? 'RM' . str_pad($id + 1, 4, '0', STR23_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td>{{ $pasien['nik'] }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $pasien['nama'] }}</td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ $pasien['jenis_kelamin'] }}</td>
                </tr>
                <tr>
                    <th>Tanggal Lahir</th>
                    <td>{{ $pasien['tanggal_lahir'] }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $pasien['alamat'] }}</td>
                </tr>
                <tr>
                    <th>Poli Tujuan</th>
                    <td>{{ $pasien['poli_tujuan'] }}</td>
                </tr>
                <tr>
                    <th>Jenis Pembayaran</th>
                    <td>{{ $pasien['jenis_pembayaran'] }}</td>
                </tr>
                <tr>
                    <th>Tanggal Kunjungan</th>
                    <td>{{ $pasien['tanggal_kunjungan'] }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection