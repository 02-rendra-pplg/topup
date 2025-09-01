@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h4 class="mb-4">🎮 Pilih Game untuk Top Up</h4>

    @include('components.game-list', ['games' => $games])

</div>
@endsection
