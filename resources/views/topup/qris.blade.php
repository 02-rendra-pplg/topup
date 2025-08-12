@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h4 class="mb-3">QRIS Payment</h4>

    {{-- Pesan Error --}}
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

        $qrCode  = $qrisData['qris'] ?? $qrisData['qrCode'] ?? null;
        $expired = $qrisData['expired'] ?? $qrisData['expire_at'] ?? null;
        $total   = $qrisData['total'] ?? null;
        $trxId   = $id ?? $qrisData['id'] ?? 'N/A';
    @endphp

    @if ($qrCode)
        <div class="card p-4 shadow text-center">
            <p class="mb-1">ID Transaksi: <strong>{{ $trxId }}</strong></p>
            
            @if ($total)
                <p class="mb-1">Total: <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></p>
            @endif

            @if ($expired)
                <p class="mb-3 text-danger">
                    Berlaku sampai: <strong>{{ $expired }}</strong>
                </p>
            @endif

            {{-- Tampilkan QR Code --}}
            @if (Str::startsWith($qrCode, 'http'))
                {{-- Jika sudah berupa URL --}}
                <img src="{{ $qrCode }}" 
                     alt="QR Code" 
                     class="img-fluid mb-3" 
                     style="max-width: 300px;">
            @else
                {{-- Jika berupa string payload QRIS --}}
                {!! QrCode::size(300)->generate($qrCode) !!}
            @endif

            <p class="mt-3">Silakan scan QR Code di atas untuk melakukan pembayaran.</p>
        </div>
    @else
        <div class="alert alert-warning">
            QR Code tidak tersedia. Silakan coba lagi.
        </div>
    @endif
</div>
@endsection
