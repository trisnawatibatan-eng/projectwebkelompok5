@extends('layout')

@section('title', 'Pemeriksaan Poli Gigi & Mulut')

@section('content')
{{-- Menghilangkan min-vh-100 dan py-5 pada kontainer utama, karena layout.blade.php sudah menanganinya. --}}
<div class="container my-4"> 
    <div class="card shadow-lg border-0 rounded-4">
        
        {{-- CARD HEADER --}}
        <div class="card-header bg-maroon text-white p-4 rounded-top-4">
            <h4 class="mb-0 fw-bold"><i class="fas fa-tooth me-2"></i> FORMULIR POLIKLINIK GIGI & MULUT</h4>
            <p class="mb-0 small opacity-90">
                {{-- DISPLAY DATA PASIEN --}}
                Rekam Medis: **<span id="pasien_rm_display">RM-{{ $pasien->no_rm ?? '00000' }}</span>** | Pasien: **<span id="pasien_nama_display">{{ $pasien->nama ?? 'Nama Pasien' }}</span>**
            </p>
        </div>

        {{-- PASIEN SEARCH BLOCK (Di dalam card-body atau sebagai border-bottom) --}}
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
        
        <div class="card-body p-5">
            
            <form action="{{ route('pemeriksaan.store') }}" method="POST">
                @csrf
                {{-- ID Pasien yang dipilih (berubah via JS) --}}
                <input type="hidden" name="pasien_id" id="pasien_selected_id" value="{{ $pasien->id ?? '' }}">
                <input type="hidden" name="poli_slug" value="gigi">

                {{-- ============================================= --}}
                {{-- 1. ANAMNESIS (RIWAYAT) --}}
                {{-- ============================================= --}}
                <h5 class="mb-3 pb-2 border-bottom fw-bold text-maroon"><i class="fas fa-history me-2"></i> 1. ANAMNESIS</h5>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="keluhan_utama" class="form-label fw-semibold">Keluhan Utama Gigi/Mulut</label>
                        <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="3" required placeholder="Contoh: Gigi berlubang, Gusi bengkak dan berdarah, Sakit saat mengunyah..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="riwayat_penyakit_dahulu" class="form-label fw-semibold">Riwayat Penyakit Sistemik</label>
                        <textarea class="form-control" id="riwayat_penyakit_dahulu" name="riwayat_penyakit_dahulu" rows="3" placeholder="Contoh: Jantung, Diabetes, Hipertensi (penting untuk tindakan cabut)"></textarea>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- 2. PEMERIKSAAN OBJEKTIF --}}
                {{-- ============================================= --}}
                <h5 class="mt-4 mb-3 pb-2 border-bottom fw-bold text-maroon"><i class="fas fa-microscope me-2"></i> 2. PEMERIKSAAN LOKAL (GIGI)</h5>
                
                <div class="row g-3 mb-4">
                    {{-- Tanda Vital --}}
                    <div class="col-md-4">
                        <label for="td" class="form-label">Tekanan Darah (mmHg)</label>
                        <input type="text" class="form-control" id="td" name="td" placeholder="Diperlukan sebelum tindakan">
                    </div>
                    <div class="col-md-4">
                        <label for="suhu" class="form-label">Suhu (°C)</label>
                        <input type="number" step="0.1" class="form-control" id="suhu" name="suhu" placeholder="Suhu">
                    </div>
                    <div class="col-md-4">
                        <label for="nadi" class="form-label">Nadi (x/menit)</label>
                        <input type="number" class="form-control" id="nadi" name="nadi" placeholder="Nadi">
                    </div>
                    
                    {{-- Status Lokal --}}
                    <div class="col-12">
                        <label for="pemeriksaan_intra_oral" class="form-label fw-semibold">Pemeriksaan Intra Oral & Gigi</label>
                        <textarea class="form-control" id="pemeriksaan_intra_oral" name="pemeriksaan_intra_oral" rows="4" placeholder="Contoh: Oral Hygiene sedang, Gingiva radang di regio 4, Terdapat karies profunda di gigi 36..."></textarea>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- 3. DIAGNOSIS & RENCANA TINDAKAN --}}
                {{-- ============================================= --}}
                <h5 class="mt-4 mb-3 pb-2 border-bottom fw-bold text-maroon"><i class="fas fa-hammer me-2"></i> 3. DIAGNOSIS & RENCANA TINDAKAN</h5>

                <div class="mb-3">
                    <label for="diagnosis_gigi" class="form-label fw-semibold">Diagnosis (Kasus)</label>
                    <input type="text" class="form-control" id="diagnosis_gigi" name="diagnosis_gigi" required placeholder="Contoh: Pulpitis Reversibel, Periodontitis Kronis">
                </div>
                
                <div class="mb-4">
                    <label for="tindakan_gigi" class="form-label fw-semibold">Rencana Tindakan / Tata Laksana</label>
                    <textarea class="form-control" id="tindakan_gigi" name="tindakan_gigi" rows="3" placeholder="Contoh: Scalling, Pencabutan Gigi 47, Penambalan Gigi 36, Rujuk ke RS"></textarea>
                </div>
                
                {{-- Tautan ke Apotek (Untuk resep obat pasca-tindakan, misal antibiotik/analgesik) --}}
                <div class="mb-5 p-3 border border-maroon rounded-3 bg-light-maroon-soft">
                    <label class="form-label fw-bold"><i class="fas fa-pills me-2"></i> RESEP OBAT (LANJUT KE APOTEK)</label>
                    <p class="small text-muted">Gunakan untuk meresepkan analgesik atau antibiotik pasca-tindakan.</p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" name="action" value="save_only" class="btn btn-secondary px-4">
                        <i class="fas fa-save me-2"></i> Simpan Saja
                    </button>
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
    /* Tambahan style untuk form Gigi, pastikan variabel warna ada di layout.blade.php */
    .bg-maroon { background-color: var(--primary-maroon) !important; }
    .text-maroon { color: var(--primary-maroon) !important; }
    .btn-maroon { background-color: var(--primary-maroon) !important; border-color: var(--primary-maroon) !important; color: white; }
    .btn-maroon:hover { background-color: var(--dark-maroon) !important; border-color: var(--dark-maroon) !important; }
    .bg-light-maroon-soft { background-color: #f7e6e6; border-color: var(--primary-maroon) !important; }
    .btn-outline-maroon {
        color: var(--primary-maroon);
        border-color: var(--primary-maroon);
    }
    .btn-outline-maroon:hover {
        background-color: var(--primary-maroon);
        color: white;
    }
    
    /* STYLE TAMBAHAN DARI PASIEN BARU */
    .header-maroon { 
        background: linear-gradient(135deg, var(--maroon-light), var(--maroon-dark)) !important;
        background-color: var(--primary-maroon) !important; 
    }
    .min-vh-100 { min-height: 100vh; }
    .card {
        border-radius: 24px;
        margin-top: -30px; /* Kompensasi visual agar kartu tumpang tindih sedikit dengan header */
    }
    .form-control, .form-select {
        border-radius: 14px !important;
        padding: 10px 18px; /* Mengurangi padding sedikit agar tidak terlalu besar */
        font-size: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBtn = document.getElementById('search_pasien_btn');
        const searchInput = document.getElementById('search_pasien_input');
        const resultsDiv = document.getElementById('pasien_search_results');
        // ID Pasien yang dipilih (di-set di hidden input)
        const pasienIdInput = document.getElementById('pasien_selected_id'); 
        
        // Display element di search block
        const rmDisplay = document.getElementById('pasien_rm_display');
        const namaDisplay = document.getElementById('pasien_nama_display');
        
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
                    const details = `Lahir: ${pasien.tanggal_lahir ? pasien.tanggal_lahir : '-'} | Alamat: ${pasien.alamat ? pasien.alamat : '-'}`;
                    html += `
                        <li class="list-group-item list-group-item-action" 
                            style="cursor: pointer;"
                            data-id="${pasien.id}" data-rm="${pasien.no_rm}" data-nama="${pasien.nama}">
                            
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
            const id = listItem.dataset.id;
            const rm = listItem.dataset.rm;
            const nama = listItem.dataset.nama;
            
            // 1. Update form data (hidden input)
            pasienIdInput.value = id;
            
            // 2. Update display header
            rmDisplay.textContent = `${rm}`;
            namaDisplay.textContent = nama;
            
            // 3. Provide feedback and clear results
            resultsDiv.innerHTML = `<div class="alert alert-success py-1 small mt-2" role="alert">Pasien **${nama}** (${rm}) berhasil dipilih.</div>`;
            searchInput.value = nama;
        }

        // Init: Atur tampilan default jika pasien belum dipilih
        if (pasienIdInput.value === '' || pasienIdInput.value === '999') {
            rmDisplay.textContent = `RM-00000`;
            namaDisplay.textContent = 'Silakan Cari Pasien';
        }
    });
</script>
@endpush