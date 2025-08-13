@extends('admin.admin')

@section('content')
<form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="judul" value="{{ old('judul', $banner->judul) }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="gambar" class="form-control">
        @if($banner->gambar)
            <div class="mt-2">
                <img src="{{ asset('storage/'.$banner->gambar) }}" alt="Banner" width="300">
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('banner.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
