<?php

namespace Tests\Domain\Object\MoneyStorage;

use App\Domain\Object\MoneyStorage\MoneyStorageId;
use PHPUnit\Framework\TestCase;

class MoneyStorageIdTest extends TestCase
{

    public function test_construct(){
        try{
            new MoneyStorageId(0);
        }catch (\Exception $e){
            $this->assertSame('ストレージIDは１以上です',$e->getMessage());
        }
    }
}
