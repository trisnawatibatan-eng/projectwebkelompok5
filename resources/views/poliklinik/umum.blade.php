@extends('layout')

@section('title', 'Pemeriksaan Poli Umum')

@section('content')
{{-- PENTING: Hapus container my-4, gunakan container-fluid agar sesuai layout terbaru --}}
<div class="container-fluid px-4"> 
    {{-- Card di tengah halaman dengan lebar maksimum 1200px dan margin-top kompensasi --}}
    <div class="card shadow-lg border-0 rounded-4 mx-auto mb-5" style="max-width: 1300px; margin-left: auto; margin-right: auto;">
        
        {{-- CARD HEADER --}}
        <div class="card-header bg-maroon text-white p-4 rounded-top-4">
            <h4 class="mb-0 fw-bold"><i class="fas fa-file-medical me-2"></i> FORMULIR POLIKLINIK UMUM</h4>
            <p class="mb-0 small opacity-90">
                {{-- DISPLAY DATA PASIEN --}}
                Rekam Medis: **<span id="pasien_rm_display">RM-{{ $pasien->no_rm ?? '00000' }}</span>** | Pasien: **<span id="pasien_nama_display">{{ $pasien->nama ?? 'Nama Pasien' }}</span>**
            </p>
        </div>

        {{-- ============================================= --}}
        {{-- PASIEN SEARCH BLOCK --}}
        {{-- ============================================= --}}
        <div class="p-4 border-bottom bg-light">
            <div class="input-group">
                <input type="text" class="form-control form-control-lg rounded-3" id="search_pasien_input" placeholder="Cari Pasien berdasarkan No. RM, NIK, atau Nama...">
                <button class="btn btn-maroon btn-lg rounded-3 fw-bold" type="button" id="search_pasien_btn">
                    <i class="fas fa-search me-1"></i> Cari Pasien
                </button>
            </div>
            <div id="pasien_search_results" class="mt-2">
                {{-- Hasil pencarian akan diinjeksi di sini --}}
            </div>
        </div>
        {{-- END PASIEN SEARCH BLOCK --}}
        
        <div class="card-body p-5">
            
            <form action="{{ route('pemeriksaan.store') }}" method="POST">
                @csrf
                {{-- ID Pasien yang dipilih (berubah via JS) --}}
                <input type="hidden" name="pasien_id" id="pasien_selected_id" value="{{ $pasien->id ?? '' }}">
                <input type="hidden" name="poli_slug" value="umum"> 

                {{-- ============================================= --}}
                {{-- DATA PASIEN LENGKAP --}}
                {{-- ============================================= --}}
                <h5 class="mb-3 pb-2 border-bottom fw-bold text-maroon"><i class="fas fa-user me-2"></i> DATA PASIEN TERPILIH</h5>
                
                <div class="row g-3 mb-5 small text-muted" id="pasien_detail_display">
                    <div class="col-md-4">
                        <span class="fw-semibold text-dark">NIK:</span> <span id="pasien_detail_nik">-</span>
                    </div>
                    <div class="col-md-4">
                        <span class="fw-semibold text-dark">Tgl Lahir / Usia:</span> <span id="pasien_detail_tgllahir">-</span>
                    </div>
                    <div class="col-md-4">
                        <span class="fw-semibold text-dark">Jenis Kelamin:</span> <span id="pasien_detail_jk">-</span>
                    </div>
                    <div class="col-12">
                        <span class="fw-semibold text-dark">Alamat:</span> <span id="pasien_detail_alamat">-</span>
                    </div>
                </div>
                
                {{-- ============================================= --}}
                {{-- 1. ANAMNESIS (RIWAYAT) --}}
                {{-- ============================================= --}}
                <h5 class="mb-3 pb-2 border-bottom fw-bold text-maroon"><i class="fas fa-history me-2"></i> 1. ANAMNESIS (RIWAYAT)</h5>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="keluhan_utama" class="form-label fw-semibold">Keluhan Utama</label>
                        <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="3" required placeholder="Sebutkan keluhan utama pasien..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="riwayat_penyakit_sekarang" class="form-label fw-semibold">Riwayat Penyakit Sekarang</label>
                        <textarea class="form-control" id="riwayat_penyakit_sekarang" name="riwayat_penyakit_sekarang" rows="3" placeholder="Detail sejak kapan, lokasi, dan gejala penyerta..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="riwayat_penyakit_dahulu" class="form-label fw-semibold">Riwayat Penyakit Dahulu</label>
                        <textarea class="form-control" id="riwayat_penyakit_dahulu" name="riwayat_penyakit_dahulu" rows="2" placeholder="Riwayat DM, Hipertensi, Asma, dll."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="riwayat_alergi" class="form-label fw-semibold">Riwayat Alergi (Obat/Makanan)</label>
                        <input type="text" class="form-control" id="riwayat_alergi" name="riwayat_alergi" placeholder="Contoh: Penisilin, Udang">
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- 2. PEMERIKSAAN FISIK --}}
                {{-- ============================================= --}}
                <h5 class="mt-4 mb-3 pb-2 border-bottom fw-bold text-maroon"><i class="fas fa-stethoscope me-2"></i> 2. PEMERIKSAAN FISIK</h5>
                
                <div class="row g-3 mb-4">
                    {{-- Tanda Vital --}}
                    <div class="col-md-3">
                        <label for="td" class="form-label">Tekanan Darah (mmHg)</label>
                        <input type="text" class="form-control" id="td" name="td" placeholder="Contoh: 120/80">
                    </div>
                    <div class="col-md-3">
                        <label for="suhu" class="form-label">Suhu (°C)</label>
                        <input type="number" step="0.1" class="form-control" id="suhu" name="suhu" placeholder="Contoh: 36.8">
                    </div>
                    <div class="col-md-3">
                        <label for="nadi" class="form-label">Nadi (x/menit)</label>
                        <input type="number" class="form-control" id="nadi" name="nadi" placeholder="Contoh: 80">
                    </div>
                    <div class="col-md-3">
                        <label for="rr" class="form-label">Respirasi (x/menit)</label>
                        <input type="number" class="form-control" id="rr" name="rr" placeholder="Contoh: 18">
                    </div>
                    
                    {{-- Status Umum --}}
                    <div class="col-md-4">
                        <label for="bb" class="form-label">Berat Badan (kg)</label>
                        <input type="number" step="0.1" class="form-control" id="bb" name="bb" placeholder="BB">
                    </div>
                    <div class="col-md-4">
                        <label for="tb" class="form-label">Tinggi Badan (cm)</label>
                        <input type="number" class="form-control" id="tb" name="tb" placeholder="TB">
                    </div>
                    <div class="col-md-4">
                        <label for="kesadaran" class="form-label">Kesadaran</label>
                        <select class="form-select" id="kesadaran" name="kesadaran">
                            <option value="Compos Mentis">Compos Mentis</option>
                            <option value="Apatis">Apatis</option>
                            <option value="Somnolen">Somnolen</option>
                            <option value="Sopor">Sopor</option>
                        </select>
                    </div>
                </div>

                <label for="pemeriksaan_sistematis" class="form-label fw-semibold">Pemeriksaan Sistematis</label>
                <textarea class="form-control mb-4" id="pemeriksaan_sistematis" name="pemeriksaan_sistematis" rows="3" placeholder="Contoh: Mata: Konjungtiva anemis (-), Sklera ikterik (-). Thorax: Vesikuler +/+. Abdomen: Supel, nyeri tekan (-)"></textarea>

                {{-- ============================================= --}}
                {{-- 3. DIAGNOSIS & TERAPI --}}
                {{-- ============================================= --}}
                <h5 class="mt-4 mb-3 pb-2 border-bottom fw-bold text-maroon"><i class="fas fa-clipboard-list me-2"></i> 3. DIAGNOSIS & PENATALAKSANAAN</h5>

                <div class="mb-3">
                    <label for="diagnosis" class="form-label fw-semibold">Diagnosis Kerja (ICD-10)</label>
                    <input type="text" class="form-control" id="diagnosis" name="diagnosis" required placeholder="Cari dan masukkan diagnosis utama">
                </div>
                
                <div class="mb-4">
                    <label for="terapi" class="form-label fw-semibold">Penatalaksanaan (Tindakan/Non-Obat)</label>
                    <textarea class="form-control" id="terapi" name="terapi" rows="3" placeholder="Misal: Kompres hangat, Istirahat total, Pasang infus"></textarea>
                </div>
                
                {{-- Bagian untuk Pemberian Resep (Tautan ke Apotek) --}}
                <div class="mb-5 p-3 border border-maroon rounded-3 bg-light-maroon-soft">
                    <label class="form-label fw-bold"><i class="fas fa-prescription-bottle me-2"></i> RESEP OBAT (LANJUT KE APOTEK)</label>
                    <p class="small text-muted">Setelah menyimpan pemeriksaan ini, Anda akan diarahkan untuk mengisi resep obat jika dibutuhkan.</p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end gap-2">
                    {{-- Tombol Simpan Saja --}}
                    <button type="submit" name="action" value="save_only" class="btn btn-secondary px-4">
                        <i class="fas fa-save me-2"></i> Simpan Saja
                    </button>
                    {{-- Tombol Lanjutkan ke Resep --}}
                    <button type="submit" name="action" value="save_and_next" class="btn btn-maroon px-4">
                        <i class="fas fa-share me-2"></i> Simpan & Lanjutkan ke Resep
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Menggunakan variabel warna Maroon untuk konsistensi */
    .bg-maroon { background-color: var(--primary-maroon) !important; }
    .text-maroon { color: var(--primary-maroon) !important; }
    .btn-maroon { background-color: var(--primary-maroon) !important; border-color: var(--primary-maroon) !important; color: white; }
    .btn-maroon:hover { background-color: var(--dark-maroon) !important; border-color: var(--dark-maroon) !important; }
    .bg-light-maroon-soft { background-color: #f7e6e6; border-color: var(--primary-maroon) !important; }
    .form-control, .form-select {
        border-radius: 14px !important;
        padding: 10px 18px; /* Mengurangi padding sedikit agar tidak terlalu besar */
        font-size: 1rem;
    }
    .card {
        margin-top: -30px; /* Kompensasi visual agar kartu tumpang tindih sedikit dengan header */
    }
    .btn-outline-maroon {
        color: var(--primary-maroon);
        border-color: var(--primary-maroon);
    }
    .btn-outline-maroon:hover {
        background-color: var(--primary-maroon);
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBtn = document.getElementById('search_pasien_btn');
        const searchInput = document.getElementById('search_pasien_input');
        const resultsDiv = document.getElementById('pasien_search_results');
        const pasienIdInput = document.getElementById('pasien_selected_id'); 
        
        // Display element
        const rmDisplay = document.getElementById('pasien_rm_display');
        const namaDisplay = document.getElementById('pasien_nama_display');
        
        // Detail display elements
        const detailNik = document.getElementById('pasien_detail_nik');
        const detailTglLahir = document.getElementById('pasien_detail_tgllahir');
        const detailJK = document.getElementById('pasien_detail_jk');
        const detailAlamat = document.getElementById('pasien_detail_alamat');

        // --- PENTING: Menggunakan route yang terhubung ke PasienController@cariPasienAjax ---
        const searchEndpoint = '{{ route('pasien.cari.ajax') }}';

        // Event Listeners
        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); 
                performSearch();
            }
        });

        // Function untuk melakukan Pencarian Pasien via AJAX
        async function performSearch() {
            const query = searchInput.value.toLowerCase().trim();
            resultsDiv.innerHTML = '';
            
            if (query.length < 2) { 
                resultsDiv.innerHTML = '<div class="alert alert-warning py-1 small">Masukkan minimal 2 karakter untuk mencari.</div>';
                return;
            }

            // Tampilkan loading indicator
            resultsDiv.innerHTML = '<div class="text-center text-maroon"><i class="fas fa-spinner fa-spin me-2"></i> Mencari data pasien...</div>';
            
            try {
                const response = await fetch(`${searchEndpoint}?q=${query}`);
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const results = await response.json();

                if (results.length === 0) {
                    resultsDiv.innerHTML = '<div class="alert alert-info py-1 small">Tidak ada pasien ditemukan.</div>';
                    return;
                }

                // Tampilkan Hasil Pencarian
                let html = '<ul class="list-group">';
                results.forEach(pasien => {
                    const jk = pasien.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
                    const details = `Lahir: ${pasien.tanggal_lahir || '-'} | JK: ${jk} | Alamat: ${pasien.alamat ? pasien.alamat : '-'}`;
                    
                    html += `
                        <li class="list-group-item list-group-item-action" 
                            style="cursor: pointer;"
                            data-id="${pasien.id}" 
                            data-rm="${pasien.no_rm}" 
                            data-nama="${pasien.nama}"
                            data-nik="${pasien.nik || '-'}"
                            data-tgllahir="${pasien.tanggal_lahir || '-'}"
                            data-jk="${jk}"
                            data-alamat="${pasien.alamat || '-'}">
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${pasien.nama}</strong> <span class="badge bg-maroon ms-2">${pasien.no_rm}</span>
                                    <div class="small text-muted mt-1">${details}</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-maroon select-pasien-btn">Pilih</button>
                            </div>
                        </li>
                    `;
                });
                html += '</ul>';
                resultsDiv.innerHTML = html;

                // Attach event listeners to new 'Pilih' buttons
                document.querySelectorAll('.select-pasien-btn').forEach(button => {
                    button.addEventListener('click', selectPasien);
                });

            } catch (error) {
                console.error('Error fetching data:', error);
                resultsDiv.innerHTML = '<div class="alert alert-danger py-1 small">Terjadi kesalahan saat mencari pasien. Silakan coba lagi.</div>';
            }
        }

        // Function untuk memilih Pasien
        function selectPasien(e) {
            const listItem = e.target.closest('li');
            const data = listItem.dataset;
            
            // 1. Update form data (hidden input)
            pasienIdInput.value = data.id;
            
            // 2. Update display header
            rmDisplay.textContent = data.rm;
            namaDisplay.textContent = data.nama;
            
            // 3. Update Detail Pasien di dalam form
            detailNik.textContent = data.nik;
            detailTglLahir.textContent = `${data.tgllahir}`;
            detailJK.textContent = data.jk;
            detailAlamat.textContent = data.alamat;

            // 4. Provide feedback and clear results
            resultsDiv.innerHTML = `<div class="alert alert-success py-1 small mt-2" role="alert">Pasien **${data.nama}** (${data.rm}) berhasil dipilih.</div>`;
            searchInput.value = data.nama;
        }

        // Init: Atur tampilan default jika pasien belum dipilih
        if (pasienIdInput.value === '' || pasienIdInput.value === '999') {
            rmDisplay.textContent = `RM-00000`;
            namaDisplay.textContent = 'Silakan Cari Pasien';
        }
    });
</script>
@endpush