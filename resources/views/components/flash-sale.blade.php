<div class="container text-center mb-5">
    <h5 class="text-warning">⚡ FLASH SALE</h5>
    <p class="text-light">Penawaran Eksklusif Terbatas!</p>
    <div class="row justify-content-center g-3">

        @forelse ($activeFlashSales as $sale)
            <div class="col-md-3">
                <div class="promo-box p-3 border rounded bg-dark text-white">
                    <p class="mb-1">{{ strtoupper($sale->nama_promo) }}</p>

                    @if ($sale->tipe == 'bonus')
                        <p style="font-size: 12px;">
                            {{ $sale->bonus_item ? $sale->bonus_item : 0 }} Bonus
                        </p>
                    @elseif ($sale->tipe == 'diskon')
                        <p style="font-size: 12px;">
                            Diskon {{ $sale->diskon_persen }}%
                        </p>
                    @endif

                    @if ($sale->tipe == 'diskon' && $sale->diskon_persen)
                        <span class="badge bg-dark">-{{ rtrim(rtrim(number_format($sale->diskon_persen, 2, '.', ''), '0'), '.') }}%</span>
                    @elseif ($sale->tipe == 'bonus' && $sale->bonus_item)
                        <span class="badge bg-dark">+{{ $sale->bonus_item }} Bonus</span>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted">Tidak ada promo saat ini</p>
        @endforelse

    </div>
</div>
