<?php

namespace App\Domain\HouseholdAccount\repository;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Account;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;

interface TransactionRepository
{

    /**
     * @param string $transactionId
     * @param \DateTime $transactionDate
     * @param String $transactionContents
     * @param Transaction $transaction
     * @param Account[] $accounts
     * @return bool
     */
    function save(string $transactionId, \DateTime $transactionDate, String $transactionContents,Transaction $transaction,array $accounts):bool;
}
