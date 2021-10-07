<?php

namespace App\infra\HouseholdAccount\EloquentRepository;

use App\Domain\HouseholdAccount\Model\Notification\Balance;

class ModelBuilder implements \App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction
{
    /**
     * @var string
     */
    public string $transactionId;
    /**
     * @var \DateTime
     */
    public \DateTime $transactionDate;
    /**
     * @var int
     */
    public int $transactionAmount;
    /**
     * @var string
     */
    public string $transactionContents;
    /**
     * @var int
     */
    public int $transactionType;

    /**
     * @var Balance[]
     */
    public array $balances;

    public function transactionId(string $transactionId): void
    {
        // TODO: Implement transactionId() method.
        $this->transactionId = $transactionId;
    }

    public function transactionDate( \Datetime $transactionDate ):void {
        $this->transactionDate = $transactionDate;

    }

    public function transactionAmount(int $transactionAmount): void
    {
        // TODO: Implement transactionAmount() method.
        $this->transactionAmount = $transactionAmount;
    }

    public function transactionContents(string $transactionContents): void
    {
        $this->transactionContents = $transactionContents;
    }


    public function transactionType(int $transactionType): void
    {
        $this->transactionType = $transactionType;
    }

    public function addBalance(Balance $balance): void
    {
        $this->balances[] = $balance;
    }
}
