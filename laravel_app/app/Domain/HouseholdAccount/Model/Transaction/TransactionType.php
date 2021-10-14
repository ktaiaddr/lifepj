<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;

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

    private int $transactionTypeValue;

    public function getLabel(){
        return self::LABEL[ $this->transactionTypeValue ];
    }

    /**
     * @param int $transactionTypeValue
     */
    public function __construct(int $transactionTypeValue)
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
    }
    public function notify(NotificationTransaction $modelBuilder)
    {
        $modelBuilder->transactionType($this->transactionTypeValue);

    }

    public function isAccountTransfer():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_ACCOUNT_TRANSFER;
    }
    public function isCashAddition():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_CASH_ADDITION;
    }
    public function isCashPayment():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_CASH_PAYMENT;
    }
    public function isDirectDevit():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_DIRECT_DEVIT;
    }
    public function isMoneyReceived():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_MONEY_RECEIVED;
    }
    public function isWithdrawalDeposit():bool{
        return $this->transactionTypeValue == self::CLASSIFICATION_WITHDRAWAL_DEPOSIT;
    }

}
