<?php

namespace App\Application\HouseholdAccount\service;

use App\Application\HouseholdAccount\query\AccountQuery;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Account;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\AccountType;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Balancers;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Increaser;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Reducer;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionType;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;
use App\infra\HouseholdAccount\EloquentRepository\ModelBuilder;
use App\infra\HouseholdAccount\inmemoryQuery\AccountInmemoryQuery;
use Illuminate\Support\Str;

class TransactionService
{

    private AccountQuery $accountQuery;

    /**
     * @param AccountQuery $accountQuery
     */
    public function __construct(AccountQuery $accountQuery)
    {
        $this->accountQuery = $accountQuery;

        $this->accountQuery->add(
            new Account(1,200,new AccountType(AccountType::TYPE_BANK))
        );
        $this->accountQuery->add(
            new Account(2,100,new AccountType(AccountType::TYPE_BANK))
        );

    }


    public function __invoke(TransactionAmount $transactionAmount,TransactionType $transactionType, string $contents)
    {

        try{

            $transactionId = (string)Str::orderedUuid();

            $transactionDate = new \Datetime();

            $transaction = new Transaction($transactionId,$transactionDate,$transactionAmount,$contents/*,$updateBalances*/);


            $wAccount = $this->accountQuery->find(1);
            $reducer = new Reducer($wAccount);

            $dAccount = $this->accountQuery->find(2);
            $increaser = new Increaser($dAccount);

            $updateBalances = new Balancers($transactionType,$reducer,$increaser);

            $accounts = $updateBalances->updateBalance($transactionAmount);


            $modelBuilder = new ModelBuilder();
            $transaction->notify($modelBuilder);
            $transactionType->notify($modelBuilder);

            for($i=0;$i<count($accounts);$i++){
                $accounts[$i]->notify($transactionId,$modelBuilder);
            }

        }catch (\Exception $e){
            $result = $e->getMessage();
        }

    }
}
