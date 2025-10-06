@extends('admin.admin')

@section('title', 'Tambah Pembayaran')

@section('content')
<h3>Tambah Metode Pembayaran</h3>
<form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data" id="formPembayaran">
    @csrf
    <div class="mb-3">
        <label>Nama Pembayaran</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        @error('nama')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label>Logo</label>
        <input type="file" name="logo" class="form-control" accept="image/*" required>
        @error('logo')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label>Tipe</label>
        <select name="tipe" class="form-control" required>
            <option value="QRIS">QRIS</option>
            <option value="e-wallet">E-Wallet</option>
            <option value="store">Store</option>
            <option value="VA">Virtual Account</option>
        </select>
        @error('tipe')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label>Biaya Admin</label>
        <input type="number" name="admin" id="adminInput" class="form-control" step="0.01"
            placeholder="contoh: 0.7 untuk 0.7% atau 4000 untuk Rp 4000" required>
        <div id="adminError" class="text-danger mt-2" style="display:none;"></div>
        @error('admin')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label>Tipe Admin</label>
        <select name="tipe_admin" class="form-control" id="tipeAdmin" required>
            <option value="persen">Persentase (%)</option>
            <option value="rupiah">Nominal (Rp)</option>
        </select>
        @error('tipe_admin')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
        @error('status')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-success" id="submitBtn">Simpan</button>
    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Batal</a>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipeAdmin = document.getElementById('tipeAdmin');
    const adminInput = document.getElementById('adminInput');
    const adminError = document.getElementById('adminError');
    const submitBtn = document.getElementById('submitBtn');

    function validateAdmin() {
        const type = tipeAdmin.value;
        const value = parseFloat(adminInput.value);
        let error = "";

        if (isNaN(value)) return;

        if (type === 'persen') {
            if (value < 0 || value > 100) {
                error = "Nilai persentase harus antara 0 dan 100.";
            } else if (value > 10) {
                error = "Biasanya persentase di bawah 10%. Pastikan nilai benar.";
            }
        }

        if (type === 'rupiah') {
            if (value < 100) {
                error = "Nominal minimal Rp100.";
            } else if (value < 1000 && value % 1 !== 0) {
                error = "Nominal tidak boleh desimal (gunakan angka bulat).";
            }
        }

        if (error) {
            adminError.textContent = error;
            adminError.style.display = "block";
            submitBtn.disabled = true;
        } else {
            adminError.style.display = "none";
            submitBtn.disabled = false;
        }
    }

    tipeAdmin.addEventListener('change', validateAdmin);
    adminInput.addEventListener('input', validateAdmin);
});
</script>
@endsection
