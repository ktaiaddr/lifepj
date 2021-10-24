<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

class SearchCommand
{
    /**
     * @var ?int
     */
    public ?int $transactionTypeVal;
    /**
     * @var ?int
     */
    public ?int $accountId;
    /**
     * @var string|null
     */
    public ?string $viewMonth;

    /**
     * @param int|null $transactionTypeVal
     * @param int|null $accountId
     */
    public function __construct(?int $transactionTypeVal, ?int $accountId, ?string $viewMonth)
    {
        $this->transactionTypeVal = $transactionTypeVal;
        $this->accountId = $accountId;
        $this->viewMonth = $viewMonth;
    }


}
