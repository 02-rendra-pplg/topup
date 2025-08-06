@extends('admin.admin')

@section('title', 'Edit Game')

@section('content')
<h3>Edit Game</h3>
<form action="{{ route('games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Nama Game</label>
        <input type="text" name="name" class="form-control" value="{{ $game->name }}" required>
    </div>
    <div class="mb-3">
        <label>Logo Game</label><br>
        <img src="{{ asset('storage/'.$game->logo) }}" width="50" class="mb-2">
        <input type="file" name="logo" class="form-control" accept="image/*">
    </div>
    <div class="mb-3">
        <label>Tipe</label>
        <input type="number" name="tipe" class="form-control" value="{{ $game->tipe }}" required>
    </div>
    <div class="mb-3">
        <label>URL API</label>
        <input type="url" name="url_api" class="form-control" value="{{ $game->url_api }}" required>
    </div>
    <div class="mb-3">
        <label>Logo Diamond</label><br>
        <img src="{{ asset('storage/'.$game->logo_diamond) }}" width="50" class="mb-2">
        <input type="file" name="logo_diamond" class="form-control" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('games.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
