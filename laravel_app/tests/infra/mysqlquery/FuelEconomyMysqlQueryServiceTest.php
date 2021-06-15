<?php

namespace Tests\infra\mysqlquery;

use App\Application\query\FuelEconomy\FuelEconomyQueryModel;
use App\infra\mysqlquery\FuelEconomyMysqlQueryService;
//use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FuelEconomyMysqlQueryServiceTest extends TestCase
{
    protected function setUp():void
    {
        parent::setUp();
    }
    function test_findByUserid(){

        $fuelEconomyMysqlQueryService = new FuelEconomyMysqlQueryService();
        $fuelEconomyQueryModel_list = $fuelEconomyMysqlQueryService->findByUserid(1);
        $this->assertTrue( $fuelEconomyQueryModel_list[0] instanceof FuelEconomyQueryModel );

        $fuelEconomyQueryModel_list = $fuelEconomyMysqlQueryService->findByUserid(2);
    }

    protected function tearDown():void
    {
//        $pdo = DB::getPdo();
//        $stmt = $pdo->prepare('truncate refuelings');
//        $stmt->execute();
        parent::tearDown();

    }


}
