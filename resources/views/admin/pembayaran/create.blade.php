@extends('admin.admin')

@section('title', 'Tambah Pembayaran')

@section('content')
<h3>Tambah Metode Pembayaran</h3>
<form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Nama Pembayaran</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Logo</label>
        <input type="file" name="logo" class="form-control" accept="image/*" required>
    </div>
    <div class="mb-3">
        <label>Tipe</label>
        <select name="tipe" class="form-control" required>
            <option value="QRIS">QRIS</option>
            <option value="e-wallet">E-Wallet</option>
            <option value="store">Store</option>
            <option value="VA">Virtual Account</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Biaya Admin</label>
        <input type="number" step="0.01" name="admin" class="form-control" placeholder="Contoh: 0.7 atau 4000" required>
    </div>
    <div class="mb-3">
        <label>Tipe Admin</label>
        <select name="tipe_admin" class="form-control" required>
            <option value="persen">Persentase (%)</option>
            <option value="rupiah">Nominal (Rp)</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
