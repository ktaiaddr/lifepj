<?php

namespace Tests\Domain\Model\Car;

use App\Domain\Model\Car\CarColor;
use App\Domain\Model\Car\Car;
use App\Domain\Model\Car\CarName;
use PHPUnit\Framework\TestCase;

class CarTest extends TestCase
{

    /**
     * @test
     */
    public function CarName生成のテスト(): CarName{

        $exception_throw = false;
        // 車名の例外テスト
        try{
            new CarName('');
        }
        catch (\Exception $e) {
            $exception_throw = true;
        }
        $this->assertTrue( $exception_throw );
        $this->assertSame( '車名は１文字以上必要です', $e->getMessage()  );
        $this->assertSame( 4501, $e->getCode()  );

        $carName = null;
        $exception_throw = false;
        try{
            $carName = new CarName('カローラ');
        }catch(\Exception $e){
            $exception_throw = true;
        }
        $this->assertFalse( $exception_throw );
        $this->assertSame($carName->getValue(),'カローラ');

        $carName = new CarName('シビック');
        $this->assertSame($carName->getValue(),'シビック');

        return $carName;
    }

    /**
     * @test
     */
    public function BodyColorのテスト(): CarColor{

        $exception_throw = false;
        // 車色の例外テスト
        try{
            new CarColor(-1);
        }
        catch (\Exception $e) {
            $exception_throw = true;
        }
        $this->assertTrue( $exception_throw );
        $this->assertSame( 'BodyColorが想定外です', $e->getMessage()  );
        $this->assertSame( 4401, $e->getCode()  );

        $bodyColor = null;
        $exception_throw = false;
        try{
            $bodyColor = new CarColor(CarColor::WHITE);
        }catch(\Exception $e){
            $exception_throw = true;
        }
        $this->assertFalse( $exception_throw );
        $this->assertSame($bodyColor->getBodyColor(),'白');

        $bodyColor = new CarColor(CarColor::BLACK);
        $this->assertSame($bodyColor->getBodyColor(),'黒');

        return $bodyColor;
    }

    /**
     * @depends CarName生成のテスト
     * @depends BodyColorのテスト
     * @test
     * @throws \Exception
     */
    public function Carのテスト(CarName $carName, CarColor $bodyColor): void{
        $exception_throw = false;
        // 車色の例外テスト
        try{
            new Car(-1,$carName,0, $bodyColor );
        }
        catch (\Exception $e) {
            $exception_throw = true;
        }
        $this->assertTrue( $exception_throw );
        $this->assertSame( 'caridは0以上の数値が必要です', $e->getMessage()  );
        $this->assertSame( 4602, $e->getCode()  );

        $exception_throw = false;
        // 車色の例外テスト
        try{
            new Car(1,$carName,0, $bodyColor );
        }
        catch (\Exception $e) {
            $exception_throw = true;
        }
        $this->assertTrue( $exception_throw );
        $this->assertSame( '定員は１名以上必要です', $e->getMessage()  );
        $this->assertSame( 4601, $e->getCode()  );

        $car = new Car(2,$carName,5, $bodyColor );
        $this->assertSame( 'シビック', $car->getName()  );
        $this->assertSame( '黒', $car->getColor()  );

    }
}
