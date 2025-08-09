@extends('admin.admin')

@section('title', 'Tambah Game')

@section('content')
<h3>Tambah Game</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('games.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Nama Game</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Logo Game</label>
        <input type="file" name="logo" class="form-control" accept="image/*" required>
    </div>
    <div class="mb-3">
        <label>Tipe</label>
        <input type="number" name="tipe" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>URL API</label>
        <input type="url" name="url_api" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Logo Diamond</label>
        <input type="file" name="logo_diamond" class="form-control" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('games.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
