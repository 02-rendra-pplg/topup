@extends('admin.admin')

@section('title', 'Daftar Game')

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h3 class="mb-2 mb-md-0">Daftar Game</h3>
        <a href="{{ route('games.create') }}" class="btn btn-primary">+ Tambah Game</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th style="width: 150px;">Gambar</th>
                            <th>Tipe</th>
                            <th>URL API</th>
                            <th>Logo Diamond</th>
                            <th style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($games as $game)
                        <tr>
                            <td>{{ $game->name }}</td>
                            <td>
                                <img src="{{ asset('storage/'.$game->logo) }}"
                                     alt="{{ $game->name }}"
                                     class="img-fluid rounded"
                                     style="max-width: 120px;">
                            </td>
                            <td>{{ $game->tipe }}</td>
                            <td class="text-truncate" style="max-width: 200px;">
                                {{ $game->url_api }}
                            </td>
                            <td>
                                <img src="{{ asset('storage/'.$game->logo_diamond) }}"
                                     alt="Diamond {{ $game->name }}"
                                     class="img-fluid"
                                     style="max-width: 50px;">
                            </td>
                            <td>
                                <a href="{{ route('games.edit', $game->id) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('games.destroy', $game->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin ingin menghapus?')"
                                            class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada game</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
