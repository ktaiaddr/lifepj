<?php

namespace Tests\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\AccountBalance;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Deposits;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Withdrawals;
use App\Domain\HouseholdAccount\Model\Transaction\AccountTransfer;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class AccountTransferTest extends TestCase
{
    public function test_AccountTransfer正常生成(){

        $transactionId = (string)Str::orderedUuid();
        $transactionDate = new \Datetime();
        $transactionAmount = new TransactionAmount(100);
        $wAccount = new AccountBalance(1,200,AccountBalance::TYPE_BANK);
        $withdrawals = new Withdrawals($wAccount);
        $dAccount = new AccountBalance(2,100,AccountBalance::TYPE_BANK);
        $deposits = new Deposits($dAccount);

        $result = "正常生成";
        try{
            new AccountTransfer(
                $transactionId
                ,$transactionDate
                ,$transactionAmount
                ,$withdrawals
                ,$deposits
            );
        }catch(\Exception $e){
            $result = $e->getMessage();
        }

        $this->assertSame($result,"正常生成");
    }


    public function test_AccountTransfer移転元が不正(){

        $transactionId = (string)Str::orderedUuid();
        $transactionDate = new \Datetime();
        $transactionAmount = new TransactionAmount(100);
        $wAccount = new AccountBalance(1,200,AccountBalance::TYPE_HAND_MONEY);//口座振替なのに移転元がハンドマネー
        $withdrawals = new Withdrawals($wAccount);
        $dAccount = new AccountBalance(2,100,AccountBalance::TYPE_BANK);
        $deposits = new Deposits($dAccount);

        $result = "正常生成";
        try{
            new AccountTransfer(
                $transactionId
                ,$transactionDate
                ,$transactionAmount
                ,$withdrawals
                ,$deposits
            );
        }catch(\Exception $e){
            $result = $e->getMessage();
        }

        $this->assertSame($result,"移動元の口座種別が不正です");
    }

    public function test_AccountTransfer移転先が不正(){

        $transactionId = (string)Str::orderedUuid();
        $transactionDate = new \Datetime();
        $transactionAmount = new TransactionAmount(100);
        $wAccount = new AccountBalance(1,200,AccountBalance::TYPE_BANK);
        $withdrawals = new Withdrawals($wAccount);
        $dAccount = new AccountBalance(2,100,AccountBalance::TYPE_HAND_MONEY);//口座振替なのに移転先がハンドマネー
        $deposits = new Deposits($dAccount);

        $result = "正常生成";
        try{
            new AccountTransfer(
                $transactionId
                ,$transactionDate
                ,$transactionAmount
                ,$withdrawals
                ,$deposits
            );
        }catch(\Exception $e){
            $result = $e->getMessage();
        }

        $this->assertSame($result,"移動先の口座種別が不正です");
    }

    public function test_updateBalance正常パターン(){

        $transactionId = (string)Str::orderedUuid();
        $transactionDate = new \Datetime();
        $transactionAmount = new TransactionAmount(231);

        $wAccount = new AccountBalance(1,500,AccountBalance::TYPE_BANK);
        $withdrawals = new Withdrawals($wAccount);
        $dAccount = new AccountBalance(2,600,AccountBalance::TYPE_BANK);
        $deposits = new Deposits($dAccount);

        $result = "正常終了";
        try{
            $transaction = new AccountTransfer(
                $transactionId
                ,$transactionDate
                ,$transactionAmount
                ,$withdrawals
                ,$deposits
            );

            $transaction->updateBalance();


            // Reflectionクラスをインスタンス化
            $transactionReflectionClass = new \ReflectionClass(AccountTransfer::class);
            $withdrawalsReflectionClass = new \ReflectionClass(Withdrawals::class);
            $depositsReflectionClass = new \ReflectionClass(Deposits::class);
            $accountBalanceReflectionClass = new \ReflectionClass(AccountBalance::class);

            $withdrawalsProperty = $transactionReflectionClass->getProperty('withdrawals');
            $depositsProperty = $transactionReflectionClass->getProperty('deposits');
            $wAccountBalanceProperty = $withdrawalsReflectionClass->getProperty('accountBalance');
            $dAccountBalanceProperty = $depositsReflectionClass->getProperty('accountBalance');
            $balanceProperty = $accountBalanceReflectionClass->getProperty('balance');

            $withdrawalsProperty->setAccessible(true);
            $depositsProperty->setAccessible(true);
            $wAccountBalanceProperty->setAccessible(true);
            $dAccountBalanceProperty->setAccessible(true);
            $balanceProperty->setAccessible(true);

            $withdrawals = $withdrawalsProperty->getValue($transaction);
            $deposits = $depositsProperty->getValue($transaction);
            $wAccountBalance = $wAccountBalanceProperty->getValue($withdrawals);
            $dAccountBalance = $dAccountBalanceProperty->getValue($deposits);

            $wBalance = $balanceProperty->getValue($wAccountBalance);
            $dBalance = $balanceProperty->getValue($dAccountBalance);


        }catch(\Exception $e){
            $result = $e->getMessage();
        }
        var_dump($wBalance);
        var_dump($dBalance);
        $this->assertSame("正常終了",$result);
        $this->assertSame(269,$wBalance);
        $this->assertSame(831,$dBalance);
    }

    public function test_updateBalance残高不足パターン(){

        $transactionId = (string)Str::orderedUuid();
        $transactionDate = new \Datetime();
        $transactionAmount = new TransactionAmount(1000);//出金額

        $wAccount = new AccountBalance(1,999,AccountBalance::TYPE_BANK);//出金元残高←足りないので例外
        $withdrawals = new Withdrawals($wAccount);
        $dAccount = new AccountBalance(2,100,AccountBalance::TYPE_BANK);
        $deposits = new Deposits($dAccount);

        $result = "正常終了";

        try{
            $transaction = new AccountTransfer(
                $transactionId
                ,$transactionDate
                ,$transactionAmount
                ,$withdrawals
                ,$deposits
            );

            $transaction->updateBalance();

        }catch(\Exception $e){
            $result = $e->getMessage();
        }

        $this->assertSame("残高不足で処理できません",$result);
    }
}
