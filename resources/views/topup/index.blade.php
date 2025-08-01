@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h3 class="mb-4">🎮 Pilih Game untuk Top Up</h3>

    {{-- Komponen daftar game --}}
    @include('components.game-list')

</div>
@endsection
