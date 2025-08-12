@extends('admin.admin')

@section('title', 'Flash Sale')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Flash Sale</h3>
    <a href="{{ route('flashsale.create') }}" class="btn btn-primary">Tambah Promo</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>Gambar</th>
            <th>Nama Promo</th>
            <th>Tipe</th>
            <th>Diskon</th>
            <th>Bonus</th>
            <th>Periode</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($flashSales as $fs)
            <tr>
                <td class="text-center">
                    @if($fs->gambar)
                        <img src="{{ asset('storage/flashsale/' . $fs->gambar) }}"
                             alt="{{ $fs->nama_promo }}"
                             style="width:60px; height:auto; border-radius:5px;">
                    @else
                        <span class="text-muted">Tidak ada gambar</span>
                    @endif
                </td>
                <td>{{ $fs->nama_promo }}</td>
                <td>{{ ucfirst($fs->tipe) }}</td>
                <td>{{ $fs->diskon_persen ? $fs->diskon_persen . '%' : '-' }}</td>
                <td>{{ $fs->bonus_item ? $fs->bonus_item . ' (' . $fs->keterangan_bonus . ')' : '-' }}</td>
                <td>{{ $fs->mulai }} s/d {{ $fs->berakhir }}</td>
                <td>
                    <span class="badge {{ $fs->status ? 'bg-success' : 'bg-secondary' }}">
                        {{ $fs->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('flashsale.edit', $fs->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('flashsale.destroy', $fs->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus promo ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center">Belum ada Flash Sale</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
