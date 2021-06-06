<?php

namespace Tests\Domain\Object\MoneyStorage;

use App\Domain\Object\MoneyStorage\MoneyStorageEventType;
use PHPUnit\Framework\TestCase;

class MoneyStorageEventTypeTest extends TestCase
{
    /**
     * @test
     */
    public function test_MoneyStorageEventType_in(){
        $moneyStorageEventType = MoneyStorageEventType::in();
        $this->assertTrue($moneyStorageEventType->isIn());
    }

    /**
     * @test
     */
    public function test_MoneyStorageEventType_out(){
        $moneyStorageEventType = MoneyStorageEventType::out();
        $this->assertTrue($moneyStorageEventType->isOut());
    }
}
