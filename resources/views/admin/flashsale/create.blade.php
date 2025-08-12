@extends('admin.admin')

@section('title', 'Tambah Flash Sale')

@section('content')
<h3>Tambah Flash Sale</h3>
<form action="{{ route('flashsale.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama Promo</label>
        <input type="text" name="nama_promo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tipe Promo</label>
        <select name="tipe" class="form-control" id="tipe" required>
            <option value="">-- Pilih Tipe --</option>
            <option value="diskon">Diskon Harga</option>
            <option value="bonus">Bonus Item</option>
        </select>
    </div>

    <div class="mb-3 tipe-diskon d-none">
        <label>Diskon (%)</label>
        <input type="number" step="0.1" name="diskon_persen" class="form-control">
    </div>

    <div class="mb-3 tipe-bonus d-none">
        <label>Bonus Item</label>
        <input type="number" name="bonus_item" class="form-control" placeholder="contoh: 65">
        <small class="text-muted">Masukkan jumlah bonus DM atau item.</small>
    </div>

    <div class="mb-3 tipe-bonus d-none">
        <label>Keterangan Bonus</label>
        <input type="text" name="keterangan_bonus" class="form-control" placeholder="contoh: Bonus Diamond">
    </div>

    <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="datetime-local" name="mulai" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tanggal Berakhir</label>
        <input type="datetime-local" name="berakhir" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('flashsale.index') }}" class="btn btn-secondary">Batal</a>
</form>

<script>
    document.getElementById('tipe').addEventListener('change', function() {
        document.querySelectorAll('.tipe-diskon').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.tipe-bonus').forEach(el => el.classList.add('d-none'));

        if (this.value === 'diskon') {
            document.querySelectorAll('.tipe-diskon').forEach(el => el.classList.remove('d-none'));
        } else if (this.value === 'bonus') {
            document.querySelectorAll('.tipe-bonus').forEach(el => el.classList.remove('d-none'));
        }
    });
</script>
@endsection
