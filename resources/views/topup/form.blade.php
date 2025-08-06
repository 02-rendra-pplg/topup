@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row g-4">
        {{-- Kiri: Deskripsi Game --}}
   <div class="col-md-5">
    <div class="card h-100 shadow" style="background-color: #1f2937; border-radius: 12px;">
        <div class="card-body p-4 text-white">
            {{-- Gambar dan Nama Game --}}
            <div class="d-flex align-items-center mb-3">
                <img src="{{ asset('images/games/mlbb.png') }}" alt="{{ $namaGame }}" width="60" class="rounded me-3">
                <div>
                    <span class="badge bg-pink text-white mb-1" style="background-color: #484bec;">{{ $publisher ?? 'Moonton' }}</span>
                    <h5 class="mb-0 text-white">{{ $namaGame }}</h5>
                </div>
            </div>

            {{-- Fitur / Keunggulan --}}
            <div class="row mb-3 text-white small">
                <div class="col-6 d-flex align-items-center mb-2">
                    <i class="bi bi-shield-check me-2 text-success"></i> Jaminan Layanan
                </div>
                <div class="col-6 d-flex align-items-center mb-2">
                    <i class="bi bi-headset me-2 text-primary"></i> Layanan Pelanggan 24/7
                </div>
                <div class="col-6 d-flex align-items-center mb-2">
                    <i class="bi bi-credit-card-2-front me-2 text-warning"></i> Pembayaran Aman
                </div>
                <div class="col-6 d-flex align-items-center mb-2">
                    <i class="bi bi-lightning-charge me-2 text-danger"></i> Pengiriman Instant
                </div>
            </div>

            <hr class="border-light">

            {{-- Deskripsi --}}
            <p class="text-light small mb-3">
                Top up Diamond {{ $namaGame }} hanya dalam hitungan detik!<br>
                Cukup masukan User ID & Server MLBB Anda, pilih jumlah Diamond yang Anda inginkan, selesaikan pembayaran dan Diamond akan langsung masuk ke akun {{ $namaGame }} Anda.
            </p>

            <div class="alert alert-warning text-dark small mb-0">
                Khusus Server Original, Tidak Bisa Isi Advance Server!
            </div>
        </div>
    </div>
</div>


        {{-- Kanan: Form Input --}}
        <div class="col-md-7">
            <div class="card shadow" style="background-color: #1f2937;">
                <div class="card-body p-4 text-white">
                    <form method="POST" action="{{ route('topup.store') }}">
                        @csrf
                        <input type="hidden" name="game" value="{{ $namaGame }}">
                        <input type="hidden" name="harga" id="hargaInput">
                        <input type="hidden" name="nominal" id="nominalInput" required>

                        {{-- Informasi Pelanggan --}}
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

                        {{-- Pilih Nominal --}}
                        <h5 class="mt-4 mb-2 text-white">Pilih Nominal Top Up</h5>
                        <div class="row g-2">
                            @foreach ($list as $val)
                                @php
                                    $harga_bersih = (int) preg_replace('/\D/', '', $val['hrg']);
                                @endphp
                                <div class="col-6 col-md-4">
                                    <button type="button" class="btn btn-outline-light w-100 pilih-nominal text-start" data-nominal="{{ $val['nama'] }}" data-harga="{{ $harga_bersih }}">
                                        <div class="small">{{ $val['nama'] }}</div>
                                        <div class="fw-semibold">Rp {{ number_format($harga_bersih, 0, ',', '.') }}</div>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="mt-4 d-grid">
                           <button type="button" id="btnKirimPesanan" class="btn fw-bold" style="background-color: #ffffff; color: #000;">Kirim Pesanan</button>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="mt-5">
                            <h6 class="text-white">Metode Pembayaran</h6>
                            <div class="rounded p-3 d-flex justify-content-between align-items-center mb-3 qris-toggle" style="background-color: #fffefe; cursor: pointer;" onclick="toggleQrisDetail()">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/qris.png') }}" alt="QRIS" width="40" class="me-3">
                                    <span class="fw-semibold text-dark">QRIS</span>
                                </div>
                                <span class="fw-bold text-dark" id="qrisHarga">Rp 0</span>
                            </div>

                            {{-- Detail QRIS --}}
                            <div id="qrisDetail" style="display: none;">
                                <div class="bg-white rounded p-3 text-center">
                                    <p class="mb-2 fw-semibold text-dark">Silakan scan kode QR di bawah ini untuk membayar:</p>
                                    {{-- <img src="{{ asset('images/qr-sample.png') }}" alt="QRIS Code" width="200"> --}}
                                    <p class="mt-2 text-muted small mb-0">QRIS akan menyesuaikan dengan nominal yang kamu pilih.</p>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Modal Konfirmasi -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-dark">
      <div class="modal-header">
        <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Pembelian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p><strong>Game:</strong> {{ $namaGame }}</p>
        <p><strong>User ID:</strong> <span id="konfirmasiUserID"></span></p>
        @if ($type === '2id')
        <p><strong>Server ID:</strong> <span id="konfirmasiServerID"></span></p>
        @endif
        <p><strong>Nominal:</strong> <span id="konfirmasiNominal"></span></p>
        <p><strong>Harga:</strong> <span id="konfirmasiHarga"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitForm()">Bayar Sekarang</button>
      </div>
    </div>
  </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const btnKirim = document.getElementById('btnKirimPesanan');
    const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));

    btnKirim.addEventListener('click', () => {
        const userID = document.querySelector('[name="user_id"]').value;
        const serverID = document.querySelector('[name="server_id"]')?.value || '-';
        const whatsapp = document.querySelector('[name="whatsapp"]').value;
        const nominal = document.getElementById('nominalInput').value;
        const harga = parseInt(document.getElementById('hargaInput').value || 0);

        if (!userID || !whatsapp || !nominal || !harga) {
            alert('Mohon lengkapi semua data sebelum melanjutkan.');
            return;
        }

        // Isi modal
        document.getElementById('konfirmasiUserID').innerText = userID;
        if (document.getElementById('konfirmasiServerID')) {
            document.getElementById('konfirmasiServerID').innerText = serverID;
        }
        document.getElementById('konfirmasiNominal').innerText = nominal;
        document.getElementById('konfirmasiHarga').innerText = new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
        }).format(harga);

        modal.show();
    });

    function submitForm() {
        // Tampilkan QRIS
        const qrisDetail = document.getElementById('qrisDetail');
        qrisDetail.style.display = 'block';

        // Tutup modal
        const modalElement = document.getElementById('modalKonfirmasi');
        const bsModal = bootstrap.Modal.getInstance(modalElement);
        bsModal.hide();

        // (Opsional) Scroll ke QRIS
        qrisDetail.scrollIntoView({ behavior: 'smooth' });

        // (Opsional) Simpan transaksi ke server via AJAX, jika tidak ingin reload halaman
        // Jika tetap ingin kirim form ke server, bisa uncomment baris di bawah ini:
        // document.querySelector('form').submit();
    }
</script>


@endsection
