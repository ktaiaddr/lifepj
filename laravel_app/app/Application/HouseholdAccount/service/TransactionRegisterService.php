<?php

namespace App\Application\HouseholdAccount\service;

use App\Application\HouseholdAccount\query\AccountBalanceQuery;
use App\Domain\HouseholdAccount\Model\Account\Increaser;
use App\Domain\HouseholdAccount\Model\Account\Reducer;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionAmount;
use App\Domain\HouseholdAccount\repository\TransactionRepository;
use Illuminate\Support\Str;

class TransactionRegisterService
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


    public function do(
        int $amount,
        int $transactionTypeValue,
        ?int $reduceAccountId,
        ?int $increaseAccountId,
        string $contents,
        int $user_id)
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

            //減らす側のIDが渡されたらリポジトリからデータを取得してレデューサーを生成
            $reducer = $this->getReducer($reduceAccountId);

            //増やす側のIDが渡されたらリポジトリからデータを取得してインクリージャーを生成
            $increaser = $this->getIncreaser($increaseAccountId);

            //取引を実行
            $accounts = $transaction->process($reducer, $increaser);

            //取引を永続化
            $this->transactionRepository->save($transactionId,$transactionDate,$transactionContents,$transaction,$accounts,$user_id);

        }catch (\Exception $e){
            $result = $e->getMessage();
            var_dump($result);
        }

    }

    /**
     * @param int|null $reduceAccountId
     * @return Reducer|null
     */
    private function getReducer(?int $reduceAccountId){

        if(isset($reduceAccountId))
            return new Reducer($this->accountQuery->find($reduceAccountId));

        return null;
    }

    /**
     * @param int|null $increaseAccountId
     * @return Reducer|null
     */
    private function getIncreaser(?int $increaseAccountId){

        if(isset($increaseAccountId))
            return new Reducer($this->accountQuery->find($increaseAccountId));

        return null;

    }

}
