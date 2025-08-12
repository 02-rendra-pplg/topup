@extends('admin.admin')

@section('title', 'Edit Flash Sale')

@section('content')
<h3>Edit Flash Sale</h3>
<form action="{{ route('flashsale.update', $flashSale->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama Promo</label>
        <input type="text" name="nama_promo" value="{{ $flashSale->nama_promo }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tipe Promo</label>
        <select name="tipe" class="form-control" id="tipe" required>
            <option value="diskon" {{ $flashSale->tipe == 'diskon' ? 'selected' : '' }}>Diskon Harga</option>
            <option value="bonus" {{ $flashSale->tipe == 'bonus' ? 'selected' : '' }}>Bonus Item</option>
        </select>
    </div>

    <div class="mb-3 tipe-diskon {{ $flashSale->tipe == 'diskon' ? '' : 'd-none' }}">
        <label>Diskon (%)</label>
        <input type="number" step="0.1" name="diskon_persen" value="{{ $flashSale->diskon_persen }}" class="form-control">
    </div>

    <div class="mb-3 tipe-bonus {{ $flashSale->tipe == 'bonus' ? '' : 'd-none' }}">
        <label>Bonus Item</label>
        <input type="number" name="bonus_item" value="{{ $flashSale->bonus_item }}" class="form-control">
    </div>

    <div class="mb-3 tipe-bonus {{ $flashSale->tipe == 'bonus' ? '' : 'd-none' }}">
        <label>Keterangan Bonus</label>
        <input type="text" name="keterangan_bonus" value="{{ $flashSale->keterangan_bonus }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="datetime-local" name="mulai" value="{{ \Carbon\Carbon::parse($flashSale->mulai)->format('Y-m-d\TH:i') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tanggal Berakhir</label>
        <input type="datetime-local" name="berakhir" value="{{ \Carbon\Carbon::parse($flashSale->berakhir)->format('Y-m-d\TH:i') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="1" {{ $flashSale->status == 1 ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $flashSale->status == 0 ? 'selected' : '' }}>Nonaktif</option>
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
