<?php

namespace Tests\Domain\Model\MoneyStorage;

use App\Domain\Model\FuelEconomy\FuelEconomy;
use App\Domain\Model\FuelEconomy\Refueling;
//use App\Domain\Model\FuelEconomy\RefuelingAmount;
//use App\Domain\Model\FuelEconomy\RefuelingDistance;
use PHPUnit\Framework\TestCase;

class RefuelingTest extends TestCase
{

    public function test_FuelEconomy():FuelEconomy{
        try{
            new FuelEconomy(0,1);
        }catch(\Exception $e){
            $this->assertSame('給油量が無効です（0以下です）',$e->getMessage());
        }
        try{
            new FuelEconomy(1,0);
        }catch(\Exception $e){
            $this->assertSame('走行距離が無効です（0以下です）',$e->getMessage());
        }
        return new FuelEconomy(26.15, 393.9);

    }

    /**
     * @depends test_FuelEconomy
     */
    public function test_Refueling(FuelEconomy $fuelEconomy){

        try{
            new Refueling(0, 1,new \DateTime()
            ,$fuelEconomy,'阪奈','帰省');
        }catch (\Exception $e){
            $this->assertSame('idは1以上の数値です',$e->getMessage());
        }
        $refueling = new Refueling(1,1, new \DateTime()
            ,$fuelEconomy,'阪奈','帰省');
        $this->assertSame(15.06,$refueling->calcFuelEconomy());

        $refueling = new Refueling(1,1, new \DateTime()
            ,new FuelEconomy(23.06, 539.6),'阪奈','帰省');
        $this->assertSame(23.4,$refueling->calcFuelEconomy());

    }

}
