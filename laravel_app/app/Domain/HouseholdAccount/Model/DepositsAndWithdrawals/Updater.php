<?php

namespace App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals;

use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

interface Updater
{

    /**
     * 入金
     */
    const CLASSIFICATION_DEPOSIT = 1;
    /**
     * 出金
     */
    const CLASSIFICATION_WITHDRAWAL = 2;

    /**
     * @param TransactionAmount $transactionAmount
     */
    public function updateBalance(TransactionAmount $transactionAmount);
}
