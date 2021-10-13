<?php

namespace Tests\Domain\HouseholdAccount\Model\ValueObject;

use App\Domain\HouseholdAccount\Model\Transaction\TransactionAmount;
use PHPUnit\Framework\TestCase;

class TransactionAmountTest extends TestCase
{

    public function test_取引金額正常(){

        $result = "正常生成";
        try{
            $amount = new TransactionAmount(100);
        }
        catch(\Exception $e){
            $result = $e->getMessage();
        }

        //例外発生なく正常に生成されていることを確認
        $this->assertSame($result,"正常生成");

    }

    public function test_取引金額が0円以下で例外発生(){

        $result = "正常生成";
        try{
            $amount = new TransactionAmount(0);
        }
        catch(\Exception $e){
            $result = $e->getMessage();//取引金額は1円以上である必要があります
        }

        //例外発生なく正常に生成されていることを確認
        $this->assertSame($result,"取引金額は1円以上である必要があります");

    }

}
