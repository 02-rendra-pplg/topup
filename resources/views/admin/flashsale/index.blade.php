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
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal"
                            data-id="{{ $fs->id }}"
                            data-nama="{{ $fs->nama_promo }}">
                        Hapus
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada Flash Sale</td>
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
        Apakah kamu yakin ingin menghapus promo <strong id="promoName"></strong>?
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
        var promoId = button.getAttribute('data-id');
        var promoName = button.getAttribute('data-nama');

        document.getElementById('promoName').textContent = promoName;
        document.getElementById('deleteForm').action = '/flashsale/' + promoId;
    });
});
</script>
@endsection
