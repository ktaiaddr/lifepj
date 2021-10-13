<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\Account\Account;
use App\Domain\HouseholdAccount\Model\Account\Balancer;
use App\Domain\HouseholdAccount\Model\Account\Deposits;
use App\Domain\HouseholdAccount\Model\Account\Increaser;
use App\Domain\HouseholdAccount\Model\Account\Reducer;
use App\Domain\HouseholdAccount\Model\Account\Withdrawals;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionAmount;
use App\infra\HouseholdAccount\EloquentRepository\ModelBuilder;

class Transaction
{
    /**
     * 口座振替
     */
    const CLASSIFICATION_ACCOUNT_TRANSFER = 1;
    /**
     * 現金加算
     */
    const CLASSIFICATION_CASH_ADDITION = 2;
    /**
     * 現金払い
     */
    const CLASSIFICATION_CASH_PAYMENT = 3;
    /**
     * 口座引落し
     */
    const CLASSIFICATION_DIRECT_DEVIT = 4;
    /**
     * 入金
     */
    const CLASSIFICATION_MONEY_RECEIVED = 5;
    /**
     * 引き出し
     */
    const CLASSIFICATION_WITHDRAWAL_DEPOSIT = 6;
    const LABEL = [
        self::CLASSIFICATION_ACCOUNT_TRANSFER  => '口座振替',
        self::CLASSIFICATION_CASH_ADDITION  => '現金加算',
        self::CLASSIFICATION_CASH_PAYMENT  => '現金払い',
        self::CLASSIFICATION_DIRECT_DEVIT  => '口座引落し',
        self::CLASSIFICATION_MONEY_RECEIVED  => '入金',
        self::CLASSIFICATION_WITHDRAWAL_DEPOSIT  => '引き出し',
    ];

    private int $transactionTypeValue;

    private TransactionAmount $transactionAmount;

    /**
     * @param int $transactionTypeValue
     * @param TransactionAmount $transactionAmount
     * @throws \Exception
     */
    public function __construct(int $transactionTypeValue, TransactionAmount $transactionAmount)
    {
        if(! in_array($transactionTypeValue,[
            self::CLASSIFICATION_ACCOUNT_TRANSFER,
            self::CLASSIFICATION_CASH_ADDITION,
            self::CLASSIFICATION_CASH_PAYMENT,
            self::CLASSIFICATION_DIRECT_DEVIT,
            self::CLASSIFICATION_MONEY_RECEIVED,
            self::CLASSIFICATION_WITHDRAWAL_DEPOSIT,
        ]))
            throw new \Exception('取引区分が不正です');

        $this->transactionTypeValue = $transactionTypeValue;
        $this->transactionAmount = $transactionAmount;
    }

    /**
     * @param Reducer|null $reducer
     * @param Increaser|null $increaser
     * @return Account[]
     * @throws \Exception
     */
    public function process(?Reducer $reducer, ?Increaser $increaser):array{

        //array_mapクロージャー
        $fn = fn(Balancer $balancer):Account => $balancer->exec($this->transactionAmount);

        return array_map($fn, $this->takeBalancers($reducer, $increaser));
    }

    /**
     * @return string
     */
    public function getLabel(){
        return self::LABEL[ $this->transactionTypeValue ];
    }

    /**
     * Blanacerオブジェクトが正しいか検査
     * @param Reducer|null $reducer
     * @param Increaser|null $increaser
     * @return bool
     * @throws \Exception
     */
    private function inspectionBalancers(?Reducer $reducer, ?Increaser $increaser):bool{

        if($this->isAccountTransfer())
            return $reducer && $reducer->targetIsBank() && $increaser && $increaser->targetIsBank();

        if($this->isCashAddition())
            return !$reducer && $increaser && $increaser->targetIsHandMoney();

        if($this->isCashPayment())
            return $reducer && $reducer->targetIsHandMoney() && !$increaser;

        if($this->isDirectDevit())
            return $reducer && $reducer->targetIsBank()  && !$increaser;

        if($this->isMoneyReceived())
            return !$reducer && $increaser && $increaser->targetIsBank();

        if($this->isWithdrawalDeposit())
            return $reducer && $reducer->targetIsBank() && $increaser && $increaser->targetIsHandMoney();

    }

    /**
     * @param Reducer|null $reducer
     * @param Increaser|null $increaser
     * @return Balancer[]
     */
    private function takeBalancers(?Reducer $reducer, ?Increaser $increaser):array{

        //残高のバランサーを検査する
        if(! $this->inspectionBalancers($reducer, $increaser))
            throw new \Exception($this->getLabel().'アカウントエラー');

        //口座間転送
        if($this->isAccountTransfer())
            return [$reducer,$increaser];

        //現金加算
        if($this->isCashAddition())
            return [$increaser];

        //現金払い
        if($this->isCashPayment())
            return [$reducer];

        //引き落とし
        if($this->isDirectDevit())
            return [$reducer];

        //入金
        if($this->isMoneyReceived())
            return [$increaser];

        //現金引き出し
        if($this->isWithdrawalDeposit())
            return [$reducer,$increaser];
    }

    public function notify(NotificationTransaction $modelBuilder){
        $modelBuilder->transactionType($this->transactionTypeValue);
        $this->transactionAmount->notify($modelBuilder);

    }

    private function isAccountTransfer():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_ACCOUNT_TRANSFER;
    }
    private function isCashAddition():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_CASH_ADDITION;
    }
    private function isCashPayment():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_CASH_PAYMENT;
    }
    private function isDirectDevit():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_DIRECT_DEVIT;
    }
    private function isMoneyReceived():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_MONEY_RECEIVED;
    }
    private function isWithdrawalDeposit():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_WITHDRAWAL_DEPOSIT;
    }
}
