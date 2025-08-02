@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row g-4">
        {{-- Kiri: Deskripsi Game --}}
        <div class="col-md-5">
            <div class="card p-4 h-100 shadow">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('images/games/mlbb.png') }}" alt="{{ $namaGame }}" width="60" class="rounded me-3">
                    <div>
                        <h5 class="mb-0">{{ $namaGame }}</h5>
                        <small class="text-muted">by {{ $publisher ?? 'Moonton' }}</small>
                    </div>
                </div>
                <ul class="list-unstyled mb-3">
                    <li><i class="bi bi-shield-check text-success me-2"></i> Layanan Terjamin</li>
                    <li><i class="bi bi-lightning-charge text-warning me-2"></i> Pengiriman Instan</li>
                    <li><i class="bi bi-headset text-primary me-2"></i> Support 24/7</li>
                </ul>
                <p class="text-muted small">
                    Top up Diamond {{ $namaGame }} cukup masukkan User ID & Server ID (jika ada), pilih paket, bayar, dan diamond akan langsung masuk.
                </p>
                <div class="alert alert-warning small mt-3 mb-0">
                    Hanya untuk server original. Tidak bisa isi akun Advance Server.
                </div>
            </div>
        </div>

        {{-- Kanan: Form Input --}}
        <div class="col-md-7">
            <div class="card p-4 shadow">
                <form method="POST" action="{{ route('topup.store') }}">
                    @csrf
                    <input type="hidden" name="game" value="{{ $namaGame }}">
                    <input type="hidden" name="harga" id="hargaInput">

                    <h5 class="mb-3">Informasi Pelanggan</h5>
                    <div class="row g-3">
                        <div class="col-md-{{ $type === '2id' ? '6' : '12' }}">
                            <input type="text" name="user_id" class="form-control" placeholder="User ID" required>
                        </div>
                        @if ($type === '2id')
                        <div class="col-md-6">
                            <input type="text" name="server_id" class="form-control" placeholder="Server ID" required>
                        </div>
                        @endif
                        <div class="col-12">
                            <input type="text" name="whatsapp" class="form-control" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-2">Pilih Nominal Top Up</h5>
                    <div class="row g-2">
                        @foreach ($list as $val)
                            @php
                                $harga_bersih = (int) preg_replace('/\D/', '', $val['hrg']);
                            @endphp
                            <div class="col-6 col-md-4">
                                <button 
                                    type="button" 
                                    class="btn btn-outline-secondary w-100 pilih-nominal text-start" 
                                    data-nominal="{{ $val['nama'] }}" 
                                    data-harga="{{ $harga_bersih }}">
                                    <div class="small">{{ $val['nama'] }}</div>
                                    <div class="fw-semibold">Rp {{ number_format($harga_bersih, 0, ',', '.') }}</div>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <label for="nominalInput" class="form-label">Paket Terpilih</label>
                        <input type="text" name="nominal" id="nominalInput" class="form-control" readonly required>
                    </div>

                    <div class="mt-4 d-grid">
                        <button type="submit" class="btn btn-primary">Kirim Pesanan</button>
                    </div>
                </form>
            </div>
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

            // Highlight
            document.querySelectorAll('.pilih-nominal').forEach(btn => 
                btn.classList.remove('btn-success')
            );
            this.classList.add('btn-success');
        });
    });
</script>
@endsection
