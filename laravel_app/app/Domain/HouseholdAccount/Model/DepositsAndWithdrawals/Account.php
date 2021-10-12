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
     * @return Account
     */
    public function increase(TransactionAmount $transactionAmount): Account
    {
        $newBalance = $transactionAmount->increaseBalance($this->balance);

        return new Account($this->accountId,$newBalance,$this->accountType);

    }

    /**
     * @param TransactionAmount $transactionAmount
     * @return Account
     * @throws \Exception
     */
    public function reduce(TransactionAmount $transactionAmount): Account{

        $newBalance = $transactionAmount->reduceBalance($this->balance);

        return new Account($this->accountId,$newBalance,$this->accountType);

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
