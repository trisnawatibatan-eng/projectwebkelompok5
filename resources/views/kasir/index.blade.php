@extends('layout')

@section('title', 'Kasir Pembayaran')

@section('content')
<div class="min-vh-100 pb-5">
    {{-- Header Formulir (Gaya RME Sesuai Layout) --}}
    <div class="text-white py-4 shadow" style="background-color:#6a1a1a;">
        <div class="container-fluid px-4 px-lg-5">
            <div class="d-flex align-items-center">
                <i class="fas fa-cash-register fs-1 me-3"></i>
                <h3 class="mb-0 fw-bold">KASIR PEMBAYARAN TAGIHAN</h3>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 px-lg-5 py-4">
        <div class="row justify-content-center">
            <div class="col-12">

                {{-- Alert Placeholder --}}
                <div id="status_alert_container" class="mb-4">
                    {{-- Pesan status sukses/error akan diinjeksi di sini --}}
                </div>

                {{-- Card Form Utama --}}
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5 p-lg-5">
                        <form action="{{ route('kasir.bayar') }}" method="POST" id="form-pembayaran">
                            @csrf
                            <input type="hidden" name="pemeriksaan_id" id="pemeriksaan_id_input" value="">
                            
                            {{-- ============================================= --}}
                            {{-- HEADER PASIEN & SEARCH BLOCK --}}
                            {{-- ============================================= --}}
                            <h5 class="text-maroon border-bottom border-maroon pb-2 mb-4 fw-bold">1. Cari Tagihan Pasien</h5>
                            
                            <div class="row g-3 align-items-center mb-4">
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-lg rounded-3" id="search_tagihan_input" placeholder="Masukkan No. RM atau ID Pemeriksaan...">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-maroon btn-lg w-100 rounded-3 fw-bold" type="button" id="search_tagihan_btn">
                                        <i class="fas fa-search me-1"></i> Cari Tagihan
                                    </button>
                                </div>
                            </div>

                            <div id="tagihan_search_results" class="mt-2 mb-5">
                                <p class="small text-muted">Hasil pencarian tagihan (Pemeriksaan / Resep yang belum lunas) akan muncul di sini.</p>
                            </div>
                            
                            <!-- ============================================== -->
                            <!-- SECTION 2: RINCIAN TAGIHAN -->
                            <!-- ============================================== -->
                            <h5 class="text-maroon border-bottom border-maroon pb-2 mb-4 fw-bold">2. Rincian Biaya</h5>

                            <div id="rincian_tagihan_container" class="mb-5">
                                <p class="text-muted text-center" id="rincian_placeholder">Tagihan akan dimuat otomatis setelah pasien dipilih.</p>

                                <table class="table table-bordered table-striped d-none" id="tabel_tagihan">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Deskripsi Layanan/Obat</th>
                                            <th class="text-end">Biaya (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tagihan_body">
                                        {{-- Data tagihan diinjeksi di sini oleh JS --}}
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-maroon-light">
                                            <td class="fw-bold">TOTAL TAGIHAN</td>
                                            <td class="text-end fw-bolder" id="total_tagihan_display">Rp 0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- ============================================== -->
                            <!-- SECTION 3: INPUT PEMBAYARAN -->
                            <!-- ============================================== -->
                            <h5 class="text-maroon border-bottom border-maroon pb-2 mb-4 fw-bold">3. Pembayaran</h5>
                            
                            <div class="row g-4 mb-4">
                                <div class="col-lg-4">
                                    <label for="metode_pembayaran" class="form-label fw-semibold">Metode Pembayaran</label>
                                    <select class="form-select form-select-lg rounded-3" id="metode_pembayaran" name="metode_pembayaran" required disabled>
                                        <option value="Tunai">Tunai</option>
                                        <option value="Debit">Debit/QRIS</option>
                                        <option value="Transfer">Transfer Bank</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="jumlah_bayar" class="form-label fw-semibold">Jumlah Dibayar (Rp)</label>
                                    <input type="number" class="form-control form-control-lg rounded-3" id="jumlah_bayar" name="jumlah_bayar" min="0" required placeholder="0" disabled>
                                </div>
                                
                                <div class="col-lg-4 text-end">
                                    <label class="form-label fw-semibold">Kembalian:</label>
                                    <h3 class="fw-bolder mt-1" id="kembalian_display" style="color: green;">Rp 0</h3>
                                </div>
                            </div>


                            <!-- Tombol Aksi - Footer Form -->
                            <div class="d-grid d-md-block text-md-end pt-3">
                                <button type="button" class="btn btn-outline-secondary btn-lg px-5 py-3 rounded-4 fw-bold" id="cetak_tagihan_btn" disabled>
                                    <i class="fas fa-print me-2"></i> Cetak Tagihan
                                </button>
                                <button type="submit" class="btn btn-maroon btn-lg px-5 py-3 rounded-4 shadow-lg fw-bold w-100 w-md-auto" id="proses_bayar_btn" disabled>
                                    <i class="fas fa-check-circle me-2"></i> PROSES PEMBAYARAN
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
    /* VARIASI WARNA MAROON (Diambil dari Formulir Pasien Baru) */
    .header-maroon { background-color: var(--maroon-base) !important; }
    .text-maroon { color: var(--maroon-base) !important; }
    .border-maroon { border-color: var(--maroon-base) !important; }
    
    /* CUSTOM TABLE STYLE */
    .table-maroon-light {
        background-color: #f7e6e6 !important; /* Maroon sangat muda */
        border-color: var(--maroon-base) !important;
        color: var(--maroon-dark) !important;
    }

    /* OVERRIDES FORM (untuk konsistensi dengan form pendaftaran) */
    .form-control, .form-select {
        border-radius: 14px !important;
        padding: 14px 18px;
        font-size: 1.05rem;
    }
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
        box-shadow: 0 10px 20px var(--maroon-shadow); 
    }
    
    .card {
        margin-top: -30px; /* Pertahankan kompensasi tumpang tindih visual */
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search_tagihan_input');
        const searchBtn = document.getElementById('search_tagihan_btn');
        const resultsDiv = document.getElementById('tagihan_search_results');
        const prosesBayarBtn = document.getElementById('proses_bayar_btn');
        const cetakTagihanBtn = document.getElementById('cetak_tagihan_btn');
        const jumlahBayarInput = document.getElementById('jumlah_bayar');
        const kembalianDisplay = document.getElementById('kembalian_display');
        const totalTagihanDisplay = document.getElementById('total_tagihan_display');
        const tagihanBody = document.getElementById('tagihan_body');
        const tabelTagihan = document.getElementById('tabel_tagihan');
        const rincianPlaceholder = document.getElementById('rincian_placeholder');
        const pemeriksaanIdInput = document.getElementById('pemeriksaan_id_input');
        const selectMetodePembayaran = document.getElementById('metode_pembayaran');
        
        let totalTagihan = 0;

        // --- PENTING: Ganti URL ini dengan route API yang Anda buat di Laravel! ---
        const searchEndpoint = '{{ route('kasir.cari.tagihan') ?? '/api/kasir/search-tagihan' }}'; 
        const statusAlertContainer = document.getElementById('status_alert_container');

        // --- SIMULASI DATA TAGIHAN (Ganti dengan FETCH ke Backend) ---
        const dummyTagihan = [
            {
                id: 1, // ID Pemeriksaan
                no_rm: 'RM000002',
                nama_pasien: 'Marlina Kan',
                poli: 'Gigi & Mulut',
                tagihan_list: [
                    { desc: 'Pemeriksaan Poli Gigi', biaya: 50000 },
                    { desc: 'Scaling (Tindakan)', biaya: 150000 },
                    { desc: 'Amoxicillin 500mg (Resep Apotek)', biaya: 25000 },
                ]
            },
            {
                id: 2,
                no_rm: 'RM000003',
                nama_pasien: 'Uco',
                poli: 'Umum',
                tagihan_list: [
                    { desc: 'Pemeriksaan Poli Umum', biaya: 35000 },
                    { desc: 'Paracetamol 500mg', biaya: 10000 },
                ]
            }
        ];
        // --------------------------------------------------------------------------

        // Helper untuk menampilkan notifikasi di dalam div khusus
        function showStatusAlert(message, type = 'danger') {
            statusAlertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                    <i class="fas fa-info-circle me-2"></i> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
        }

        // Fungsi Reset Formulir Tagihan
        function resetTagihanForm() {
            totalTagihan = 0;
            pemeriksaanIdInput.value = '';
            rincianPlaceholder.classList.remove('d-none');
            tabelTagihan.classList.add('d-none');
            tagihanBody.innerHTML = '';
            totalTagihanDisplay.textContent = 'Rp 0';
            kembalianDisplay.textContent = 'Rp 0';
            jumlahBayarInput.value = '';
            prosesBayarBtn.disabled = true;
            cetakTagihanBtn.disabled = true;
            jumlahBayarInput.disabled = true;
            selectMetodePembayaran.disabled = true;
            kembalianDisplay.classList.replace('text-danger', 'text-success'); // Reset warna kembalian
        }

        // Fungsi Pencarian Tagihan
        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); 
                performSearch();
            }
        });

        async function performSearch() {
            const query = searchInput.value.toLowerCase().trim();
            resultsDiv.innerHTML = '';
            
            if (query.length < 2) { 
                resultsDiv.innerHTML = '<div class="alert alert-warning py-1 small">Masukkan minimal 2 karakter.</div>';
                return;
            }

            resultsDiv.innerHTML = '<div class="text-center text-maroon"><i class="fas fa-spinner fa-spin me-2"></i> Mencari tagihan...</div>';
            resetTagihanForm(); // Reset sebelum mencari

            try {
                // GANTI DENGAN FETCH ASLI KE searchEndpoint
                // const response = await fetch(${searchEndpoint}?query=${query});
                // const results = await response.json();
                
                // SIMULASI
                const results = dummyTagihan.filter(t => 
                    t.no_rm.toLowerCase().includes(query) || t.nama_pasien.toLowerCase().includes(query) || t.id.toString() === query
                );

                if (results.length === 0) {
                    resultsDiv.innerHTML = '<div class="alert alert-info py-1 small">Tidak ada tagihan yang belum dibayar ditemukan.</div>';
                    return;
                }

                // Tampilkan Hasil Pencarian
                let html = '<ul class="list-group">';
                results.forEach(tagihan => {
                    const tagihanTotal = tagihan.tagihan_list.reduce((sum, item) => sum + item.biaya, 0);
                    html += `
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                            style="cursor: pointer;"
                            data-id="${tagihan.id}" data-total="${tagihanTotal}">
                            <div>
                                <strong>${tagihan.nama_pasien}</strong> <span class="badge bg-secondary ms-2">${tagihan.no_rm}</span>
                                <div class="small text-muted mt-1">${tagihan.poli} | Total: Rp ${formatRupiah(tagihanTotal)}</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-maroon select-tagihan-btn">Pilih</button>
                        </li>
                    `;
                });
                html += '</ul>';
                resultsDiv.innerHTML = html;

                document.querySelectorAll('.select-tagihan-btn').forEach(button => {
                    button.addEventListener('click', selectTagihan);
                });

            } catch (error) {
                console.error('Error fetching data:', error);
                resultsDiv.innerHTML = '<div class="alert alert-danger py-1 small">Terjadi kesalahan saat mencari tagihan.</div>';
            }
        }

        // Fungsi Memilih Tagihan
        function selectTagihan(e) {
            const listItem = e.target.closest('li');
            const pemeriksaanId = listItem.dataset.id;
            const tagihanTotal = parseFloat(listItem.dataset.total);
            
            // SIMULASI: Ambil detail tagihan (di aplikasi nyata, ini adalah fetch kedua)
            const selectedTagihan = dummyTagihan.find(t => t.id == pemeriksaanId);

            // 1. Update Header & Total
            document.getElementById('layanan_poli_display').textContent = selectedTagihan.poli;
            document.getElementById('pasien_nama_display').textContent = selectedTagihan.nama_pasien;
            
            pemeriksaanIdInput.value = pemeriksaanId;
            totalTagihan = tagihanTotal;
            
            // 2. Tampilkan Rincian Tagihan
            rincianPlaceholder.classList.add('d-none');
            tabelTagihan.classList.remove('d-none');
            
            tagihanBody.innerHTML = '';
            selectedTagihan.tagihan_list.forEach(item => {
                tagihanBody.innerHTML += `
                    <tr>
                        <td>${item.desc}</td>
                        <td class="text-end">Rp ${formatRupiah(item.biaya)}</td>
                    </tr>
                `;
            });
            
            totalTagihanDisplay.textContent = Rp ${formatRupiah(totalTagihan)};
            
            // 3. Aktifkan Pembayaran dan Tombol
            jumlahBayarInput.value = totalTagihan; // Isi otomatis jumlah tagihan
            updateKembalian();
            
            jumlahBayarInput.disabled = false;
            selectMetodePembayaran.disabled = false;
            prosesBayarBtn.disabled = false;
            cetakTagihanBtn.disabled = false;

            resultsDiv.innerHTML = <div class="alert alert-success py-1 small mt-2" role="alert">Tagihan Pasien **${selectedTagihan.nama_pasien}** berhasil dimuat.</div>;
        }
        
        // Fungsi Update Kembalian
        jumlahBayarInput.addEventListener('input', updateKembalian);
        function updateKembalian() {
            const bayar = parseFloat(jumlahBayarInput.value) || 0;
            const kembalian = bayar - totalTagihan;
            
            kembalianDisplay.textContent = Rp ${formatRupiah(kembalian)};

            // Kunci tombol proses jika pembayaran kurang
            const isLunas = kembalian >= 0;
            prosesBayarBtn.disabled = !isLunas || (totalTagihan === 0);
            
            // Ganti warna kembalian
            if (isLunas) {
                kembalianDisplay.classList.replace('text-danger', 'text-success');
                prosesBayarBtn.classList.remove('btn-secondary');
                prosesBayarBtn.classList.add('btn-maroon');
            } else {
                kembalianDisplay.classList.replace('text-success', 'text-danger');
                prosesBayarBtn.classList.add('btn-secondary');
                prosesBayarBtn.classList.remove('btn-maroon');
            }
        }

        // Fungsi Format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(Math.abs(angka));
        }
        
        // Initial state
        resetTagihanForm();
        
        // Handle Submit Form
        document.getElementById('form-pembayaran').addEventListener('submit', function(e) {
            if (totalTagihan === 0 || pemeriksaanIdInput.value === '') {
                e.preventDefault();
                showStatusAlert('Mohon pilih tagihan pasien terlebih dahulu.', 'danger');
            } else if (parseFloat(jumlahBayarInput.value) < totalTagihan) {
                 e.preventDefault();
                showStatusAlert('Jumlah pembayaran kurang dari total tagihan.', 'danger');
            } else {
                // SweetAlert Konfirmasi Pembayaran
                e.preventDefault();
                 Swal.fire({
                    title: 'Konfirmasi Pembayaran',
                    html: Anda akan memproses pembayaran untuk tagihan sebesar <b>Rp ${formatRupiah(totalTagihan)}</b>. <br> Jumlah dibayar: <b>Rp ${formatRupiah(parseFloat(jumlahBayarInput.value))}</b>.,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#800000', // Maroon
                    cancelButtonColor: '#6c757d', // Secondary
                    confirmButtonText: 'Ya, Proses Bayar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Lanjutkan submit form setelah konfirmasi
                        e.target.submit();
                    }
                });
            }
        });
    });
</script>
@endpush
