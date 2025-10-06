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

                    <!-- Tombol untuk buka modal hapus -->
                    <button type="button" class="btn btn-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#hapusModal"
                        data-id="{{ $pembayaran->id }}"
                        data-nama="{{ $pembayaran->nama }}">
                        Hapus
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada metode pembayaran</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p>Apakah kamu yakin ingin menghapus metode pembayaran <strong id="namaMetode"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk ubah action form sesuai data -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hapusModal = document.getElementById('hapusModal');
    const namaMetode = document.getElementById('namaMetode');
    const formHapus = document.getElementById('formHapus');

    hapusModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');

        namaMetode.textContent = nama;
        formHapus.action = `/pembayaran/${id}`;
    });
});
</script>
@endsection
