<?php

namespace App\Domain\HouseholdAccount\Model\Notification;

class Balance
{
    public string $transactionId;
    public int $accountId;
    public int $balance;

    /**
     * @param string $transactionId
     * @param int $accountId
     * @param int $balance
     */
    public function __construct(string $transactionId, int $accountId, int $balance)
    {
        $this->transactionId = $transactionId;
        $this->accountId = $accountId;
        $this->balance = $balance;
    }

}
