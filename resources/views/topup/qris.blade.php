@extends('layouts.app')

@section('content')
<div class="container my-5">

    <div class="text-center mb-4">
        <h4 class="fw-bold text-light">Belum Bayar</h4>
        <p class="text-muted">Selesaikan Pembayaran Sebelum Waktu Habis</p>
    </div>

    <div class="d-flex justify-content-center gap-3 mb-4">
        <div class="count-box text-center">
            <h2 id="hours">00</h2>
            <small>Jam</small>
        </div>
        <div class="count-box text-center">
            <h2 id="minutes">00</h2>
            <small>Menit</small>
        </div>
        <div class="count-box text-center">
            <h2 id="seconds">00</h2>
            <small>Detik</small>
        </div>
    </div>
    <p class="text-danger text-center small">Agar Pesanan Kamu Tidak Expired</p>

    <div class="row g-4">
        <div class="col-md-6 text-center">
            <div class="bg-white p-3 rounded shadow-sm d-inline-block">
                @php $qrCode = $qrisData['qris'] ?? ''; @endphp
                @if (Str::startsWith($qrCode, 'http'))
                    <img src="{{ $qrCode }}" alt="QR Code" class="img-fluid" style="max-width:280px;">
                @elseif(!empty($qrCode))
                    {!! QrCode::size(280)->generate($qrCode) !!}
                @else
                    <p class="text-muted">QR Code belum tersedia.</p>
                @endif
            </div>

            <div class="mt-3">
                <a href="{{ $qrCode }}" download class="btn btn-pink rounded-pill px-4 fw-bold">
                    Unduh kode QR
                </a>
            </div>

            {{-- Tombol tambahan di bawah Unduh QR --}}
            <div class="mt-2 d-flex flex-column gap-2 align-items-center">
                {{-- tombol lihat status pesanan --}}
                <a href="{{ route('orders.status', ['trx_id' => $qrisData['trx_id']]) }}">
                    Masukkan Kode
                </a>


                {{-- tombol kembali ke beranda --}}
                <a href="{{ route('home') }}"
                   class="btn btn-pink rounded-pill px-4 fw-bold">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark text-white border-0 rounded-4 p-3 shadow-sm">

                <div class="row text-center mb-3">
                    <div class="col">
                        <small class="text-muted d-block">Tanggal Pembelian</small>
                        <span>{{ \Carbon\Carbon::parse($qrisData['created_at'] ?? now())->format('m/d/Y') }}</span>
                    </div>
                    <div class="col">
                        <small class="text-muted d-block">Nomor Pesanan</small>
                        <span>{{ $qrisData['trx_id'] ?? '-' }}</span>
                    </div>
                    <div class="col">
                        <small class="text-muted d-block">Metode Pembayaran</small>
                        <span>QRIS Untuk Semua Pembayaran</span>
                    </div>
                    <div class="col">
                        <small class="text-muted d-block">Status Pesanan</small>
                        <span class="fw-bold text-warning">Belum Bayar</span>
                    </div>
                </div>

                <hr class="border-secondary">

                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $qrisData['game_icon'] ?? '/default-game.png' }}"
                         alt="Game" class="rounded me-3" style="width: 50px; height: 50px;">
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $qrisData['game_name'] ?? 'Game' }}</h5>
                    </div>
                </div>

                <ul class="list-unstyled small">
                    <li class="d-flex justify-content-between"><span>Item</span><span>{{ $qrisData['item'] ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between"><span>ID User</span><span>{{ $qrisData['user_id'] ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between"><span>ID Zone</span><span>{{ $qrisData['server_id'] ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between"><span>Harga</span><span>Rp {{ number_format($qrisData['price'] ?? 0,0,',','.') }}</span></li>
                    <li class="d-flex justify-content-between"><span>Fee</span><span>Rp {{ number_format($qrisData['fee'] ?? 0,0,',','.') }}</span></li>
                    <li class="d-flex justify-content-between"><span>Kode Unik</span><span>{{ $qrisData['unique_code'] ?? '-' }}</span></li>
                </ul>

                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total Pembayaran</span>
                    <span class="text-pink">Rp {{ number_format($qrisData['total'] ?? 0,0,',','.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($qrisData['expired']))
<script>
    const expiredTime = new Date("{{ $qrisData['expired'] }}").getTime();
    const timer = setInterval(() => {
        const now = new Date().getTime();
        const distance = expiredTime - now;

        if (distance <= 0) {
            clearInterval(timer);
            document.getElementById("hours").innerText = "00";
            document.getElementById("minutes").innerText = "00";
            document.getElementById("seconds").innerText = "00";
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24))/(1000*60*60));
        const minutes = Math.floor((distance % (1000 * 60 * 60))/(1000*60));
        const seconds = Math.floor((distance % (1000 * 60))/1000);

        document.getElementById("hours").innerText = String(hours).padStart(2,'0');
        document.getElementById("minutes").innerText = String(minutes).padStart(2,'0');
        document.getElementById("seconds").innerText = String(seconds).padStart(2,'0');
    }, 1000);
</script>
@endif

<style>
    body { background-color: #1a1a2e; }
    .count-box {
        background: #2a2a2a;
        border-radius: 10px;
        padding: 10px 20px;
        min-width: 80px;
    }
    .count-box h2 { margin: 0; font-weight: bold; color: #A9B5DF; }
    .btn-pink {
        background: #1B3C53;
        color: white;
        transition: .3s;
    }       
    .btn-pink:hover { background: #A9B5DF; color: #fff; }
    .text-pink { color: #A9B5DF !important; }
</style>
@endsection
