@extends('admin.admin')

@section('content')
<form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control">
    </div>
    <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="gambar" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('banner.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
