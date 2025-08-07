@extends('admin.admin')

@section('title', 'Edit Metode Pembayaran')

@section('content')
<h3>Edit Metode Pembayaran</h3>

<form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $pembayaran->nama }}" required>
    </div>
    <div class="mb-3">
        <label>Logo Saat Ini</label><br>
        <img src="{{ asset('storage/' . $pembayaran->logo) }}" width="60"><br>
        <label>Ganti Logo (opsional)</label>
        <input type="file" name="logo" class="form-control">
    </div>
    <div class="mb-3">
        <label>Tipe</label>
        <select name="tipe" class="form-control" required>
            @foreach (['QRIS', 'e-wallet', 'store', 'VA'] as $tipe)
                <option value="{{ $tipe }}" {{ $pembayaran->tipe == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Biaya Admin</label>
        <input type="number" name="admin" class="form-control" value="{{ $pembayaran->admin }}" required>
    </div>
    <div class="mb-3">
        <label>Tipe Admin</label>
        <select name="tipe_admin" class="form-control" required>
            <option value="1" {{ $pembayaran->tipe_admin ? 'selected' : '' }}>Rupiah</option>
            <option value="0" {{ !$pembayaran->tipe_admin ? 'selected' : '' }}>Persen</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="1" {{ $pembayaran->status ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ !$pembayaran->status ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
