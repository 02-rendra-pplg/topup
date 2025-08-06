@extends('admin.admin')

@section('title', 'Dashboard Admin')

@section('content')
<h1>Selamat Datang, {{ auth('admin')->user()->name }}</h1>
<p>Gunakan menu di bawah untuk mengelola data:</p>

<a href="{{ route('games.index') }}" class="btn btn-success">Kelola Game</a>
@endsection
