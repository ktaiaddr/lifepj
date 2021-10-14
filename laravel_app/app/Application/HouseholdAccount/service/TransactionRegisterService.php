<?php

namespace App\Application\HouseholdAccount\service;

use App\Application\HouseholdAccount\query\AccountBalanceQuery;
use App\Domain\HouseholdAccount\Model\Account\Increaser;
use App\Domain\HouseholdAccount\Model\Account\Reducer;
use App\Domain\HouseholdAccount\Model\Transaction\RegisterCommand;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionAmount;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionType;
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

    /**
     * @param RegisterCommand $registerCommand
     * @param int $user_id
     * @throws \Exception
     */
    public function do(RegisterCommand $registerCommand, int $user_id)
    {
        try{

            //取引IDをUUIDで生成
            $transactionId = (string)Str::orderedUuid();

            //取引日を現在日時で生成
            $transactionDate = new \Datetime();

            //取引金額を生成
            $transactionAmount = new TransactionAmount($registerCommand->amount);

            //取引内容
            $transactionContents = $registerCommand->contents;

            //取引を生成
            $transaction = new Transaction(new TransactionType($registerCommand->transactionTypeValue), $transactionAmount);

            //減らす側のIDが渡されたらリポジトリからデータを取得してレデューサーを生成
            $reducer = $this->getReducer($registerCommand->reduceAccountId);

            //増やす側のIDが渡されたらリポジトリからデータを取得してインクリージャーを生成
            $increaser = $this->getIncreaser($registerCommand->increaseAccountId);

            //取引を実行
            $accounts = $transaction->process($reducer, $increaser);

            //取引を永続化
            $bool = $this->transactionRepository->save($transactionId,$transactionDate,$transactionContents,$transaction,$accounts,$user_id);

        }catch (\Exception $e){
            $result = $e->getMessage();

            throw $e;
        }

        return $transactionId;
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
     * @return Increaser|null
     */
    private function getIncreaser(?int $increaseAccountId){

        if(isset($increaseAccountId))
            return new Increaser($this->accountQuery->find($increaseAccountId));

        return null;

    }

}
