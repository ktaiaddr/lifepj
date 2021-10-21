<?php

namespace App\Domain\HouseholdAccount\Model\Notification;

class Balance
{
    public string $transactionId;
    public int $accountId;
    public int $increase_decrease_type;

    /**
     * @param string $transactionId
     * @param int $accountId
     * @param int $increase_decrease_type
     */
    public function __construct(string $transactionId, int $accountId, int $increase_decrease_type)
    {
        $this->transactionId = $transactionId;
        $this->accountId = $accountId;
        $this->increase_decrease_type = $increase_decrease_type;
    }

}
