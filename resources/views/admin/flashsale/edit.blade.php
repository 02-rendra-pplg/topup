@extends('admin.admin')

@section('title', 'Edit Flash Sale')

@section('content')
<h3>Edit Flash Sale</h3>
<form action="{{ route('flashsale.update', $flashSale->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama Promo</label>
        <input type="text" name="nama_promo" class="form-control"
               value="{{ old('nama_promo', $flashSale->nama_promo) }}" required>
    </div>

    <div class="mb-3">
        <label>Gambar (opsional)</label>
        <input type="file" name="gambar" class="form-control">
        @if($flashSale->gambar)
            <div class="mt-2">
                <img src="{{ asset('storage/flashsale/' . $flashSale->gambar) }}"
                     alt="{{ $flashSale->nama_promo }}" width="100">
            </div>
        @endif
    </div>

    <div class="mb-3">
        <label>Tipe Promo</label>
        <select name="tipe" class="form-control" id="tipe" required>
            <option value="">-- Pilih Tipe --</option>
            <option value="diskon" {{ $flashSale->tipe == 'diskon' ? 'selected' : '' }}>Diskon Harga</option>
            <option value="bonus" {{ $flashSale->tipe == 'bonus' ? 'selected' : '' }}>Bonus Item</option>
        </select>
    </div>

    <div class="mb-3 tipe-diskon {{ $flashSale->tipe != 'diskon' ? 'd-none' : '' }}">
        <label>Diskon (%)</label>
        <input type="number" step="0.1" name="diskon_persen" class="form-control"
               value="{{ old('diskon_persen', $flashSale->diskon_persen) }}">
    </div>

    <div class="mb-3 tipe-bonus {{ $flashSale->tipe != 'bonus' ? 'd-none' : '' }}">
        <label>Bonus Item</label>
        <input type="number" name="bonus_item" class="form-control"
               value="{{ old('bonus_item', $flashSale->bonus_item) }}">
        <small class="text-muted">Masukkan jumlah bonus DM atau item.</small>
    </div>

    <div class="mb-3 tipe-bonus {{ $flashSale->tipe != 'bonus' ? 'd-none' : '' }}">
        <label>Keterangan Bonus</label>
        <input type="text" name="keterangan_bonus" class="form-control"
               value="{{ old('keterangan_bonus', $flashSale->keterangan_bonus) }}">
    </div>

    <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="datetime-local" name="mulai" class="form-control"
               value="{{ old('mulai', date('Y-m-d\TH:i', strtotime($flashSale->mulai))) }}" required>
    </div>

    <div class="mb-3">
        <label>Tanggal Berakhir</label>
        <input type="datetime-local" name="berakhir" class="form-control"
               value="{{ old('berakhir', date('Y-m-d\TH:i', strtotime($flashSale->berakhir))) }}" required>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="1" {{ $flashSale->status ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ !$flashSale->status ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
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
