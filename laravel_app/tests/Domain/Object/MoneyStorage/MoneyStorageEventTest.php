<?php

namespace Tests\Domain\Object\MoneyStorage;

use App\Domain\Object\MoneyStorage\MoneyStorageEvent;
use PHPUnit\Framework\TestCase;

class MoneyStorageEventTest extends TestCase
{

    /**
     *
     */
    public function MoneyStorageEvent生成テスト(){


        new MoneyStorageEvent();
        $this->assertSame();
    }
}
