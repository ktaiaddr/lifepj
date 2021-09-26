<?php

namespace App\infra\HouseholdAccount\EloquentRepository;

use App\Domain\HouseholdAccount\Model\Notification\Balance;

class ModelBuilder implements \App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction
{
    /**
     * @var int
     */
    public int $transactionId;

    public function transactionId(int $transactionId): void
    {
        // TODO: Implement transactionId() method.
        $this->transactionId = $transactionId;
    }

    public int $transactionDate;

    public function transactionDate( \Datetime $transactionDate ):void {
        $this->transactionDate = $transactionDate;

    }

    /**
     * @var int
     */
    public int $transactionAmount;

    public function transactionAmount(int $transactionAmount): void
    {
        // TODO: Implement transactionAmount() method.
        $this->transactionAmount = $transactionAmount;
    }

    /**
     * @var int
     */
    public int $transactionClass;
    public function transactionClass(int $transactionClass): void
    {
        // TODO: Implement transactionClass() method.
        $this->transactionClass = $transactionClass;
    }

    /**
     * @var Balance[]
     */
    public array $balances;
    public function addBalance(Balance $balance): void
    {
        $this->balances[] = $balance;
    }
}
