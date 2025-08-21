@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row g-4">

        <div class="col-md-5">
            <div class="card h-100 shadow" style="background-color: #1f2937; border-radius: 12px;">
                <div class="card-body p-4 text-white">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('storage/'.$game->logo) }}" alt="{{ $game->name }}" width="60" class="rounded me-3">
                        <div>
                            <h5 class="mb-0 text-white">{{ $game->name }}</h5>
                            <span class="badge" style="background-color: #484bec;">{{ $game->publisher ?? 'Tidak diketahui' }}</span>
                        </div>
                    </div>

                    <div class="row mb-3 text-white small">
                        <div class="col-6 mb-2"><i class="bi bi-shield-check me-2 text-success"></i> Jaminan Layanan</div>
                        <div class="col-6 mb-2"><i class="bi bi-headset me-2 text-primary"></i> Layanan Pelanggan 24/7</div>
                        <div class="col-6 mb-2"><i class="bi bi-credit-card-2-front me-2 text-warning"></i> Pembayaran Aman</div>
                        <div class="col-6 mb-2"><i class="bi bi-lightning-charge me-2 text-danger"></i> Pengiriman Instant</div>
                    </div>

                    <hr class="border-light">

                    <p class="text-light small mb-3">
                        Top up {{ $game->name }} hanya dalam hitungan detik!<br>
                        Masukkan {{ $game->tipe == 2 ? 'User ID & Server ID' : 'User ID' }}, pilih jumlah, lakukan pembayaran, dan item langsung masuk ke akun Anda.
                    </p>

                    <div class="alert alert-warning text-dark small mb-0">
                        Khusus Server Original, Tidak Bisa Isi Advance Server!
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow" style="background-color: #1f2937;">
                <div class="card-body p-4 text-white">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('orders.store') }}" id="topupForm">
                        @csrf

                        <input type="hidden" name="game_id" value="{{ $game->id }}">
                        <input type="hidden" name="game_name" value="{{ $game->name }}">
                        <input type="hidden" name="price" id="priceInput" required>
                        <input type="hidden" name="nominal" id="nominalInput" required>
                        <input type="hidden" name="kode_produk" id="produk" required>
                        <input type="hidden" name="payment_method" id="payment_method">

                        <h5 class="mb-3 text-white">Informasi Pelanggan</h5>
                        <div class="row g-3">
                            <div class="col-md-{{ $game->tipe == 2 ? '6' : '12' }}">
                                <input type="text" name="user_id" class="form-control bg-white text-dark"
                                       placeholder="User ID" required>
                            </div>

                            @if ($game->tipe == 2)
                            <div class="col-md-6">
                                <input type="text" name="server_id" class="form-control bg-white text-dark"
                                       placeholder="Server ID" required>
                            </div>
                            @endif

                            <div class="col-12">
                                <input type="text" name="whatsapp" class="form-control bg-white text-dark"
                                       placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-2 text-white">Pilih Nominal Top Up</h5>
                        <div class="row g-2">
                            @foreach ($list as $val)
                                @php
                                    $harga_bersih = (int) preg_replace('/\D/', '', $val['hrg']);
                                    $logo = $game->logo_diamond ?? null;

                                    if (Str::contains(strtolower($val['nama']), 'weekly') && $game->logo_weekly) {
                                        $logo = $game->logo_weekly;
                                    } elseif (Str::contains(strtolower($val['nama']), 'member') && $game->logo_member) {
                                        $logo = $game->logo_member;
                                    }
                                @endphp

                                <div class="col-6 col-md-4">
                                    <button type="button"
                                        class="btn btn-outline-light w-100 pilih-nominal d-flex align-items-center p-2"
                                        data-nominal="{{ $val['nama'] }}"
                                        data-harga="{{ $harga_bersih }}"
                                        data-kode="{{ $val['kode'] }}"
                                        style="border-radius: 12px; transition: all 0.2s ease;">

                                        <div class="flex-shrink-0 me-3">
                                            @if ($logo)
                                                <img src="{{ asset('storage/' . $logo) }}"
                                                    alt="Logo {{ $val['nama'] }}"
                                                    style="width:40px; height:40px; object-fit:contain;">
                                            @endif
                                        </div>

                                        <div class="text-start">
                                            <div class="fw-semibold text-white" style="font-size: 14px;">
                                                {{ $val['nama'] }}
                                            </div>
                                            <div class="fw-bold text-warning" style="font-size: 13px;">
                                                Rp {{ number_format($harga_bersih, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5">
                            <h6 class="text-white">Metode Pembayaran</h6>
                            @foreach($pembayarans as $pay)
                                <div class="rounded p-3 d-flex justify-content-between align-items-center mb-3 pilih-pembayaran border border-2"
                                    style="background-color: #fffefe; cursor: pointer;"
                                    data-id="{{ $pay->id }}"
                                    data-nama="{{ $pay->nama }}"
                                    data-admin="{{ $pay->admin }}"
                                    data-tipe-admin="{{ $pay->tipe_admin }}">

                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/'.$pay->logo) }}" alt="{{ $pay->nama }}" width="40" class="me-3">
                                        <span class="fw-semibold text-dark">{{ $pay->nama }}</span>
                                    </div>
                                    <span class="fw-bold text-dark">
                                        @if($pay->tipe_admin === 'persen')
                                            +{{ $pay->admin }}%
                                        @else
                                            +Rp {{ number_format($pay->admin,0,',','.') }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-grid">
                            <button type="button" id="btnKirimPesanan" class="btn fw-bold" style="background-color: #ffffff; color: #000;">Kirim Pesanan</button>
                        </div>
                    </form>

                    <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content text-dark">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Pembelian</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Game:</strong> {{ $game->name }}</p>
                                    <p><strong>User ID:</strong> <span id="konfirmasiUserID"></span></p>
                                    @if ($game->tipe == 2)
                                    <p><strong>Server ID:</strong> <span id="konfirmasiServerID"></span></p>
                                    @endif
                                    <p><strong>Nominal:</strong> <span id="konfirmasiNominal"></span></p>
                                    <p><strong>Harga:</strong> <span id="konfirmasiHarga"></span></p>
                                    <p><strong>Metode Pembayaran:</strong> <span id="konfirmasiPembayaran"></span></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" form="topupForm" class="btn btn-primary">Bayar Sekarang</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pilih-nominal:hover,
.pilih-nominal:focus,
.pilih-nominal.active {
    background-color: #3498db !important;
    color: #fff !important;
    border-color: #3498db !important;
}
.pilih-pembayaran.active {
    background-color: #cce5ff !important;
    border-color: #007bff !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nominalBtns  = document.querySelectorAll('.pilih-nominal');
    const pembayaranBtns = document.querySelectorAll('.pilih-pembayaran');

    const priceInput   = document.getElementById('priceInput');
    const nominalInput = document.getElementById('nominalInput');
    const produk       = document.getElementById('produk');
    const pembayaranInput = document.getElementById('payment_method');

    nominalBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const nominal = this.dataset.nominal;
            const harga   = this.dataset.harga;
            const kode    = this.dataset.kode;

            produk.value = kode;
            nominalInput.value = nominal;
            priceInput.value   = harga;

            nominalBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    pembayaranBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            pembayaranBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            pembayaranInput.value = btn.dataset.id;
        });
    });

    document.getElementById('btnKirimPesanan').addEventListener('click', function () {
        const userID   = document.querySelector('[name="user_id"]').value.trim();
        const serverID = document.querySelector('[name="server_id"]')?.value.trim() || '-';
        const whatsapp = document.querySelector('[name="whatsapp"]').value.trim();
        const nominal  = nominalInput.value.trim();
        const harga    = parseInt(priceInput.value || 0, 10);
        const pembayaranNama = document.querySelector('.pilih-pembayaran.active span.fw-semibold')?.innerText || '';

        if (!userID || !whatsapp || !nominal || !harga) {
            alert('Mohon lengkapi semua data dan pilih nominal.');
            return;
        }
        if (!pembayaranInput.value) {
            alert('Silakan pilih metode pembayaran.');
            return;
        }

        document.getElementById('konfirmasiUserID').innerText  = userID;
        if (document.getElementById('konfirmasiServerID')) {
            document.getElementById('konfirmasiServerID').innerText = serverID;
        }
        document.getElementById('konfirmasiNominal').innerText = nominal;
        document.getElementById('konfirmasiHarga').innerText   = formatRupiah(harga);
        document.getElementById('konfirmasiPembayaran').innerText = pembayaranNama;

        new bootstrap.Modal(document.getElementById('modalKonfirmasi')).show();
    });

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }
});
</script>
@endsection