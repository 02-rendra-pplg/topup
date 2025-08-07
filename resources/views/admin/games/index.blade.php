@extends('admin.admin')

@section('title', 'Daftar Game')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Game</h3>
    <a href="{{ route('games.create') }}" class="btn btn-primary">Tambah Game</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th style="width: 150px;">Gambar</th>
            <th>Tipe</th>
            <th>URL API</th>
            <th>Logo Diamond</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($games as $game)
        <tr>
            <td>{{ $game->name }}</td>
            <td>
                <img src="{{ asset('storage/'.$game->logo) }}"
                     alt="{{ $game->name }}"
                     style="width: 120px; height: auto;">
            </td>
            <td>{{ $game->tipe }}</td>
            <td>{{ $game->url_api }}</td>
            <td>
                <img src="{{ asset('storage/'.$game->logo_diamond) }}"
                     alt="Diamond {{ $game->name }}"
                     width="50">
            </td>
            <td>
                <a href="{{ route('games.edit', $game->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('games.destroy', $game->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Belum ada game</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
