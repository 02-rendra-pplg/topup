<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Game;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with('banners', Banner::latest()->get());
            $view->with('games', Game::latest()->get());
        });
    }
}
