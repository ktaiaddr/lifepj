<?php

namespace App\Providers;

use App\Application\query\FuelEconomy\FuelEconomyQueryService;
use App\Domain\Model\FuelEconomy\IRefuelingRepository;
use App\infra\EloquentRepository\RefuelingEloquentRepository;
use App\infra\mysqlquery\FuelEconomyMysqlQueryService;
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
        $this->app->singleton(FuelEconomyQueryService::class,FuelEconomyMysqlQueryService::class);
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
