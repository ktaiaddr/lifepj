<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

class TransactionTypeDefine
{

    public int $typeValue;
    public string $typeLabel;

    /**
     * @param int $typeValue
     * @param string $typeLabel
     */
    public function __construct(int $typeValue, string $typeLabel)
    {
        $this->typeValue = $typeValue;
        $this->typeLabel = $typeLabel;
    }
}
