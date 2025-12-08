@extends('layout')

@section('title','Kasir - Invoice')

@section('content')
<div class="container py-4">
    <h2 class="text-maroon">Invoice - {{ $resep->no_resep }}</h2>

    <div class="card p-3 mb-3">
        <div><strong>Pasien:</strong> 
            @if ($resep->pemeriksaan && $resep->pemeriksaan->kunjungan)
                {{ $resep->pemeriksaan->kunjungan->pasien->nama ?? '-' }} ({{ $resep->pemeriksaan->kunjungan->pasien->no_rm ?? '-' }})
            @else
                {{ $resep->pemeriksaan->nama ?? '-' }} ({{ $resep->pemeriksaan->no_rm ?? '-' }})
            @endif
        </div>
        <div><strong>Tanggal:</strong> {{ $resep->created_at->format('d-m-Y H:i') }}</div>
    </div>

    <div class="card p-3 mb-3">
        <h5>Rincian Obat</h5>
        @php $items = json_decode($resep->items, true) ?: []; @endphp
        <table class="table table-striped">
            <thead>
                <tr><th>Nama Obat</th><th class="text-end">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach($items as $it)
                <tr>
                    <td>{{ $it['name'] ?? ($it ?? '-') }}</td>
                    <td class="text-end">{{ $it['qty'] ?? '-' }}</td>
                    <td class="text-end">Rp {{ number_format($it['price'] ?? 0,2,',','.') }}</td>
                    <td class="text-end">Rp {{ number_format( ((int)($it['qty']??0)) * ((float)($it['price']??0)),2,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th class="text-end">Rp {{ number_format($resep->total_biaya,2,',','.') }}</th>
                </tr>
            </tfoot>
        </table>

        <form method="POST" action="{{ route('kasir.bayar', ['resepId' => $resep->id]) }}">
            @csrf
            <a href="{{ route('kasir.print', $resep->id) }}" class="btn btn-outline-primary me-2" target="_blank">Cetak Struk</a>
            <button type="submit" class="btn btn-success">Tandai Lunas / Bayar</button>
            <a href="{{ route('kasir.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
