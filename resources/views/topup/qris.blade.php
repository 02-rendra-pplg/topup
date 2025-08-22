@extends('layouts.app')

@section('content')
<div class="container my-5 d-flex justify-content-center">
    <div class="card shadow-lg p-4 text-center" style="max-width: 500px; border-radius: 20px;">
        
        {{-- Header --}}
        <h4 class="mb-3 fw-bold text-primary">QRIS Payment</h4>

        {{-- ID Transaksi --}}
        <p class="mb-1">ID Transaksi:</p>
        <h5 class="fw-bold">{{ $qrisData['trx_id'] ?? '-' }}</h5>

        {{-- Total --}}
        @if(isset($qrisData['total']))
            <p class="mt-2">Total Pembayaran</p>
            <h4 class="text-success fw-bold">Rp {{ number_format($qrisData['total'], 0, ',', '.') }}</h4>
        @endif

        {{-- Countdown Timer --}}
        @if(isset($qrisData['expired']))
            <p id="countdown" class="text-danger fw-bold mt-3"></p>
        @endif

        {{-- QR Code --}}
        <div class="my-4 bg-white d-inline-block p-3 rounded shadow-sm">
            @php
                $qrCode = $qrisData['qris'] ?? '';
            @endphp

            @if (Str::startsWith($qrCode, 'http'))
                <img src="{{ $qrCode }}" alt="QR Code" class="img-fluid" style="max-width: 280px;">
            @elseif(!empty($qrCode))
                {!! QrCode::size(280)->generate($qrCode) !!}
            @else
                <p class="text-muted">QR Code belum tersedia.</p>
            @endif
        </div>

        <p class="text-muted">Silakan scan menggunakan aplikasi pembayaran:<br> 
            DANA, OVO, GoPay, ShopeePay, BCA, dll.
        </p>

        {{-- Tombol Cek Status --}}
        <a href="{{ route('orders.show', $qrisData['id'] ?? 0) }}" class="btn btn-lg btn-primary w-100 mt-3 rounded-pill fw-bold">
            🔄 Cek Status Pembayaran
        </a>
    </div>
</div>

{{-- Countdown Script --}}
@if(isset($qrisData['expired']))
<script>
    const expiredTime = new Date("{{ $qrisData['expired'] }}").getTime();
    const countdownEl = document.getElementById("countdown");

    const timer = setInterval(() => {
        const now = new Date().getTime();
        const distance = expiredTime - now;

        if (distance <= 0) {
            clearInterval(timer);
            countdownEl.innerHTML = "❌ Waktu pembayaran habis.";
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownEl.innerHTML = `Berlaku sampai: <strong>${minutes}m ${seconds}s</strong>`;
    }, 1000);
</script>
@endif
@endsection
