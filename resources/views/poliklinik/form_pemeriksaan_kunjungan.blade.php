@extends('layout')

@section('title', 'Form Pemeriksaan - ' . $kunjungan->pasien->nama)

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="text-maroon fw-bold">
                <i class="bi bi-file-medical me-2"></i>Form Pemeriksaan Pasien
            </h2>
        </div>
    </div>

    <!-- Kartu Informasi Pasien -->
    <div class="card mb-4 border-maroon">
        <div class="card-header bg-maroon text-white">
            <h5 class="mb-0">Informasi Pasien</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nomor RM:</strong> {{ $kunjungan->no_rm }}</p>
                    <p><strong>Nama:</strong> {{ $kunjungan->pasien->nama }}</p>
                    <p><strong>Jenis Kelamin:</strong> {{ ucfirst($kunjungan->pasien->jenis_kelamin) }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Umur:</strong> 
                        @if ($kunjungan->pasien->tanggal_lahir)
                            {{ \Carbon\Carbon::parse($kunjungan->pasien->tanggal_lahir)->age }} tahun
                        @else
                            -
                        @endif
                    </p>
                    <p><strong>Alamat:</strong> {{ $kunjungan->pasien->alamat ?? '-' }}</p>
                    <p><strong>No Telepon:</strong> {{ $kunjungan->pasien->no_telepon ?? '-' }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Poli Tujuan:</strong> <span class="badge bg-primary">{{ $kunjungan->poli }}</span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Dokter/Perawat:</strong> {{ $kunjungan->dokter ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pemeriksaan -->
    <form action="{{ route('poliklinik.simpan_pemeriksaan_kunjungan', $kunjungan->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf

        <div class="card mb-4">
            <div class="card-header bg-maroon text-white">
                <h5 class="mb-0">Anamnesis (Riwayat & Keluhan)</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="keluhan_utama" class="form-label"><strong>Keluhan Utama</strong> <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="3" required 
                        placeholder="Deskripsi keluhan utama pasien">{{ $kunjungan->keluhan_utama ?? '' }}</textarea>
                    @error('keluhan_utama')<span class="text-danger small">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label for="riwayat_penyakit" class="form-label">Riwayat Penyakit</label>
                    <textarea class="form-control" id="riwayat_penyakit" name="riwayat_penyakit" rows="2" 
                        placeholder="Riwayat penyakit dahulu/sekarang">{{ old('riwayat_penyakit') }}</textarea>
                    @error('riwayat_penyakit')<span class="text-danger small">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Pemeriksaan Fisik -->
        <div class="card mb-4">
            <div class="card-header bg-maroon text-white">
                <h5 class="mb-0">Pemeriksaan Fisik</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="suhu" class="form-label">Suhu Tubuh (°C)</label>
                            <input type="number" class="form-control" id="suhu" name="suhu" step="0.1" 
                                placeholder="Contoh: 36.5" min="35" max="42" value="{{ old('suhu') }}">
                            @error('suhu')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tekanan_darah" class="form-label">Tekanan Darah (mmHg)</label>
                            <input type="text" class="form-control" id="tekanan_darah" name="tekanan_darah" 
                                placeholder="Contoh: 120/80" value="{{ old('tekanan_darah') }}">
                            @error('tekanan_darah')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nadi" class="form-label">Nadi (x/menit)</label>
                            <input type="number" class="form-control" id="nadi" name="nadi" 
                                placeholder="Contoh: 80" value="{{ old('nadi') }}">
                            @error('nadi')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="respirasi" class="form-label">Respirasi (x/menit)</label>
                            <input type="number" class="form-control" id="respirasi" name="respirasi" 
                                placeholder="Contoh: 20" value="{{ old('respirasi') }}">
                            @error('respirasi')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Diagnosa & Terapi -->
        <div class="card mb-4">
            <div class="card-header bg-maroon text-white">
                <h5 class="mb-0">Diagnosa & Terapi</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="diagnosa" class="form-label"><strong>Diagnosa</strong> <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="diagnosa" name="diagnosa" rows="2" required 
                        placeholder="Diagnosa medis pasien">{{ old('diagnosa') }}</textarea>
                    @error('diagnosa')<span class="text-danger small">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label for="terapi" class="form-label"><strong>Terapi / Rencana Tindakan</strong> <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="terapi" name="terapi" rows="2" required 
                        placeholder="Terapi atau rencana tindakan">{{ old('terapi') }}</textarea>
                    @error('terapi')<span class="text-danger small">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label for="rujukan" class="form-label">Rujukan (Jika Ada)</label>
                    <input type="text" class="form-control" id="rujukan" name="rujukan" 
                        placeholder="Contoh: Rumah Sakit X" value="{{ old('rujukan') }}">
                    @error('rujukan')<span class="text-danger small">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Resep Obat -->
        <div class="card mb-4">
            <div class="card-header bg-maroon text-white">
                <h5 class="mb-0">Resep Obat</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Tambahkan obat yang ingin diresepkan untuk pasien ini.</p>
                
                <div id="obat-container">
                    <div class="row g-2 mb-3 obat-row">
                        <div class="col-md-5">
                            <input type="text" name="resep_items[0][name]" class="form-control" placeholder="Nama Obat" value="{{ old('resep_items.0.name') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="resep_items[0][qty]" class="form-control" placeholder="Qty" min="1" value="{{ old('resep_items.0.qty', 1) }}">
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="resep_items[0][price]" class="form-control" placeholder="Harga (Rp)" min="0" step="100" value="{{ old('resep_items.0.price') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm btn-danger remove-obat">−</button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="button" id="add-obat" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Obat
                    </button>
                </div>
            </div>
        </div>


        <!-- Tombol Aksi -->
        <div class="row mb-4">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg me-2">
                    <i class="bi bi-check-circle me-2"></i>Simpan Pemeriksaan
                </button>
                <a href="{{ route('poliklinik.daftar_kunjungan') }}" class="btn btn-secondary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </form>
</div>

<style>
    .text-maroon {
        color: #800000;
    }
    .bg-maroon {
        background-color: #800000;
    }
    .border-maroon {
        border-left: 5px solid #800000 !important;
    }
</style>

@push('scripts')
<script>
(function(){
    let obatIdx = 1;
    
    document.getElementById('add-obat').addEventListener('click', function(){
        const container = document.getElementById('obat-container');
        const row = document.querySelector('.obat-row').cloneNode(true);
        
        row.querySelectorAll('input').forEach(inp => {
            const name = inp.getAttribute('name');
            const newName = name.replace(/resep_items\[\d+\]/, 'resep_items[' + obatIdx + ']');
            inp.setAttribute('name', newName);
            inp.value = '';
        });
        
        container.appendChild(row);
        obatIdx++;
    });

    document.addEventListener('click', function(e){
        if(e.target && e.target.classList.contains('remove-obat')){
            const rows = document.querySelectorAll('.obat-row');
            if(rows.length > 1){
                e.target.closest('.obat-row').remove();
            } else {
                alert('Minimal harus ada satu obat atau kosongkan semuanya');
            }
        }
    });
})();
</script>
@endpush

@endsection
