@extends('layout')

@section('title', 'Pendaftaran Pasien Lama')

@section('content')
<div class="min-vh-100 pb-5">
    
    {{-- Header Formulir (Gaya RME Sesuai Layout) --}}
    <div class="header-maroon text-white py-4 shadow">
        <div class="container-fluid px-4 px-lg-5">
            <div class="d-flex align-items-center">
                <i class="fas fa-people-fill fs-1 me-3"></i>
                <h3 class="mb-0 fw-bold">Pendaftaran Pasien Lama</h3>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 px-lg-5 py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                
                {{-- Alert Status (Diambil dari session info) --}}
                @if(session('info'))
                    <div class="alert alert-warning alert-dismissible fade show rounded-4 shadow-sm mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                {{-- Card Form Utama --}}
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="margin-top: -30px;">
                    <div class="card-body p-4 p-md-5 p-lg-5">

                        <h5 class="text-maroon border-bottom border-maroon pb-2 mb-4 fw-bold">1. Cari Data Pasien</h5>

                        {{-- Form Pencarian --}}
                        <form action="{{ route('pasien.cari') }}" method="GET" class="mb-5">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-9">
                                    <input type="text" name="keyword" class="form-control form-control-lg rounded-3" 
                                           placeholder="Masukkan No. RM, NIK, atau Nama Pasien" required
                                           value="{{ request('keyword') }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-maroon btn-lg w-100 rounded-3 fw-bold">
                                        <i class="fas fa-search me-2"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        
                        @if ($pasien)
                        <h5 class="text-maroon border-bottom border-maroon pb-2 mb-4 fw-bold mt-5">2. Verifikasi Data & Pendaftaran Kunjungan</h5>
                        
                        {{-- Data Pasien Ditemukan (Untuk Verifikasi Petugas) --}}
                        <div class="alert alert-success-light border border-success rounded-3 p-3 mb-4">
                            <div class="row small">
                                <div class="col-md-6 mb-2"><strong>No. RM:</strong> {{ $pasien->no_rm }}</div>
                                <div class="col-md-6 mb-2"><strong>Nama:</strong> {{ $pasien->nama }}</div>
                                <div class="col-md-6 mb-2"><strong>NIK:</strong> {{ $pasien->nik }}</div>
                                <div class="col-md-6 mb-2"><strong>Tgl Lahir/JK:</strong> {{ date('d M Y', strtotime($pasien->tanggal_lahir)) }} / {{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                <div class="col-12"><strong>Alamat:</strong> {{ $pasien->alamat }}</div>
                            </div>
                        </div>

                        {{-- Form Pendaftaran Kunjungan (Antrian) --}}
                        {{-- Mengirim data ke PasienController@store, yang akan memproses antrian --}}
                        <form action="{{ route('pasien.store') }}" method="POST">
                            @csrf
                            {{-- Input tersembunyi ID Pasien Lama --}}
                            <input type="hidden" name="pasien_id" value="{{ $pasien->id }}">
                            <input type="hidden" name="is_pasien_lama" value="1">
                            
                            {{-- Data yang dibutuhkan untuk tabel Antrian --}}
                            <div class="row g-4 mb-5">
                                {{-- JENIS PEMBAYARAN --}}
                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label fw-semibold">Jenis Pembayaran <span class="text-maroon">*</span></label>
                                    <select name="penjamin" id="penjamin_select" class="form-select form-select-lg rounded-3" required>
                                        <option value="Umum">Umum (Tunai)</option>
                                        <option value="Asuransi">Asuransi</option>
                                    </select>
                                </div>
                                
                                {{-- INPUT NAMA ASURANSI (KONDISIONAL) --}}
                                <div class="col-lg-4 col-md-6" id="nama_asuransi_group" style="display: none;">
                                    <label class="form-label fw-semibold">Nama Asuransi <span class="text-maroon">*</span></label>
                                    <input type="text" name="nama_asuransi" id="nama_asuransi" class="form-control form-control-lg rounded-3" 
                                            placeholder="Contoh: Prudential">
                                </div>

                                {{-- INPUT NO ASURANSI (KONDISIONAL) --}}
                                <div class="col-lg-4 col-md-6" id="no_asuransi_group" style="display: none;">
                                    <label class="form-label fw-semibold">Nomor Asuransi <span class="text-maroon">*</span></label>
                                    <input type="text" name="no_asuransi" id="no_asuransi" class="form-control form-control-lg rounded-3" 
                                            placeholder="Masukkan Nomor Kartu Asuransi">
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label fw-semibold">Poliklinik Tujuan <span class="text-maroon">*</span></label>
                                    <select name="poliklinik_tujuan" class="form-select form-select-lg rounded-3" required>
                                        <option value="">-- Pilih Poli --</option>
                                        <option>Poli Umum</option>
                                        <option>Poli Gigi & Mulut</option>
                                        <option>Poli KIA/KB</option>
                                    </select>
                                </div>
                                
                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                                    <input type="date" name="tanggal_kunjungan" class="form-control form-control-lg rounded-3" 
                                            value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            
                            {{-- Tombol Daftar --}}
                            <div class="d-grid d-md-block text-md-end">
                                <button type="submit" class="btn btn-maroon btn-lg px-5 py-3 rounded-4 shadow-lg fw-bold w-100 w-md-auto">
                                    <i class="fas fa-hospital me-2"></i> DAFTARKAN KUNJUNGAN
                                </button>
                            </div>
                        </form>

                        @else
                        {{-- Pasien Belum Ditemukan --}}
                        <div class="alert alert-secondary rounded-3 p-4">
                            <i class="fas fa-info-circle me-2"></i> Silakan masukkan No. RM, NIK, atau Nama pasien pada kolom di atas untuk memulai pendaftaran.
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Tambahan style khusus */
    .alert-success-light { background-color: #e6ffe6; color: #0f5132; }
    .header-maroon { background-color: var(--primary-maroon) !important; }
    .text-maroon { color: var(--primary-maroon) !important; }
    .border-maroon { border-color: var(--primary-maroon) !important; }
    .btn-maroon {
        background: linear-gradient(135deg, var(--light-maroon), var(--dark-maroon)); 
        border: none;
        color: white;
        border-radius: 16px;
    }
    .btn-maroon:hover {
        background: var(--primary-maroon);
    }
    .card {
        margin-top: -30px; /* Kompensasi visual agar kartu tumpang tindih sedikit dengan header */
        border-radius: 24px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPenjamin = document.getElementById('penjamin_select');
        const namaAsuransiGroup = document.getElementById('nama_asuransi_group');
        const noAsuransiGroup = document.getElementById('no_asuransi_group');
        const inputNamaAsuransi = document.getElementById('nama_asuransi');
        const inputNoAsuransi = document.getElementById('no_asuransi');
        
        // --- KONDISIONAL ASURANSI INPUT ---
        function toggleAsuransiInput() {
            const isAsuransi = selectPenjamin.value === 'Asuransi';

            if (isAsuransi) {
                namaAsuransiGroup.style.display = 'block';
                noAsuransiGroup.style.display = 'block';
                inputNamaAsuransi.setAttribute('required', 'required');
                inputNoAsuransi.setAttribute('required', 'required');
            } else {
                namaAsuransiGroup.style.display = 'none';
                noAsuransiGroup.style.display = 'none';
                inputNamaAsuransi.removeAttribute('required');
                inputNoAsuransi.removeAttribute('required');
                // Kosongkan nilai (ini penting agar validasi backend tidak terpicu)
                inputNamaAsuransi.value = ''; 
                inputNoAsuransi.value = '';
            }
        }

        selectPenjamin.addEventListener('change', toggleAsuransiInput);
        toggleAsuransiInput(); // Jalankan saat load
    });
</script>
@endpush