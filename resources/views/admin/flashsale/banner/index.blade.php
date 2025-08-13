@extends('admin.admin')

@section('content')
<a href="{{ route('banner.create') }}" class="btn btn-primary">Tambah Banner</a>
<table class="table mt-3">
    <thead>
        <tr>
            <th>Gambar</th>
            <th>Judul</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($banners as $banner)
        <tr>
            <td><img src="{{ asset('storage/'.$banner->gambar) }}" width="150"></td>
            <td>{{ $banner->judul }}</td>
            <td>
                <a href="{{ route('banner.edit', $banner) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('banner.destroy', $banner) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
