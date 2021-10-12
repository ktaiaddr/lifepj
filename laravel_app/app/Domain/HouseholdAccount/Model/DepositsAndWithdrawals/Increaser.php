<?php

namespace App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals;


use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

class Increaser implements Balancer
{
    private Account $accountBalance;
    /**
     * @param Account $accountBalance
     */
    public function __construct(Account $accountBalance)
    {
        $this->accountBalance = $accountBalance;
    }

    /**
     * @param TransactionAmount $transactionAmount
     * @return Account
     */
    public function exec(TransactionAmount $transactionAmount):Account
    {
        return $this->accountBalance->increase($transactionAmount);
    }

    public function notify(string $transactionId, NotificationTransaction $modelBuilder){
        $this->accountBalance->notify($transactionId,  $modelBuilder);
    }

    public function targetIsBank(): bool
    {
        return $this->accountBalance->isBank();
    }

    public function targetIsHandMoney(): bool
    {
        return $this->accountBalance->isHandMoney();
    }
}
