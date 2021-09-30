<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Deposits;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Increaser;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Reducer;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Withdrawals;

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
        ])) throw new \Exception('取引区分が不正です');

        $this->value = $value;
    }

    public function isAccountTransfer():bool{
        return $this->value == self::CLASSIFICATION_ACCOUNT_TRANSFER;
    }
    public function isCashAddition():bool{
        return $this->value == self::CLASSIFICATION_CASH_ADDITION;

    }
    public function isCashPayment():bool{
        return $this->value == self::CLASSIFICATION_CASH_PAYMENT;
    }
    public function isDirectDevit():bool{
        return $this->value == self::CLASSIFICATION_DIRECT_DEVIT;
    }
    public function isMoneyReceived():bool{
        return $this->value == self::CLASSIFICATION_MONEY_RECEIVED;
    }
    public function isWithdrawalDeposit():bool{
        return $this->value == self::CLASSIFICATION_WITHDRAWAL_DEPOSIT;
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
}
