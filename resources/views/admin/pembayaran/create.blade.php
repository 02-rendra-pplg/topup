@extends('admin.admin')

@section('title', 'Tambah Metode Pembayaran')

@section('content')
<h3>Tambah Metode Pembayaran</h3>

<form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Logo</label>
        <input type="file" name="logo" class="form-control" required>
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
        <input type="number" name="admin" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label>Tipe Admin</label>
        <select name="tipe_admin" class="form-control" required>
            <option value="1">Rupiah</option>
            <option value="0">Persen</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
