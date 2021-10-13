<?php

namespace Tests\Domain\Model;


use App\Domain\HouseholdAccount\Model\Account\Account;
use App\Domain\HouseholdAccount\Model\Account\AccountType;
use App\Domain\HouseholdAccount\Model\Account\Deposits;
use App\Domain\HouseholdAccount\Model\Account\Balancer;
use App\Domain\HouseholdAccount\Model\Account\Withdrawals;
use PHPUnit\Framework\TestCase;

class AccountBalanceTest extends TestCase
{

    public function test_口座残高オブジェクト生成(){

        $accountId = 1;
        $account = new Account($accountId,100,new AccountType(AccountType::TYPE_BANK));
        //銀行であることを確認
        $this->assertTrue($account->isBank());
        //ハンドマネーでないことを確認
        $this->assertFalse($account->isHandMoney());
    }

    public function test_ハンドマネー残高オブジェクト生成(){

        $accountId = 2;
        $account = new Account($accountId,100,new AccountType(AccountType::TYPE_HAND_MONEY));
        //銀行であることを確認
        $this->assertFalse($account->isBank());
        //ハンドマネーでないことを確認
        $this->assertTrue($account->isHandMoney());
    }

}
