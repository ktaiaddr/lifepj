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
    const VALUE_LABEL = [
        ['value'=>self::CLASSIFICATION_ACCOUNT_TRANSFER  ,'label'=> '口座振替'],
        ['value'=>self::CLASSIFICATION_CASH_ADDITION  ,'label'=> '現金加算'],
        ['value'=>self::CLASSIFICATION_CASH_PAYMENT  ,'label'=> '現金払い'],
        ['value'=>self::CLASSIFICATION_DIRECT_DEVIT  ,'label'=> '口座引落し'],
        ['value'=>self::CLASSIFICATION_MONEY_RECEIVED  ,'label'=> '入金'],
        ['value'=>self::CLASSIFICATION_WITHDRAWAL_DEPOSIT  ,'label'=> '引き出し'],
    ];

    private int $transactionTypeValue;

    public function getLabel(){
        $filtered = array_filter(self::VALUE_LABEL,fn($a)=> $a['value'] == $this->transactionTypeValue);
        return array_shift($filtered)['label'];
    }

    /**
     * @return TransactionTypeDefine[]
     */
    public static function getTypeDefines(): array
    {
        return array_map(fn($a,$b) => new TransactionTypeDefine($a['value'], $a['label']),self::VALUE_LABEL,[]);
    }

    /**
     * @param int $transactionTypeValue
     * @throws \Exception
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

    public function isAccountTransfer()   :bool { return $this->transactionTypeValue == self::CLASSIFICATION_ACCOUNT_TRANSFER   ;}
    public function isCashAddition()      :bool { return $this->transactionTypeValue == self::CLASSIFICATION_CASH_ADDITION      ;}
    public function isCashPayment()       :bool { return $this->transactionTypeValue == self::CLASSIFICATION_CASH_PAYMENT       ;}
    public function isDirectDevit()       :bool { return $this->transactionTypeValue == self::CLASSIFICATION_DIRECT_DEVIT       ;}
    public function isMoneyReceived()     :bool { return $this->transactionTypeValue == self::CLASSIFICATION_MONEY_RECEIVED     ;}
    public function isWithdrawalDeposit() :bool { return $this->transactionTypeValue == self::CLASSIFICATION_WITHDRAWAL_DEPOSIT ;}

}
