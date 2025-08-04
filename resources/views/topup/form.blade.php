@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row g-4">
        {{-- Kiri: Deskripsi Game --}}
        <div class="col-md-5">
            <div class="card h-100 shadow" style="background-color: #384B70;">
                <div class="card-body p-4 text-white">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('images/games/mlbb.png') }}" alt="{{ $namaGame }}" width="60" class="rounded me-3">
                        <div>
                            <h5 class="mb-0 text-white">{{ $namaGame }}</h5>
                            <small class="text-light">by {{ $publisher ?? 'Moonton' }}</small>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-3">
                        <li><i class="bi bi-shield-check text-success me-2"></i> <span class="text-light">Layanan Terjamin</span></li>
                        <li><i class="bi bi-lightning-charge text-warning me-2"></i> <span class="text-light">Pengiriman Instan</span></li>
                        <li><i class="bi bi-headset text-primary me-2"></i> <span class="text-light">Support 24/7</span></li>
                    </ul>
                    <p class="text-light small mb-3">
                        Top up Diamond {{ $namaGame }} cukup masukkan User ID & Server ID (jika ada), pilih paket, bayar, dan diamond akan langsung masuk.
                    </p>
                    <div class="alert alert-warning small mb-0">
                        Hanya untuk server original. Tidak bisa isi akun Advance Server.
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanan: Form Input --}}
        <div class="col-md-7">
            <div class="card shadow" style="background-color: #384B70;">
                <div class="card-body p-4 text-white">
                    <form method="POST" action="{{ route('topup.store') }}">
                        @csrf
                        <input type="hidden" name="game" value="{{ $namaGame }}">
                        <input type="hidden" name="harga" id="hargaInput">

                        <h5 class="mb-3 text-white">Informasi Pelanggan</h5>
                        <div class="row g-3">
                            <div class="col-md-{{ $type === '2id' ? '6' : '12' }}">
                                <input type="text" name="user_id" class="form-control" style="background-color: #ffffff; color: #000;" placeholder="User ID" required>
                            </div>
                            @if ($type === '2id')
                            <div class="col-md-6">
                                <input type="text" name="server_id" class="form-control" style="background-color: #ffffff; color: #000;" placeholder="Server ID" required>
                            </div>
                            @endif
                            <div class="col-12">
                                <input type="text" name="whatsapp" class="form-control" style="background-color: #ffffff; color: #000;" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-2 text-white">Pilih Nominal Top Up</h5>
                        <div class="row g-2">
                            @foreach ($list as $val)
                                @php
                                    $harga_bersih = (int) preg_replace('/\D/', '', $val['hrg']);
                                @endphp
                                <div class="col-6 col-md-4">
                                    <button 
                                        type="button" 
                                        class="btn btn-outline-light w-100 pilih-nominal text-start" 
                                        data-nominal="{{ $val['nama'] }}" 
                                        data-harga="{{ $harga_bersih }}">
                                        <div class="small">{{ $val['nama'] }}</div>
                                        <div class="fw-semibold">Rp {{ number_format($harga_bersih, 0, ',', '.') }}</div>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <label for="nominalInput" class="form-label text-white">Paket Terpilih</label>
                            <input type="text" name="nominal" id="nominalInput" class="form-control" style="background-color: #ffffff; color: #000;" readonly required>
                        </div>

                        <div class="mt-4 d-grid">
                            <button type="submit" class="btn fw-bold" style="background-color: #ffffff; color: #000;">Kirim Pesanan</button>
                        </div>

                        {{-- Metode Pembayaran QRIS --}}
                        <div class="mt-5">
                            <h6 class="text-white">QRIS</h6>
                            <div class="rounded p-3 d-flex justify-content-between align-items-center mb-3" style="background-color: #fffefe;">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/qris.png') }}" alt="QRIS" width="40" class="me-3">
                                    <span class="fw-semibold text-dark">QRIS</span>
                                </div>
                                <span class="fw-bold text-dark">Rp 3.210.556</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Script --}}
        <script>
            document.querySelectorAll('.pilih-nominal').forEach(button => {
                button.addEventListener('click', function () {
                    const nominal = this.getAttribute('data-nominal');
                    const harga = this.getAttribute('data-harga');

                    document.getElementById('nominalInput').value = nominal;
                    document.getElementById('hargaInput').value = harga;

                    document.querySelectorAll('.pilih-nominal').forEach(btn => {
                        btn.classList.remove('btn-info');
                        btn.classList.add('btn-outline-light');
                    });

                    this.classList.remove('btn-outline-light');
                    this.classList.add('btn-info');
                });
            });
        </script>
    </div>
</div>
@endsection
