@extends('layout')

@section('title', 'Apotek - Daftar Transaksi')

@section('content')
<div class="row">
    <!-- CARD 1: DAFTAR TRANSAKSI APOTEK (TETAP) -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary fw-bold">
                    <i class="bi bi-bag-plus-fill me-2"></i> Daftar Transaksi Apotek
                </h5>
                <button class="btn btn-success btn-sm rounded-pill" onclick="alert('Fungsi Tambah Transaksi/Penjualan Obat')">
                    <i class="bi bi-cart-plus me-1"></i> Transaksi Baru
                </button>
            </div>
            <div class="card-body">
                
                @if ($message = session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($message = session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i> {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tabel Daftar Resep Pending/Ready -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>No. Resep</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>Obat (Qty)</th>
                                <th>Total Rp</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reseps as $index => $resep)
                            <tr>
                                <td>{{ $index + 1 }}</td>
<<<<<<< HEAD
                                <td><strong>{{ $resep->no_resep }}</strong></td>
                                <td>{{ $resep->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if ($resep->pemeriksaan && $resep->pemeriksaan->kunjungan)
                                        {{ $resep->pemeriksaan->kunjungan->pasien->nama ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $items = json_decode($resep->items, true);
                                        $itemList = [];
                                        if (is_array($items)) {
                                            foreach ($items as $item) {
                                                $itemList[] = ($item['name'] ?? 'Obat') . ' (' . ($item['qty'] ?? 0) . ')';
                                            }
                                        }
                                    @endphp
                                    {{ implode(', ', $itemList) ?: '-' }}
                                </td>
                                <td>Rp {{ number_format($resep->total_biaya, 0, ',', '.') }}</td>
                                <td>
                                    @if ($resep->status === 'Pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($resep->status === 'Ready')
                                        <span class="badge bg-info">Siap Ambil</span>
                                    @else
                                        <span class="badge bg-success">Lunas</span>
                                    @endif
                                </td>
=======
                                <td>{{ $resep->no_resep }}</td>
                                <td>{{ $resep->created_at->format('d/m/Y') }}</td>
                                <td>{{ $resep->pemeriksaan->nama ?? '-' }}</td>
                                <td>Rp {{ number_format($resep->total_biaya, 0, ',', '.') }}</td>
                                <td><span class="badge {{ $resep->status === 'Lunas' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $resep->status }}</span></td>
>>>>>>> f868db48cec9d34adf8065fb4d9df4824cbf45e4
                                <td class="text-center">
                                    @if ($resep->status === 'Pending')
                                        <form action="{{ route('apotek.proses-resep', $resep->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Proses Resep">
                                                <i class="bi bi-check-lg"></i> Proses
                                            </button>
                                        </form>
                                    @elseif ($resep->status === 'Ready')
                                        <a href="{{ route('kasir.pembayaran', ['resepId' => $resep->id]) }}" class="btn btn-sm btn-primary" title="Ke Kasir">
                                            <i class="bi bi-arrow-right-square"></i> Ke Kasir
                                        </a>
                                    @else
                                        <span class="text-success fw-bold">Sudah Lunas</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
<<<<<<< HEAD
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox"></i> Belum ada resep untuk diproses
                                </td>
=======
                                <td colspan="7" class="text-center text-muted py-4">Belum ada resep yang disimpan</td>
>>>>>>> f868db48cec9d34adf8065fb4d9df4824cbf45e4
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    
    <!-- CARD 2: MANAJEMEN STOK / DAFTAR OBAT BARU -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-success fw-bold">
                    <i class="bi bi-capsule me-2"></i> Manajemen Stok Obat
                </h5>
                <button class="btn btn-primary btn-sm rounded-pill" onclick="alert('Fungsi Tambah Obat Baru')">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Obat
                </button>
            </div>
            <div class="card-body">
                
                <!-- Area Pencarian Obat -->
                <div class="mb-3 d-flex align-items-center">
                    <input type="text" class="form-control me-2" placeholder="Cari Nama Obat, Satuan, atau Supplier...">
                    <button class="btn btn-outline-secondary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                <!-- Tabel Daftar Stok Obat (Placeholder) -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Obat</th>
                                <th>Stok</th>
                                <th>Harga Jual</th>
                                <th>Exp. Date</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contoh Baris Data Obat -->
                            <tr>
                                <td>1</td>
                                <td>Paracetamol 500mg</td>
                                <td><span class="badge bg-success">450 Strip</span></td>
                                <td>Rp 3.500</td>
                                <td>10/2026</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning me-1" title="Edit Obat"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-info text-white" title="Detail Stok"><i class="bi bi-box"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Amoxicillin 250mg</td>
                                <td><span class="badge bg-danger">20 Box</span></td>
                                <td>Rp 12.000</td>
                                <td>01/2025</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning me-1" title="Edit Obat"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-info text-white" title="Detail Stok"><i class="bi bi-box"></i></button>
                                </td>
                            </tr>
                            <!-- Akhir Contoh Baris Data Obat -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- CARD 3: RINGKASAN HARIAN (Opsional) -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 text-secondary fw-bold">
                    <i class="bi bi-graph-up me-2"></i> Ringkasan Penjualan Hari Ini
                </h5>
            </div>
            <div class="card-body d-flex justify-content-around text-center">
                <div class="p-3 border rounded">
                    <h6 class="text-muted">Total Transaksi</h6>
                    <h4 class="text-primary fw-bold">12</h4>
                </div>
                <div class="p-3 border rounded">
                    <h6 class="text-muted">Omset Bruto</h6>
                    <h4 class="text-success fw-bold">Rp 895.000</h4>
                </div>
                <div class="p-3 border rounded">
                    <h6 class="text-muted">Obat Kritis (Stok < 50)</h6>
                    <h4 class="text-danger fw-bold">5 Jenis</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection