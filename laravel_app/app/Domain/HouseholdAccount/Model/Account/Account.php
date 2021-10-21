<?php

namespace App\Domain\HouseholdAccount\Model\Account;

use App\Domain\HouseholdAccount\Model\Notification\Balance;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionAmount;

class Account
{
    /**
     * @var int 口座ID
     */
    private int $accountId;

    /**
     * @var int 増減タイプ
     */
    private int $increase_decrease_type;

    /**
     * @var AccountType アカウント種別
     */
    private AccountType $accountType;

    /**
     * @param int $accountId
     * @param int $increase_decrease_type
     * @param AccountType $accountType
     */
    public function __construct(int $accountId, int $increase_decrease_type, AccountType $accountType)
    {
        $this->accountId = $accountId;
        $this->increase_decrease_type = $increase_decrease_type;
        $this->accountType = $accountType;
    }

    /**
     * @return int
     */
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
     * @param TransactionAmount $transactionAmount
     * @return Account
     */
    public function increase(TransactionAmount $transactionAmount): Account
    {
//        $newBalance = $transactionAmount->increaseBalance($this->increase_decrease_type);
//
//        return new Account($this->accountId,$newBalance,$this->accountType);
        return new Account($this->accountId,$this->increase_decrease_type,$this->accountType);

    }

    /**
     * @param TransactionAmount $transactionAmount
     * @return Account
     * @throws \Exception
     */
    public function reduce(TransactionAmount $transactionAmount): Account{

//        $newBalance = $transactionAmount->reduceBalance($this->increase_decrease_type);
//
//        return new Account($this->accountId,$newBalance,$this->accountType);
        return new Account($this->accountId,$this->increase_decrease_type,$this->accountType);

    }

    /**
     * 通知
     * @param string $transactionId
     * @param NotificationTransaction $modelBuilder
     */
    public function notify(string $transactionId, NotificationTransaction $modelBuilder){
        $modelBuilder->addBalance(new Balance($transactionId,$this->accountId,$this->increase_decrease_type));
    }
}
