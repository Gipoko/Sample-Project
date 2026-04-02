<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
  use Illuminate\Support\Facades\URL;
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
URL::forceRootUrl('https://expert-space-barnacle-wg46xp45wv5hgrj4-8000.app.github.dev');
    URL::forceScheme('https');
}
}
