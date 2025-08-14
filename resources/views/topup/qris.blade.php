@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h4 class="mb-3">QRIS Payment</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        use Illuminate\Support\Str;

        $qrCode  = $qrisData['image'] ?? $qrisData['qris'] ?? null;
        $expired = $qrisData['expired'] ?? null;
        $total   = $qrisData['total'] ?? null;
        $trxId   = $id ?? $qrisData['id'] ?? 'N/A';
    @endphp

    @if ($qrCode)
        <div class="card p-4 shadow text-center">
            <p>ID Transaksi: <strong>{{ $trxId }}</strong></p>
            @if ($total)
                <p>Total: <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></p>
            @endif
            @if ($expired)
                <p class="text-danger">Berlaku sampai: <strong>{{ $expired }}</strong></p>
            @endif

            @if (Str::startsWith($qrCode, 'http'))
                <img src="{{ $qrCode }}" alt="QR Code" class="img-fluid mb-3" style="max-width: 300px;">
            @else
                {!! QrCode::size(300)->generate($qrCode) !!}
            @endif

            <p>Silakan scan QR Code di atas untuk melakukan pembayaran.</p>
        </div>
    @else
        <div class="alert alert-warning">QR Code tidak tersedia. Silakan coba lagi.</div>
    @endif
</div>
@endsection
