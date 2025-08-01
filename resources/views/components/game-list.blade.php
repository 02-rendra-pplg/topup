<div class="container mb-5">
    <h5 class="mb-3">🎮 Game Top Up</h5>
    <div class="row g-4">
        @php
            $games = [
                ['nama' => 'Mobile Legends', 'img' => 'mlbb.png', 'type' => '2id'],
                ['nama' => 'PUBG Mobile', 'img' => 'pubg.jpg', 'type' => '1id'],
                ['nama' => 'Free Fire', 'img' => 'free.jpg', 'type' => '1id'],
                ['nama' => 'Genshin Impact', 'img' => 'ghensin.jpeg', 'type' => '1id'],
                ['nama' => 'Delta Force Garena', 'img' => 'df garena.jpg', 'type' => '1id'],
                ['nama' => 'Delta Force Steam', 'img' => 'df steam.png', 'type' => '1id'],
                ['nama' => 'Magic Chess GO.GO', 'img' => 'mc.jpg', 'type' => '2id'],
                ['nama' => 'Free Fire Max', 'img' => 'Free Max.jpg', 'type' => '1id'],
                ['nama' => 'Honor OF King', 'img' => 'hok.jpg', 'type' => '1id'],
            ];
            function slugify($text) {
                return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
            }
        @endphp

        @foreach ($games as $game)
            <div class="col-6 col-md-2 text-center">
                <a href="{{ route('topup.show', ['slug' => slugify($game['nama'])]) }}"
                   class="text-decoration-none"
                   data-type="{{ $game['type'] }}"
                   data-nama="{{ $game['nama'] }}">
                    <img src="{{ asset('images/games/' . $game['img']) }}" class="img-fluid" alt="{{ $game['nama'] }}">
                    <div class="text-white mt-2" style="font-size: 14px;">
                        {{ $game['nama'] }}
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
