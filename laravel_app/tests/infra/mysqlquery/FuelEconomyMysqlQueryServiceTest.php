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

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function test_findByUserid(){

        /** @var FuelEconomyMysqlQueryService $fuelEconomyMysqlQueryService */
        $fuelEconomyMysqlQueryService = app()->make(FuelEconomyMysqlQueryService::class);

        /** @var FuelEconomyQueryModel[] $fuelEconomyQueryModel_list */
        $fuelEconomyQueryModel_list = $fuelEconomyMysqlQueryService->findByUserid(1);

        $this->assertTrue( $fuelEconomyQueryModel_list[0] instanceof FuelEconomyQueryModel );
        $this->assertSame( 'memo1',$fuelEconomyQueryModel_list[0]->memo);
        $this->assertSame( 'memo2_modify',$fuelEconomyQueryModel_list[1]->memo);
        $this->assertSame( 'memo３',$fuelEconomyQueryModel_list[2]->memo);

        $fuelEconomyQueryModel_list = $fuelEconomyMysqlQueryService->findByUserid(3);
        $this->assertSame( [],$fuelEconomyQueryModel_list );
    }

    protected function tearDown():void
    {
//        $pdo = DB::getPdo();
//        $stmt = $pdo->prepare('truncate refuelings');
//        $stmt->execute();
        parent::tearDown();

    }


}
