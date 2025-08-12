<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\FlashSale;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
       View::composer('*', function ($view) {
            $activeFlashSales = FlashSale::where('status', 1)
                ->where('mulai', '<=', now())
                ->where('berakhir', '>=', now())
                ->get();

            $view->with('activeFlashSales', $activeFlashSales);
        });
    }
}
