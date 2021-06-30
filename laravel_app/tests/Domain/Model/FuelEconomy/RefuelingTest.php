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

        //例外テスト:給油量が0
        try{
            new FuelEconomy(0,1);
        }catch(\Exception $e){
            $this->assertSame('給油量が無効です（0以下です）',$e->getMessage());
        }
        //例外テスト:走行距離が0
        try{
            new FuelEconomy(1,0);
        }catch(\Exception $e){
            $this->assertSame('走行距離が無効です（0以下です）',$e->getMessage());
        }
        //正常インスタンス生成
        $exception_throw = false;
        $fuelEconomy = null;
        try{
            $fuelEconomy = new FuelEconomy(26.15, 393.9);
        }catch(\Exception $e){
            $exception_throw = true;
        }
        //例外が投げられないことを確認
        $this->assertFalse( $exception_throw );

        // test_Refueling に@depends
        return $fuelEconomy;

    }

    /**
     * @depends test_FuelEconomy
     */
    public function test_Refueling(FuelEconomy $fuelEconomy){

        //例外テスト:idが0
        try{
            new Refueling(0, 1,new \DateTime()
            ,$fuelEconomy,'阪奈','帰省');
        }catch (\Exception $e){
            $this->assertSame('idは1以上の数値です',$e->getMessage());
        }
        $refueling = new Refueling(1,1, new \DateTime()
            ,$fuelEconomy,'阪奈','帰省');

        $this->assertSame(round(393.9/26.15,2),$refueling->calcFuelEconomy());

        $refueling = new Refueling(1,1, new \DateTime()
            ,new FuelEconomy(23.06, 539.6),'阪奈','帰省');
        $this->assertSame(23.4,$refueling->calcFuelEconomy());

    }

}
