<?php

namespace App\Application\HouseholdAccount\service;

use App\Application\HouseholdAccount\query\AccountQuery;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Account;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\AccountType;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Increaser;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Reducer;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;
use App\Domain\HouseholdAccount\repository\TransactionRepository;
use App\infra\HouseholdAccount\EloquentRepository\ModelBuilder;
use App\infra\HouseholdAccount\inmemoryQuery\AccountInmemoryQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionService
{

    private AccountQuery $accountQuery;
    private TransactionRepository $transactionRepository;

    /**
     * @param AccountQuery $accountQuery
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(AccountQuery $accountQuery,TransactionRepository $transactionRepository)
    {
        $this->accountQuery = $accountQuery;

        $this->accountQuery->add(
            new Account(1,200,new AccountType(AccountType::TYPE_BANK))
        );
        $this->accountQuery->add(
            new Account(2,100,new AccountType(AccountType::TYPE_BANK))
        );

        $this->transactionRepository = $transactionRepository;
    }


    public function do(int $amount,int $transactionTypeValue,int $reduceAccountId,int $increaseAccountId, string $contents)
    {
        try{

            //取引IDをUUIDで生成
            $transactionId = (string)Str::orderedUuid();

            //取引日を現在日時で生成
            $transactionDate = new \Datetime();

            //取引金額を生成
            $transactionAmount = new TransactionAmount($amount);

            //取引内容
            $transactionContents = $contents;

            //取引を生成
            $transaction = new Transaction($transactionTypeValue, $transactionAmount);

            $reducer = null;
            if(isset($reduceAccountId)){
                $reduceAccount = $this->accountQuery->find($reduceAccountId);
                $reducer = new Reducer($reduceAccount);
            }

            $increaser = null;
            if(isset($increaseAccountId)){
                $increaseAccount = $this->accountQuery->find($increaseAccountId);
                $increaser = new Increaser($increaseAccount);
            }

            $accounts = $transaction->process($reducer, $increaser);


            $this->transactionRepository->save($transactionId,$transactionDate,$transactionContents,$transaction,$accounts);


        }catch (\Exception $e){
            $result = $e->getMessage();
            var_dump($result);
        }

    }
}
