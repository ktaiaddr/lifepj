<?php

namespace Tests\Domain\Object\MoneyStorage;

use App\Domain\Object\Car\Car;
use App\Domain\Object\MoneyStorage\MoneyStorageEvent;
use App\Domain\Object\MoneyStorage\MoneyStorageEventAmount;
use App\Domain\Object\MoneyStorage\MoneyStorageEventMemo;
use App\Domain\Object\MoneyStorage\MoneyStorageEventType;
use App\Domain\Object\MoneyStorage\MoneyStorageId;
use phpDocumentor\Reflection\Types\Integer;
use PHPUnit\Framework\TestCase;

class MoneyStorageEventTest extends TestCase
{

    public function test_MoneyStorageId():MoneyStorageId{
        try {
            new MoneyStorageId(null);
        }catch (\Exception $e){
            $this->assertSame('ストレージIDは数値です',$e->getMessage());
        }
        try {
            new MoneyStorageId(0);
        }catch (\Exception $e){
            $this->assertSame('ストレージIDは１以上です',$e->getMessage());
        }
        return new MoneyStorageId(1);
    }

    /**
     * @throws \Exception
     */
    public function test_MoneyStorageEventAmount():MoneyStorageEventAmount{
        try{
            new MoneyStorageEventAmount(0);
        }catch(\Exception $e){
            $this->assertSame('金額は0以上数値です',$e->getMessage());
        }
        return new MoneyStorageEventAmount(10);
    }

    public function test_MoneyStorageEventMemo(): MoneyStorageEventMemo{
        $moneyStorageEventMemo = new MoneyStorageEventMemo('給料');
        $this->assertTrue( $moneyStorageEventMemo instanceof MoneyStorageEventMemo );
        $this->assertSame( '給料',$moneyStorageEventMemo->getValue() );
        return $moneyStorageEventMemo;
    }

    public function test_MoneyStorageEventType():MoneyStorageEventType{
        $type = MoneyStorageEventType::in();
        $this->assertTrue($type->isIn());
        $type = MoneyStorageEventType::out();
        $this->assertTrue($type->isOut());

        return $type;
    }
    /**
     * @depends test_MoneyStorageEventAmount
     * @depends test_MoneyStorageEventMemo
     * @depends test_MoneyStorageId
     * @depends test_MoneyStorageEventType
     * @test
     */
    public function MoneyStorageEvent生成テスト(MoneyStorageEventAmount $moneyStorageEventAmount,
                                           MoneyStorageEventMemo $moneyStorageEventMemo,
                                           MoneyStorageId $moneyStorageId,
                                           MoneyStorageEventType $moneyStorageEventType){
        $moneyStorageEvent = new MoneyStorageEvent(null,$moneyStorageId,
            $moneyStorageEventType,$moneyStorageEventAmount,
            $moneyStorageEventMemo);
        $this->assertTrue( $moneyStorageEvent instanceof MoneyStorageEvent );
        $this->assertTrue( $moneyStorageEvent->isNewObject() );

    }
}
