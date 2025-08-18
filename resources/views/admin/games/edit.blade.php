@extends('admin.admin')

@section('title', 'Edit Game')

@section('content')
<h3>Edit Game</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nama Game</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $game->name) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Logo Game</label><br>
        <img src="{{ asset('storage/'.$game->logo) }}" alt="{{ $game->name }}" width="120" class="mb-2">
        <input type="file" name="logo" class="form-control" accept="image/*">
        <small class="text-muted">Kosongkan jika tidak ingin mengganti logo</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Tipe</label>
        <input type="number" name="tipe" class="form-control" value="{{ old('tipe', $game->tipe) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">URL API</label>
        <input type="url" name="url_api" class="form-control" value="{{ old('url_api', $game->url_api) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Logo Diamond</label><br>
        <img src="{{ asset('storage/'.$game->logo_diamond) }}" alt="Diamond {{ $game->name }}" width="80" class="mb-2">
        <input type="file" name="logo_diamond" class="form-control" accept="image/*">
        <small class="text-muted">Kosongkan jika tidak ingin mengganti logo diamond</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Logo Weekly</label><br>
        @if($game->logo_weekly)
            <img src="{{ asset('storage/'.$game->logo_weekly) }}" alt="Weekly {{ $game->name }}" width="80" class="mb-2">
        @endif
        <input type="file" name="logo_weekly" class="form-control" accept="image/*">
        <small class="text-muted">Kosongkan jika tidak ingin mengganti logo weekly</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Logo Member</label><br>
        @if($game->logo_member)
            <img src="{{ asset('storage/'.$game->logo_member) }}" alt="Member {{ $game->name }}" width="80" class="mb-2">
        @endif
        <input type="file" name="logo_member" class="form-control" accept="image/*">
        <small class="text-muted">Kosongkan jika tidak ingin mengganti logo member</small>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('games.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
