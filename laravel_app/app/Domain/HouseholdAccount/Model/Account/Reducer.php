<?php

namespace App\Domain\HouseholdAccount\Model\Account;

use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionAmount;

class Reducer implements Balancer
{
    private Account $account;
    /**
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @param TransactionAmount $transactionAmount
     * @return Account
     * @throws \Exception
     */
    public function exec(TransactionAmount $transactionAmount):Account
    {
            return $this->account->reduce($transactionAmount);
    }

    public function targetIsBank(): bool
    {
        return $this->account->isBank();
    }

    public function targetIsHandMoney(): bool
    {
        return $this->account->isHandMoney();
    }

}
