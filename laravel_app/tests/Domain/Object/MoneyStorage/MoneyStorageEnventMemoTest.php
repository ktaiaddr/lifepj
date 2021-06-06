<?php

namespace Tests\Domain\Object\MoneyStorage;

use App\Domain\Object\MoneyStorage\MoneyStorageEnventMemo;
use PHPUnit\Framework\TestCase;

class MoneyStorageEnventMemoTest extends TestCase
{

    public function test_getValue(){

        $v = new MoneyStorageEnventMemo('test');
        $this->assertSame('test',$v->getValue());

        $v = new MoneyStorageEnventMemo('test2');
        $this->assertSame('test2',$v->getValue());
    }
}
