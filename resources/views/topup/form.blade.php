@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row g-4">

        {{-- Kiri: Deskripsi Game --}}
        <div class="col-md-5">
            <div class="card h-100 shadow" style="background-color: #1f2937; border-radius: 12px;">
                <div class="card-body p-4 text-white">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('storage/'.$game->logo) }}" alt="{{ $game->name }}" width="60" class="rounded me-3">
                        <div>
                            <span class="badge" style="background-color: #484bec;">{{ $publisher ?? 'Moonton' }}</span>
                            <h5 class="mb-0 text-white">{{ $game->name }}</h5>
                        </div>
                    </div>

                    <div class="row mb-3 text-white small">
                        <div class="col-6 d-flex align-items-center mb-2"><i class="bi bi-shield-check me-2 text-success"></i> Jaminan Layanan</div>
                        <div class="col-6 d-flex align-items-center mb-2"><i class="bi bi-headset me-2 text-primary"></i> Layanan Pelanggan 24/7</div>
                        <div class="col-6 d-flex align-items-center mb-2"><i class="bi bi-credit-card-2-front me-2 text-warning"></i> Pembayaran Aman</div>
                        <div class="col-6 d-flex align-items-center mb-2"><i class="bi bi-lightning-charge me-2 text-danger"></i> Pengiriman Instant</div>
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

        {{-- Kanan: Form --}}
        <div class="col-md-7">
            <div class="card shadow" style="background-color: #1f2937;">
                <div class="card-body p-4 text-white">

                    {{-- Error validasi --}}
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

                        {{-- Field wajib --}}
                        <input type="hidden" name="game_id" value="{{ $game->id }}">
                        <input type="hidden" name="game_name" value="{{ $game->name }}">
                        <input type="hidden" name="price" id="priceInput" required>
                        <input type="hidden" name="nominal" id="nominalInput" required>
                        <input type="hidden" name="kode_produk" id="produk" required>
                        <input type="hidden" name="payment_method" id="paymentMethod" value="qris">

                        {{-- Informasi Pelanggan --}}
                        <h5 class="mb-3 text-white">Informasi Pelanggan</h5>
                        <div class="row g-3">
                            {{-- User ID --}}
                            <div class="col-md-{{ $game->tipe == 2 ? '6' : '12' }}">
                                <input type="text" name="user_id" class="form-control bg-white text-dark"
                                       placeholder="User ID" required>
                            </div>

                            {{-- Server ID kalau tipe = 2 --}}
                            @if ($game->tipe == 2)
                            <div class="col-md-6">
                                <input type="text" name="server_id" class="form-control bg-white text-dark"
                                       placeholder="Server ID" required>
                            </div>
                            @endif

                            {{-- WhatsApp --}}
                            <div class="col-12">
                                <input type="text" name="whatsapp" class="form-control bg-white text-dark"
                                       placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        {{-- Pilih Nominal --}}
                        <h5 class="mt-4 mb-2 text-white">Pilih Nominal Top Up</h5>
                        <div class="row g-2">
                            @foreach ($list as $val)
                                @php $harga_bersih = (int) preg_replace('/\D/', '', $val['hrg']); @endphp
                                <div class="col-6 col-md-4">
                                    <button type="button"
                                        class="btn btn-outline-light w-100 pilih-nominal text-start"
                                        data-nominal="{{ $val['nama'] }}"
                                        data-harga="{{ $harga_bersih }}"
                                        data-kode="{{ $val['kode']; }}">
                                        <div class="small">{{ $val['nama'] }}</div>
                                        <div class="fw-semibold">Rp {{ number_format($harga_bersih, 0, ',', '.') }}</div>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="mt-5">
                            <h6 class="text-white">Metode Pembayaran</h6>
                            <div id="qrisBox" class="rounded p-3 d-flex justify-content-between align-items-center mb-3 qris-toggle border border-2"
                                 style="background-color: #fffefe; cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/qris.png') }}" alt="QRIS" width="40" class="me-3">
                                    <span class="fw-semibold text-dark">QRIS</span>
                                </div>
                                <span class="fw-bold text-dark" id="qrisHarga">Rp 0</span>
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="mt-4 d-grid">
                            <button type="button" id="btnKirimPesanan" class="btn fw-bold" style="background-color: #ffffff; color: #000;">Kirim Pesanan</button>
                        </div>
                    </form>

                    {{-- Modal Konfirmasi --}}
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
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" form="topupForm" class="btn btn-primary">Bayar Sekarang</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- /Modal --}}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.qris-selected { border-color: #ff9800 !important; box-shadow: 0 0 5px rgba(255, 152, 0, 0.5); }
.pilih-nominal.active { background-color: #ff9800; color: #fff; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nominalBtns  = document.querySelectorAll('.pilih-nominal');
    const qrisBox      = document.getElementById('qrisBox');
    const priceInput   = document.getElementById('priceInput');
    const nominalInput = document.getElementById('nominalInput');
    const payMethodInp = document.getElementById('paymentMethod');
    const produk       = document.getElementById('produk');

    nominalBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const nominal = this.dataset.nominal;
            const harga   = this.dataset.harga;
            const kode    = this.dataset.kode;
            produk.value = kode;
            nominalInput.value = nominal;
            priceInput.value   = harga;
            document.getElementById('qrisHarga').innerText = formatRupiah(harga);
            nominalBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    qrisBox.addEventListener('click', function () {
        document.querySelectorAll('.qris-toggle').forEach(el => el.classList.remove('qris-selected'));
        this.classList.add('qris-selected');
        payMethodInp.value = 'qris';
    });

    document.getElementById('btnKirimPesanan').addEventListener('click', function () {
        const userID   = document.querySelector('[name="user_id"]').value.trim();
        const serverID = document.querySelector('[name="server_id"]')?.value.trim() || '-';
        const whatsapp = document.querySelector('[name="whatsapp"]').value.trim();
        const nominal  = nominalInput.value.trim();
        const harga    = parseInt(priceInput.value || 0, 10);

        if (!userID || !whatsapp || !nominal || !harga) {
            alert('Mohon lengkapi semua data dan pilih nominal.');
            return;
        }
        document.getElementById('konfirmasiUserID').innerText  = userID;
        if (document.getElementById('konfirmasiServerID')) {
            document.getElementById('konfirmasiServerID').innerText = serverID;
        }
        document.getElementById('konfirmasiNominal').innerText = nominal;
        document.getElementById('konfirmasiHarga').innerText   = formatRupiah(harga);

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
