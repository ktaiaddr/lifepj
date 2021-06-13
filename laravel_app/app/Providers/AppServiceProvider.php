<?php

namespace App\Providers;

use App\Domain\Object\FuelEconomy\IRefuelingRepository;
use App\infra\FuelEconomy\ElqRefuelingRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IRefuelingRepository::class,ElqRefuelingRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
