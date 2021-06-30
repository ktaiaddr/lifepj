<?php

namespace Tests\infra\mysqlquery;

use App\Application\query\FuelEconomy\FuelEconomyQueryConditions;
use App\Application\query\FuelEconomy\FuelEconomyQueryModel;
use App\Domain\Model\FuelEconomy\FuelEconomy;
use App\Domain\Model\FuelEconomy\Refueling;
use App\infra\EloquentRepository\RefuelingEloquentRepository;
use App\infra\mysqlquery\FuelEconomyMysqlQueryService;
//use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FuelEconomyMysqlQueryServiceTest extends TestCase
{

    private FuelEconomyMysqlQueryService $fuelEconomyMysqlQueryService;

    protected function setUp():void
    {
        parent::setUp();
        $this->fuelEconomyMysqlQueryService = app()->make(FuelEconomyMysqlQueryService::class);

        $pdo = DB::getPdo();
        $stmt = $pdo->prepare('truncate refuelings');
        $stmt->execute();
        $elqRefuelingRepository = new RefuelingEloquentRepository();

        for( $i = 1 ; $i<=12 ; $i++ ){
            $refueling = new Refueling(
                null,
                1,
                new \DateTime('2021-'.sprintf('%02d',$i).'-'.sprintf('%02d',$i)),
                new FuelEconomy((30 + $i),(500+$i)),
                'gasStation'.$i,
                'memo'.$i);
            $elqRefuelingRepository->save($refueling);
        }
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function test_findByUserid(){

        /** @var FuelEconomyQueryModel[] $fuelEconomyQueryModel_list */
        $fuelEconomyQueryModel_list = $this->fuelEconomyMysqlQueryService->findByUserid(1);

        $this->assertTrue( $fuelEconomyQueryModel_list[0] instanceof FuelEconomyQueryModel );
        $this->assertSame( 'memo1',$fuelEconomyQueryModel_list[0]->memo);
        $this->assertSame( 'memo2',$fuelEconomyQueryModel_list[1]->memo);

        $fuelEconomyQueryModel_list = $this->fuelEconomyMysqlQueryService->findByUserid(3);
        $this->assertSame( [],$fuelEconomyQueryModel_list );
    }

    function test_findByUseridAndCondition(){

        $cond = new FuelEconomyQueryConditions(null,null,
            null,null,null,null,null,null,1);

        $fuelEconomyQueryModel_list = $this->fuelEconomyMysqlQueryService->findByUseridAndCondition(1,$cond);

        $this->assertSame(5,count($fuelEconomyQueryModel_list));

        for($i=1;$i<=5;$i++)
            $this->assertSame(round((500+$i)/(30+$i),2), $fuelEconomyQueryModel_list[($i-1)]->calcFuelEconomy());

        $cond = new FuelEconomyQueryConditions(new \DateTime('2021-01-01'),new \DateTime('2022-01-01'),
            31,33,501,503,'g','m',null);

        $fuelEconomyQueryModel_list = $this->fuelEconomyMysqlQueryService->findByUseridAndCondition(1,$cond);

        $this->assertIsArray($fuelEconomyQueryModel_list);
        $this->assertSame(3,count($fuelEconomyQueryModel_list));
    }

    protected function tearDown():void
    {
//        $pdo = DB::getPdo();
//        $stmt = $pdo->prepare('truncate refuelings');
//        $stmt->execute();
//        parent::tearDown();

    }


}
