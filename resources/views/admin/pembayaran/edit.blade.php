@extends('admin.admin')

@section('title', 'Edit Pembayaran')

@section('content')
<h3>Edit Metode Pembayaran</h3>
<form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Nama Pembayaran</label>
        <input type="text" name="nama" value="{{ $pembayaran->nama }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Logo (biarkan kosong jika tidak diubah)</label>
        <input type="file" name="logo" class="form-control" accept="image/*">
    </div>
    <div class="mb-3">
        <label>Tipe</label>
        <select name="tipe" class="form-control" required>
            <option value="QRIS" {{ $pembayaran->tipe == 'QRIS' ? 'selected' : '' }}>QRIS</option>
            <option value="e-wallet" {{ $pembayaran->tipe == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
            <option value="store" {{ $pembayaran->tipe == 'store' ? 'selected' : '' }}>Store</option>
            <option value="VA" {{ $pembayaran->tipe == 'VA' ? 'selected' : '' }}>Virtual Account</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Biaya Admin</label>
        <input type="number" name="admin" class="form-control" step="0.01"
            value="{{ old('admin', $pembayaran->admin) }}" required>
    </div>
    <div class="mb-3">
        <label>Tipe Admin</label>
        <select name="tipe_admin" class="form-control" required>
            <option value="persen" {{ $pembayaran->tipe_admin == 'persen' ? 'selected' : '' }}>Persentase (%)</option>
            <option value="rupiah" {{ $pembayaran->tipe_admin == 'rupiah' ? 'selected' : '' }}>Nominal (Rp)</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="1" {{ $pembayaran->status == 1 ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $pembayaran->status == 0 ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
