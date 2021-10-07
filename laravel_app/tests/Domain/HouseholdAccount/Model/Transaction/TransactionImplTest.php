<?php

namespace Tests\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Account;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\AccountType;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Increaser;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Balancers;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Reducer;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionType;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;
use App\infra\HouseholdAccount\EloquentRepository\ModelBuilder;
use App\infra\HouseholdAccount\inmemoryQuery\AccountInmemoryQuery;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class TransactionImplTest extends TestCase
{

    public function test_口座間転送（AccountTransfer）正常生成、アカウント１⇨２に１９９円送金する(){

        $accountBalanceInmemoryQuery=new AccountInmemoryQuery();
        $accountBalanceInmemoryQuery->add(
            new Account(1,200,new AccountType(AccountType::TYPE_BANK))
        );
        $accountBalanceInmemoryQuery->add(
            new Account(2,100,new AccountType(AccountType::TYPE_BANK))
        );


        $result = "正常生成";
        try{

            //取引区分を生成
            $transactionType = new TransactionType(TransactionType::CLASSIFICATION_ACCOUNT_TRANSFER);

            //取引金額を生成
            $transactionAmount = new TransactionAmount(199);

            //取引IDをUUIDで生成
            $transactionId = (string)Str::orderedUuid();

            //取引日を現在日時で生成
            $transactionDate = new \Datetime();

            //取引内容
            $transactionContents = "";

            $wAccount = $accountBalanceInmemoryQuery->find(1);
            $reducer = new Reducer($wAccount);
            $dAccount = $accountBalanceInmemoryQuery->find(2);
            $increaser = new Increaser($dAccount);

            $accounts = $transactionType->updateBalance($transactionAmount,$reducer,$increaser);

            $transaction = new Transaction($transactionId,$transactionDate,$transactionAmount,$transactionContents/*,$updateBalances*/);


            $modelBuilder = new ModelBuilder();

            $transaction->notify($modelBuilder);

            $transactionType->notify($modelBuilder);

            for($i=0;$i<count($accounts);$i++){
                $accounts[$i]->notify($transactionId,$modelBuilder);
            }

        }catch (\Exception $e){
            $result = $e->getMessage();
        }

        $this->assertSame("正常生成",$result);

        foreach($modelBuilder->balances as $balance){
            if((int)($balance->accountId) === 1)
                $this->assertSame(1,$balance->balance);

            if((int)($balance->accountId) === 2)
                $this->assertSame(299,$balance->balance);
        }
    }
}
