<?php

namespace App\Providers;

use App\Domain\Model\FuelEconomy\IRefuelingRepository;
use App\infra\EloquentRepository\RefuelingEloquentRepository;
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
        $this->app->singleton(IRefuelingRepository::class,RefuelingEloquentRepository::class);
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
