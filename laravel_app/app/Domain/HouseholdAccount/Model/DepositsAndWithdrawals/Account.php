<?php

namespace App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals;

use App\Domain\HouseholdAccount\Model\Notification\Balance;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

class Account
{
    /**
     * @var int 口座ID
     */
    private int $accountId;


    /**
     * @var int 残高
     */
    private int $balance;

    /**
     * @var AccountType アカウント種別
     */
    private AccountType $accountType;

    public function getAccountId(){
        return $this->accountId;
    }
    /**
     * アカウントのタイプが銀行ならtrue
     * @return bool
     */
    public function isBank(){
        return $this->accountType->isBank();
    }
    /**
     * @return bool
     */
    public function isHandMoney(){
        return $this->accountType->isHandMoney();
    }

    /**
     * @param int $accountId
     * @param int $balance
     * @param AccountType $accountType
     */
    public function __construct(int $accountId, int $balance, AccountType $accountType)
    {
        $this->accountId = $accountId;
        $this->balance = $balance;
        $this->accountType = $accountType;
    }

    /**
     * @param TransactionAmount $transactionAmount
     */
    public function increase(TransactionAmount $transactionAmount){

        $this->balance = $transactionAmount->increaseBalance($this->balance);
    }
    /**
     * @param TransactionAmount $transactionAmount
     */
    public function reduce(TransactionAmount $transactionAmount){
        $this->balance = $transactionAmount->reduceBalance($this->balance);
    }


    /**
     * 通知
     * @param string $transactionId
     * @param NotificationTransaction $modelBuilder
     */
    public function notify(string $transactionId, NotificationTransaction $modelBuilder){
        $modelBuilder->addBalance(new Balance($transactionId,$this->accountId,$this->balance));
    }
}
