@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h3 class="mb-4 text-white">🎮 Pilih Game untuk Top Up</h3>

    <div class="row">
        @forelse($games as $game)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('storage/'.$game->image) }}" class="card-img-top" alt="{{ $game->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $game->name }}</h5>
                        <a href="{{ route('topup.show', $game->slug) }}" class="btn btn-primary btn-sm">
                            Top Up
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-4">
                Belum ada game yang tersedia.
            </div>
        @endforelse
    </div>
</div>

<style>
.game-card:hover {
    transform: scale(1.05);
}
</style>
@endsection
