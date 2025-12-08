@extends('layout')

@section('title','Kasir - Struk')

@section('content')
<div class="container py-4">
    <div class="card p-3 mx-auto" style="max-width:480px;">
        <div class="text-center mb-3">
            <h5 class="mb-0">KLINIK XYZ</h5>
            <small>Struk Pembayaran Resep</small>
        </div>

        <div>
            <div><strong>No. Resep:</strong> {{ $resep->no_resep }}</div>
            <div><strong>Pasien:</strong>
                @if ($resep->pemeriksaan && $resep->pemeriksaan->kunjungan)
                    {{ $resep->pemeriksaan->kunjungan->pasien->nama ?? '-' }} ({{ $resep->pemeriksaan->kunjungan->pasien->no_rm ?? '-' }})
                @else
                    {{ $resep->pemeriksaan->nama ?? '-' }} ({{ $resep->pemeriksaan->no_rm ?? '-' }})
                @endif
            </div>
            <div><strong>Tanggal:</strong> {{ $resep->created_at->format('d-m-Y H:i') }}</div>
        </div>

        <hr>
        <table class="table table-borderless table-sm">
            <thead>
                <tr><th>Nama Obat</th><th class="text-end">Qty</th><th class="text-end">Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach($items as $it)
                <tr>
                    <td>{{ $it['name'] ?? '-' }}</td>
                    <td class="text-end">{{ $it['qty'] ?? 0 }}</td>
                    <td class="text-end">Rp {{ number_format(((int)($it['qty']??0)) * ((float)($it['price']??0)),0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-end">Total</th>
                    <th></th>
                    <th class="text-end">Rp {{ number_format($resep->total_biaya,0,',','.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="text-center mt-3">
            <button class="btn btn-primary" onclick="window.print()">Cetak Struk</button>
            <a href="{{ route('kasir.pembayaran', ['resepId' => $resep->id]) }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
