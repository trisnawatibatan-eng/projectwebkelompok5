@extends('layout')

@section('content')
<div class="container mt-4">
    <h3>🩹 Edit Data Pasien</h3>

    <form action="{{ route('pasien.update', $pasien->id) }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>NIK</label>
                <input type="text" name="nik" value="{{ $pasien->nik }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>No. RM</label>
                <input type="text" name="no_rm" value="{{ $pasien->no_rm }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Nama</label>
                <input type="text" name="nama" value="{{ $pasien->nama }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Alamat</label>
                <input type="text" name="alamat" value="{{ $pasien->alamat }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select">
                    <option value="Laki-laki" {{ $pasien->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ $pasien->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ $pasien->tanggal_lahir }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>No. Telepon</label>
                <input type="text" name="no_telepon" value="{{ $pasien->no_telepon }}" class="form-control" required>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('data.master') }}" class="btn btn-secondary"> Kembali</a>
            <button type="submit" class="btn btn-success"> Simpan</button>
        </div>
    </form>
</div>
@endsection