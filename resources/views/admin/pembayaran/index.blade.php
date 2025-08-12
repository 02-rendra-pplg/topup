@extends('admin.admin')

@section('title', 'Metode Pembayaran')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Metode Pembayaran</h3>
    <a href="{{ route('pembayaran.create') }}" class="btn btn-primary">Tambah Metode</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Logo</th>
            <th>Tipe</th>
            <th>Biaya Admin</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pembayarans as $pembayaran)
            <tr>
                <td>{{ $pembayaran->nama }}</td>
                <td>
                    <img src="{{ asset('storage/' . $pembayaran->logo) }}" width="60" alt="{{ $pembayaran->nama }}">
                </td>
                <td>{{ $pembayaran->tipe }}</td>
                <td>
                    @if($pembayaran->tipe_admin === 'persen')
                        {{ rtrim(rtrim(number_format($pembayaran->admin, 2), '0'), '.') }}%
                    @else
                        Rp {{ number_format($pembayaran->admin, 0, ',', '.') }}
                    @endif
                </td>
                <td>
                    <span class="badge {{ $pembayaran->status ? 'bg-success' : 'bg-secondary' }}">
                        {{ $pembayaran->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('pembayaran.destroy', $pembayaran->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin hapus metode ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada metode pembayaran</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
