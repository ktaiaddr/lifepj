<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Account;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Balancer;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Deposits;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Increaser;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Reducer;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Withdrawals;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;
use App\infra\HouseholdAccount\EloquentRepository\ModelBuilder;

class TransactionType
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

    private int $value;

    /**
     * @param int $value
     * @throws \Exception
     */
    public function __construct(int $value)
    {
        if(! in_array($value,[
            self::CLASSIFICATION_ACCOUNT_TRANSFER,
            self::CLASSIFICATION_CASH_ADDITION,
            self::CLASSIFICATION_CASH_PAYMENT,
            self::CLASSIFICATION_DIRECT_DEVIT,
            self::CLASSIFICATION_MONEY_RECEIVED,
            self::CLASSIFICATION_WITHDRAWAL_DEPOSIT,
        ]))
            throw new \Exception('取引区分が不正です');

        $this->value = $value;
    }

    /**
     * @param TransactionAmount $transactionAmount
     * @return Account[]
     */
    public function updateBalance(TransactionAmount $transactionAmount,Reducer $reducer,Increaser $increaser){

        if(! $this->inspectionBalancers($reducer, $increaser)){
            throw new \Exception($this->getLabel().'アカウントエラー');
        }

        $balancers = $this->takeBalancers($reducer, $increaser);

        $fn = fn(Balancer $updateBalance):Account=>$updateBalance->updateBalance($transactionAmount);
        return array_map($fn,$balancers);

    }
    /**
     * @return string
     */
    public function getLabel(){
        return self::LABEL[ $this->value ];
    }

    /**
     * Blanacerオブジェクトが正しいか検査
     * @param Reducer|null $reducer
     * @param Increaser|null $increaser
     * @return bool
     * @throws \Exception
     */
    public function inspectionBalancers(?Reducer $reducer, ?Increaser $increaser):bool{

        if($this->isAccountTransfer())
            return $reducer && $reducer->hasBankTypeAccount() && $increaser && $increaser->hasBankTypeAccount();

        if($this->isCashAddition())
            return !$reducer && $increaser && $increaser->hasHandMoneyAccount();

        if($this->isCashPayment())
            return $reducer && $reducer->hasHandMoneyAccount() && !$increaser;

        if($this->isDirectDevit())
            return $reducer && $reducer->hasBankTypeAccount()  && !$increaser;

        if($this->isMoneyReceived())
            return !$reducer && $increaser && $increaser->hasBankTypeAccount();

        if($this->isWithdrawalDeposit())
            return $reducer && $reducer->hasBankTypeAccount() && $increaser && $increaser->hasHandMoneyAccount();

    }

    /**
     * @param Reducer|null $reducer
     * @param Increaser|null $increaser
     * @return Balancer[]
     */
    public function takeBalancers(?Reducer $reducer, ?Increaser $increaser):array{
        //口座間転送
        if($this->isAccountTransfer()) return [$reducer,$increaser];
        //現金加算
        if($this->isCashAddition()) return [$increaser];
        //現金払い
        if($this->isCashPayment()) return [$reducer];
        //引き落とし
        if($this->isDirectDevit()) return [$reducer];
        //入金
        if($this->isMoneyReceived()) return [$increaser];
        //現金引き出し
        if($this->isWithdrawalDeposit()) return [$reducer,$increaser];
    }

    public function notify(NotificationTransaction $modelBuilder){
        $modelBuilder->transactionType($this->value);
    }

    private function isAccountTransfer():bool{
        return $this->value == self::CLASSIFICATION_ACCOUNT_TRANSFER;
    }
    private function isCashAddition():bool{
        return $this->value == self::CLASSIFICATION_CASH_ADDITION;
    }
    private function isCashPayment():bool{
        return $this->value == self::CLASSIFICATION_CASH_PAYMENT;
    }
    private function isDirectDevit():bool{
        return $this->value == self::CLASSIFICATION_DIRECT_DEVIT;
    }
    private function isMoneyReceived():bool{
        return $this->value == self::CLASSIFICATION_MONEY_RECEIVED;
    }
    private function isWithdrawalDeposit():bool{
        return $this->value == self::CLASSIFICATION_WITHDRAWAL_DEPOSIT;
    }
}
