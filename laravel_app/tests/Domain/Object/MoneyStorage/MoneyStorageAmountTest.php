<?php

namespace Tests\Domain\Object\MoneyStorage;

use App\Domain\Object\MoneyStorage\MoneyStorageAmount;
use PHPUnit\Framework\TestCase;

class MoneyStorageAmountTest extends TestCase
{

    public function test_construct(){
        try{
            new MoneyStorageAmount(0);
        }catch(\Exception $e){
            $this->assertSame('金額は0以上数値です', $e->getMessage());
        }
    }
}
