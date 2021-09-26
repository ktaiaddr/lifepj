<?php

namespace App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals;

use App\Domain\HouseholdAccount\AccountBalance;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

class Deposits implements Updater
{

    private AccountBalance $accountBalance;

    /**
     * @param AccountBalance $accountBalance
     */
    public function __construct(AccountBalance $accountBalance)
    {
        $this->accountBalance = $accountBalance;
    }


    /**
     * @param TransactionAmount $transactionAmount
     */
    public function updateBalance(TransactionAmount $transactionAmount)
    {
        $this->accountBalance->increaseBalance($transactionAmount);

    }

    public function notify(string $transactionId, NotificationTransaction $modelBuilder){
        $this->accountBalance->notify($transactionId,  $modelBuilder);
    }
    public function isBank(){
        return $this->accountBalance->isBank();
    }
    public function isHandMoney(){
        return $this->accountBalance->isHandMoney();
    }
}
