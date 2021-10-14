<?php

namespace App\Providers;

use App\Application\HouseholdAccount\query\AccountBalanceQuery;
use App\Application\HouseholdAccount\query\TransactionViewQuery;
use App\Application\query\Refuelings\FuelEconomyQueryService;
use App\Domain\HouseholdAccount\repository\TransactionRepository;
use App\Domain\Model\Refuelings\IRefuelingRepository;
use App\infra\EloquentRepository\Refuelings\RefuelingEloquentRepository;
use App\infra\HouseholdAccount\EloquentRepository\EloquentTransactionRepository;
use App\infra\HouseholdAccount\mysqlquery\MysqlAccountBalanceQuery;
use App\infra\HouseholdAccount\mysqlquery\MysqlTransactionViewQuery;
use App\infra\mysqlquery\Refuelings\FuelEconomyMysqlQueryService;
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
        $this->app->singleton(TransactionRepository::class,EloquentTransactionRepository::class);
        $this->app->singleton(AccountBalanceQuery::class,MysqlAccountBalanceQuery::class);
        $this->app->singleton(TransactionViewQuery::class,MysqlTransactionViewQuery::class);
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
