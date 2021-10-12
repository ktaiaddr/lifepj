<?php

namespace App\Application\HouseholdAccount\service;

use App\Application\HouseholdAccount\query\AccountBalanceQuery;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Increaser;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Reducer;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;
use App\Domain\HouseholdAccount\repository\TransactionRepository;
use Illuminate\Support\Str;

class TransactionService
{

    private AccountBalanceQuery $accountQuery;
    private TransactionRepository $transactionRepository;

    /**
     * @param AccountBalanceQuery $accountQuery
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(AccountBalanceQuery $accountQuery, TransactionRepository $transactionRepository)
    {
        $this->accountQuery = $accountQuery;
        $this->transactionRepository = $transactionRepository;
    }


    public function do(int $amount,int $transactionTypeValue,int $reduceAccountId,int $increaseAccountId, string $contents,int $user_id)
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

            //レデューサーをnullで初期化
            $reducer = null;
            //減らす側のIDが渡されたらリポジトリからデータを取得してレデューサーを生成
            if(isset($reduceAccountId)){
                $reduceAccount = $this->accountQuery->find($reduceAccountId);
                $reducer = new Reducer($reduceAccount);
            }

            //インクリージャーをnullで初期か
            $increaser = null;
            //増やす側のIDが渡されたらリポジトリからデータを取得してインクリージャーを生成
            if(isset($increaseAccountId)){
                $increaseAccount = $this->accountQuery->find($increaseAccountId);
                $increaser = new Increaser($increaseAccount);
            }

            $accounts = $transaction->process($reducer, $increaser);

            $this->transactionRepository->save($transactionId,$transactionDate,$transactionContents,$transaction,$accounts,$user_id);


        }catch (\Exception $e){
            $result = $e->getMessage();
            var_dump($result);
        }

    }
}
