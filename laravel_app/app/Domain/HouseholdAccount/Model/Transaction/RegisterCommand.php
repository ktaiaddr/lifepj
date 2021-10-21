<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

class RegisterCommand
{
    public int $amount;
    public string $date;
    public int $transactionTypeValue;
    public ?int $reduceAccountId;
    public ?int $increaseAccountId;
    public string $contents;

    /**
     * @param int $amount
     * @param string $date
     * @param int $transactionTypeValue
     * @param int|null $reduceAccountId
     * @param int|null $increaseAccountId
     * @param string $contents
     */
    public function __construct(int $amount, string $date, int $transactionTypeValue, ?int $reduceAccountId, ?int $increaseAccountId, string $contents)
    {
        $this->amount = $amount;
        $this->date = $date;
        $this->transactionTypeValue = $transactionTypeValue;
        $this->reduceAccountId = $reduceAccountId;
        $this->increaseAccountId = $increaseAccountId;
        $this->contents = $contents;
    }


}
