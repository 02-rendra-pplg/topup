@extends('admin.admin')

@section('title', 'Daftar Game')

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h3 class="mb-2 mb-md-0">Daftar Game</h3>
        <a href="{{ route('games.create') }}" class="btn btn-primary">+ Tambah Game</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-id="{{ $game->id }}"
                                        data-nama="{{ $game->name }}">
                                    Hapus
                                </button>
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

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah kamu yakin ingin menghapus game <strong id="gameName"></strong>?
      </div>
      <div class="modal-footer">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Script Modal -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var gameId = button.getAttribute('data-id');
        var gameName = button.getAttribute('data-nama');

        // Ubah nama game di teks modal
        document.getElementById('gameName').textContent = gameName;

        // Ubah action form ke URL destroy yang sesuai
        document.getElementById('deleteForm').action = '/games/' + gameId;
    });
});
</script>
@endsection
