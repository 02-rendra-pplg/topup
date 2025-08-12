<style>
    .flash-sale-card {
        background: linear-gradient(135deg, #4ab7ff , #2291db);
        border-radius: 12px;
        padding: 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        color: #fff;
        box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    }
    .flash-sale-top {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .flash-sale-img {
        width: 55px;
        height: 55px;
        object-fit: contain;
        flex-shrink: 0;
    }
    .flash-sale-title {
        font-weight: 700;
        font-size: 14px;
        line-height: 1.2;
        margin-bottom: 2px;
        text-transform: uppercase;
    }
    .flash-sale-desc {
        font-size: 12px;
        line-height: 1.2;
        color: #f0f0f0;
        margin: 0;
    }
    .flash-sale-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }
    .flash-sale-timer {
        background-color: #ff6600;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: bold;
        color: #fff;
    }
    .flash-sale-discount {
        color: #00ff99;
        font-size: 13px;
        font-weight: bold;
    }
</style>

<div class="container text-center mb-5">
    <h5 class="text-warning mb-1">⚡ FLASH SALE</h5>
    <p class="text-light mb-4">Penawaran Eksklusif Terbatas!</p>
    <div class="row justify-content-center g-3">
        @forelse ($activeFlashSales as $sale)
            <div class="col-md-3 col-sm-6">
                <div class="flash-sale-card">
                    <div class="flash-sale-top">
                        <img src="{{ asset('storage/'.$sale->gambar) }}" alt="{{ $sale->nama_promo }}" class="flash-sale-img">
                        <div>
                            <div class="flash-sale-title">{{ strtoupper($sale->nama_promo) }}</div>
                            @if ($sale->tipe == 'bonus')
                                <p class="flash-sale-desc">({{ $sale->bonus_item ?? 0 }} Bonus)</p>
                            @elseif ($sale->tipe == 'diskon')
                                <p class="flash-sale-desc">
                                    Diskon {{ rtrim(rtrim(number_format($sale->diskon_persen, 2, '.', ''), '0'), '.') }}%
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flash-sale-bottom">
                        <div class="flash-sale-timer" data-end="{{ $sale->berakhir }}">
                            00:00:00
                        </div>
                        @if ($sale->tipe == 'diskon')
                            <div class="flash-sale-discount">
                                -{{ rtrim(rtrim(number_format($sale->diskon_persen, 2, '.', ''), '0'), '.') }}%
                            </div>
                        @elseif ($sale->tipe == 'bonus')
                            <div class="flash-sale-discount">
                                +{{ $sale->bonus_item }} Bonus
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Tidak ada promo saat ini</p>
        @endforelse
    </div>
</div>

<script>
    document.querySelectorAll('.flash-sale-timer').forEach(function(el) {
        const endTime = new Date(el.getAttribute('data-end')).getTime();
        function updateTimer() {
            const now = new Date().getTime();
            const distance = endTime - now;
            if (distance <= 0) {
                el.textContent = "00:00:00";
                return;
            }
            const hours = String(Math.floor((distance / (1000 * 60 * 60)) % 24)).padStart(2, '0');
            const minutes = String(Math.floor((distance / (1000 * 60)) % 60)).padStart(2, '0');
            const seconds = String(Math.floor((distance / 1000) % 60)).padStart(2, '0');
            el.textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateTimer, 1000);
        updateTimer();
    });
</script>
