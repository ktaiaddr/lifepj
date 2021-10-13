<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

class RegisterCommand
{
    public int $amount;
    public int $transactionTypeValue;
    public ?int $reduceAccountId;
    public ?int $increaseAccountId;
    public string $contents;

    /**
     * @param int $amount
     * @param int $transactionTypeValue
     * @param int|null $reduceAccountId
     * @param int|null $increaseAccountId
     * @param string $contents
     */
    public function __construct(int $amount, int $transactionTypeValue, ?int $reduceAccountId, ?int $increaseAccountId, string $contents)
    {
        $this->amount = $amount;
        $this->transactionTypeValue = $transactionTypeValue;
        $this->reduceAccountId = $reduceAccountId;
        $this->increaseAccountId = $increaseAccountId;
        $this->contents = $contents;
    }


}
