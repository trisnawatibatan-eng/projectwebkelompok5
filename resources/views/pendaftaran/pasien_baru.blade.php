@extends('layout')

@section('title', 'Pendaftaran Pasien Baru')

@section('content')
<div class="min-vh-100 pb-5">
    {{-- Header Formulir (Gaya RME Sesuai Layout) --}}
    <div class="header-maroon text-white py-4 shadow">
        <div class="container-fluid px-4 px-lg-5">
            <div class="d-flex align-items-center">
                <i class="fas fa-file-medical fs-1 me-3"></i>
                <h3 class="mb-0 fw-bold">Form Pendaftaran Pasien Baru</h3>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 px-lg-5 py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                
                {{-- Alert Success --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                {{-- Alert Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-maroon-light alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> Mohon periksa kembali inputan Anda. Ada beberapa kesalahan yang perlu diperbaiki.
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Card Form Utama --}}
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5 p-lg-5">
                        <form action="{{ route('pasien.store') }}" method="POST">
                            @csrf

                            <!-- ============================================== -->
                            <!-- SECTION 1: DATA PRIBADI PASIEN -->
                            <!-- ============================================== -->
                            <h5 class="text-maroon border-bottom border-maroon pb-2 mb-4 fw-bold">1. Data Identitas Pasien</h5>
                            
                            <div class="row g-4 mb-5">
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold text-dark">Nama Pasien <span class="text-maroon">*</span></label>
                                    <input type="text" name="nama" class="form-control form-control-lg rounded-3 @error('nama') is-invalid-maroon @enderror" 
                                            placeholder="Masukkan nama pasien" value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold text-dark">Tanggal Lahir <span class="text-maroon">*</span></label>
                                    <input type="date" name="tanggal_lahir" class="form-control form-control-lg rounded-3 @error('tanggal_lahir') is-invalid-maroon @enderror" 
                                            value="{{ old('tanggal_lahir') }}" required>
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold text-dark">Jenis Kelamin <span class="text-maroon">*</span></label>
                                    <select name="jenis_kelamin" class="form-select form-select-lg rounded-3 @error('jenis_kelamin') is-invalid-maroon @enderror" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold">No KTP (NIK)</label>
                                    <input type="text" name="nik" class="form-control form-control-lg rounded-3 @error('nik') is-invalid-maroon @enderror" 
                                            placeholder="Masukkan No KTP (16 digit)" value="{{ old('nik') }}" maxlength="16">
                                    @error('nik')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold">No Telepon / WA</label>
                                    <input type="text" name="no_telepon" class="form-control form-control-lg rounded-3 @error('no_telepon') is-invalid-maroon @enderror" 
                                            placeholder="08xxxxxxxxxx" value="{{ old('no_telepon') }}">
                                    @error('no_telepon')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control form-control-lg rounded-3 @error('email') is-invalid-maroon @enderror" 
                                            placeholder="contoh@email.com" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- ============================================== -->
                            <!-- SECTION 2: ALAMAT LENGKAP -->
                            <!-- ============================================== -->
                            <h5 class="text-maroon border-bottom border-maroon pb-2 mb-4 fw-bold">2. Alamat Domisili Pasien</h5>

                            <div class="row g-4 mb-5">
                                {{-- PROVINSI --}}
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold">Provinsi <span class="text-maroon">*</span></label>
                                    <select name="provinsi" id="provinsi" class="form-select form-select-lg rounded-3 @error('provinsi') is-invalid-maroon @enderror" required>
                                        <option value="">-- Pilih Provinsi --</option>
                                        {{-- Options diisi oleh JS --}}
                                    </select>
                                    @error('provinsi')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- KOTA/KABUPATEN --}}
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold">Kota/Kabupaten <span class="text-maroon">*</span></label>
                                    <select name="kota" id="kota" class="form-select form-select-lg rounded-3 @error('kota') is-invalid-maroon @enderror" required disabled>
                                        <option value="">-- Pilih Kota/Kabupaten --</option>
                                    </select>
                                    @error('kota')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- KECAMATAN --}}
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold">Kecamatan <span class="text-maroon">*</span></label>
                                    <select name="kecamatan" id="kecamatan" class="form-select form-select-lg rounded-3 @error('kecamatan') is-invalid-maroon @enderror" required disabled>
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                    @error('kecamatan')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                {{-- ALAMAT DETAIL --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Detail Alamat Lengkap (Jalan, RT/RW, Desa/Kelurahan) <span class="text-maroon">*</span></label>
                                    <input type="text" name="alamat" class="form-control form-control-lg rounded-3 @error('alamat') is-invalid-maroon @enderror" 
                                            placeholder="Masukkan detail alamat lengkap" value="{{ old('alamat') }}" required>
                                    @error('alamat')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- ============================================== -->
                            <!-- SECTION 3: DATA KUNJUNGAN & PEMBAYARAN -->
                            <!-- ============================================== -->
                            <h5 class="text-maroon border-bottom border-maroon pb-2 mb-4 fw-bold">3. Data Kunjungan & Penjamin</h5>

                            <div class="row g-4 mb-5">
                                {{-- JENIS PEMBAYARAN --}}
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label fw-semibold">Jenis Pembayaran <span class="text-maroon">*</span></label>
                                    <select name="penjamin" id="penjamin_select" class="form-select form-select-lg rounded-3 @error('penjamin') is-invalid-maroon @enderror" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Umum" {{ old('penjamin') == 'Umum' ? 'selected' : '' }}>Umum (Tunai/Non-BPJS)</option>
                                        <option value="BPJS" {{ old('penjamin') == 'BPJS' ? 'selected' : '' }}>BPJS Kesehatan</option>
                                        <option value="Asuransi" {{ old('penjamin') == 'Asuransi' ? 'selected' : '' }}>Asuransi Lain</option>
                                    </select>
                                    @error('penjamin')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                {{-- INPUT NO BPJS (KONDISIONAL) --}}
                                <div class="col-lg-3 col-md-6" id="bpjs_input_group" style="display: {{ old('penjamin') == 'BPJS' ? 'block' : 'none' }};">
                                    <label class="form-label fw-semibold">Nomor BPJS <span class="text-maroon">*</span></label>
                                    <input type="text" name="no_bpjs" id="no_bpjs" class="form-control form-control-lg rounded-3 @error('no_bpjs') is-invalid-maroon @enderror" 
                                            placeholder="Masukkan 13 digit No. BPJS" value="{{ old('no_bpjs') }}" maxlength="13">
                                    @error('no_bpjs')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label fw-semibold">Poliklinik Tujuan <span class="text-maroon">*</span></label>
                                    <select name="poliklinik_tujuan" class="form-select form-select-lg rounded-3 @error('poliklinik_tujuan') is-invalid-maroon @enderror" required>
                                        <option value="">-- Pilih --</option>
                                        <option {{ old('poliklinik_tujuan') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                                        <option {{ old('poliklinik_tujuan') == 'Poli Gigi & Mulut' ? 'selected' : '' }}>Poli Gigi & Mulut</option>
                                        <option {{ old('poliklinik_tujuan') == 'Poli KIA/KB' ? 'selected' : '' }}>Poli KIA/KB</option>
                                    </select>
                                    @error('poliklinik_tujuan')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                                    <input type="date" name="tanggal_kunjungan" class="form-control form-control-lg rounded-3 @error('tanggal_kunjungan') is-invalid-maroon @enderror" 
                                            value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}">
                                    @error('tanggal_kunjungan')
                                        <div class="invalid-feedback-maroon">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Catatan Tambahan (Full Width) -->
                            <div class="mb-5">
                                <label class="form-label fw-semibold">Catatan Tambahan</label>
                                <textarea name="catatan" rows="4" class="form-control form-control-lg rounded-3 @error('catatan') is-invalid-maroon @enderror" 
                                            placeholder="Tambahkan keterangan atau catatan jika diperlukan">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <div class="invalid-feedback-maroon">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tombol Simpan - Full Width di HP -->
                            <div class="d-grid d-md-block text-md-end">
                                <button type="submit" class="btn btn-maroon btn-lg px-5 py-3 rounded-4 shadow-lg fw-bold w-100 w-md-auto">
                                    <i class="fas fa-save me-2"></i> SIMPAN DATA PASIEN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* VARIASI WARNA MAROON */
    :root {
        --maroon-base: #800000;
        --maroon-dark: #660000;
        --maroon-light: #a04040;
        --maroon-shadow: rgba(128, 0, 0, 0.5); /* 50% opacity of base maroon */
    }

    /* CUSTOM CLASSES */
    .header-maroon, .bg-maroon { background-color: var(--maroon-base) !important; }
    .text-maroon { color: var(--maroon-base) !important; }
    .border-maroon { border-color: var(--maroon-base) !important; }
    
    /* ALERT KHUSUS UNTUK ERROR DENGAN TEMA MAROON */
    .alert-maroon-light {
        color: var(--maroon-dark);
        background-color: #ffe6e6; /* Sangat terang, untuk kontras */
        border-color: var(--maroon-light);
    }
    .alert-maroon-light .btn-close-white {
        filter: invert(30%) sepia(100%) saturate(1500%) hue-rotate(330deg) brightness(80%) contrast(100%);
    }

    /* OVERRIDES DAN LAYOUT */
    .min-vh-100 { min-height: 100vh; }
    .pb-5 { padding-bottom: 3rem !important; } 
    
    .form-control, .form-select {
        border-radius: 14px !important;
        padding: 14px 18px;
        font-size: 1.05rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; 
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--maroon-base) !important;
        box-shadow: 0 0 0 0.3rem rgba(128, 0, 0, 0.25) !important; /* Maroon focus shadow */
    }
    .card {
        border-radius: 24px;
        margin-top: -30px; /* Kompensasi visual agar kartu tumpang tindih sedikit dengan header */
    }
    
    /* BUTTON MAROON */
    .btn-maroon {
        background: linear-gradient(135deg, var(--maroon-light), var(--maroon-dark)); 
        border: none;
        color: white;
        border-radius: 16px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    .btn-maroon:hover {
        background: linear-gradient(135deg, var(--maroon-base), var(--maroon-dark));
        transform: translateY(-2px); 
        box-shadow: 0 10px 20px var(--maroon-shadow); /* Maroon shadow effect */
    }

    /* VALIDATION FEEDBACK (CUSTOM MAROON) */
    .text-maroon { color: var(--maroon-base) !important; }

    /* Custom Invalid Input Styling */
    .form-control.is-invalid-maroon, 
    .form-select.is-invalid-maroon {
        border-color: var(--maroon-base) !important;
        padding-right: calc(1.5em + 0.75rem);
        /* Menggunakan data-uri SVG dengan warna maroon untuk ikon validasi */
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23800000'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3cpath stroke-linecap='round' d='M6 8.25h.01'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.35rem) center;
        background-size: calc(0.75em + 0.35rem) calc(0.75em + 0.35rem);
    }
    
    /* Custom Invalid Feedback Text Color */
    .invalid-feedback-maroon {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: var(--maroon-dark); /* Warna teks feedback */
    }

    /* Show feedback when invalid class is present */
    .form-control.is-invalid-maroon ~ .invalid-feedback-maroon, 
    .form-select.is-invalid-maroon ~ .invalid-feedback-maroon {
        display: block;
    }

    @media (max-width: 768px) {
        .container-fluid { padding-left: 15px !important; padding-right: 15px !important; }
        .w-md-auto { width: auto !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    // --- Data Simulasi Wilayah Indonesia ---
    // PENTING: Dalam aplikasi nyata, ini harus diambil dari API atau Database
    const provinces = [
        { id: 1, name: "DKI Jakarta" },
        { id: 2, name: "Jawa Barat" },
        { id: 3, name: "Jawa Timur" },
        { id: 4, name: "Sumatera Utara" },
        { id: 5, name: "Sulawesi Selatan" },
    ];
    const cities = [
        { id: 101, province_id: 1, name: "Kota Jakarta Pusat" },
        { id: 102, province_id: 1, name: "Kota Jakarta Selatan" },
        { id: 201, province_id: 2, name: "Kota Bandung" },
        { id: 202, province_id: 2, name: "Kabupaten Bogor" },
        { id: 301, province_id: 3, name: "Kota Surabaya" },
        { id: 302, province_id: 3, name: "Kabupaten Jember" },
        { id: 401, province_id: 4, name: "Kota Medan" },
        { id: 501, province_id: 5, name: "Kota Makassar" },
    ];
    const districts = [
        { id: 1001, city_id: 101, name: "Tanah Abang" },
        { id: 1002, city_id: 101, name: "Gambir" },
        { id: 2001, city_id: 201, name: "Bandung Wetan" },
        { id: 2002, city_id: 201, name: "Coblong" },
        { id: 2003, city_id: 202, name: "Cibinong" },
        { id: 3001, city_id: 302, name: "Kaliwates" },
        { id: 3002, city_id: 302, name: "Patrang" },
        { id: 4001, city_id: 401, name: "Medan Kota" },
        { id: 5001, city_id: 501, name: "Panakkukang" },
    ];
    // ----------------------------------------

    document.addEventListener('DOMContentLoaded', function() {
        const selectProvinsi = document.getElementById('provinsi');
        const selectKota = document.getElementById('kota');
        const selectKecamatan = document.getElementById('kecamatan');
        const selectPenjamin = document.getElementById('penjamin_select');
        const bpjsInputGroup = document.getElementById('bpjs_input_group');
        const inputNoBpjs = document.getElementById('no_bpjs');
        
        // --- 1. KONDISIONAL BPJS INPUT ---
        function toggleBpjsInput() {
            if (selectPenjamin.value === 'BPJS') {
                bpjsInputGroup.style.display = 'block';
                inputNoBpjs.setAttribute('required', 'required');
            } else {
                bpjsInputGroup.style.display = 'none';
                inputNoBpjs.removeAttribute('required');
            }
        }

        selectPenjamin.addEventListener('change', toggleBpjsInput);
        toggleBpjsInput(); // Jalankan saat load untuk old data

        // --- 2. CASCADING DROPDOWN WILAYAH ---
        
        // Populate Provinsi
        function populateProvinces() {
            provinces.forEach(p => {
                const option = new Option(p.name, p.id);
                selectProvinsi.add(option);
            });
        }

        // Populate Kota/Kabupaten
        selectProvinsi.addEventListener('change', function() {
            const provinceId = parseInt(this.value);
            selectKota.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
            selectKecamatan.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            selectKota.disabled = true;
            selectKecamatan.disabled = true;
            
            if (provinceId) {
                const filteredCities = cities.filter(c => c.province_id === provinceId);
                filteredCities.forEach(c => {
                    const option = new Option(c.name, c.id);
                    selectKota.add(option);
                });
                selectKota.disabled = false;
            }
        });

        // Populate Kecamatan
        selectKota.addEventListener('change', function() {
            const cityId = parseInt(this.value);
            selectKecamatan.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            selectKecamatan.disabled = true;
            
            if (cityId) {
                const filteredDistricts = districts.filter(d => d.city_id === cityId);
                filteredDistricts.forEach(d => {
                    const option = new Option(d.name, d.name); // Menggunakan nama sebagai value
                    selectKecamatan.add(option);
                });
                selectKecamatan.disabled = false;
            }
        });
        
        populateProvinces();
    });
</script>
@endpush