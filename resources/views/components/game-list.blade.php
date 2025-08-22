<div class="row gx-4 gy-4">
        <h4 class="mb-4 text-center">🎮 Pilih Game untuk Top Up</h4>
    @forelse ($games as $game)
        <div class="col-6 col-md-2 text-center">
            <a href="{{ route('topup.show', ['slug' => $game->slug]) }}"
               class="game-card text-decoration-none d-block p-3 rounded bg-dark h-100"
               style="transition: transform 0.2s ease-in-out;">
                <img src="{{ asset('storage/' . $game->logo) }}"
                     class="img-fluid rounded mb-2"
                     alt="{{ $game->name }}"
                     style="max-height: 120px; object-fit: contain;">
                <div class="text-white fw-semibold text-truncate" style="font-size: 14px;">
                    {{ $game->name }}
                </div>
            </a>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-4">
            Belum ada game yang tersedia.
        </div>
    @endforelse
</div>

<style>
.game-card:hover {
    transform: scale(1.05);
}
</style>

