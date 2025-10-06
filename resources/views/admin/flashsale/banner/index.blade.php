@extends('admin.admin')

@section('title', 'Daftar Banner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Banner</h3>
    <a href="{{ route('banner.create') }}" class="btn btn-primary">Tambah Banner</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>Gambar</th>
            <th>Judul</th>
            <th style="width: 150px;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($banners as $banner)
        <tr>
            <td>
                <img src="{{ asset('storage/'.$banner->gambar) }}"
                     alt="{{ $banner->judul }}"
                     width="150"
                     class="rounded shadow-sm">
            </td>
            <td>{{ $banner->judul }}</td>
            <td>
                <a href="{{ route('banner.edit', $banner) }}" class="btn btn-warning btn-sm">Edit</a>
                <button type="button"
                        class="btn btn-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal"
                        data-id="{{ $banner->id }}"
                        data-judul="{{ $banner->judul }}">
                    Hapus
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center text-muted">Belum ada banner</td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah kamu yakin ingin menghapus banner <strong id="bannerTitle"></strong>?
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
        var bannerId = button.getAttribute('data-id');
        var bannerTitle = button.getAttribute('data-judul');

        document.getElementById('bannerTitle').textContent = bannerTitle;
        document.getElementById('deleteForm').action = '/banner/' + bannerId;
    });
});
</script>
@endsection
