<?php

namespace App\Domain\HouseholdAccount\repository;

use App\Domain\HouseholdAccount\Model\Account\Account;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;

interface TransactionRepository
{

    /**
     * @param string $transactionId
     * @param \DateTime $transactionDate
     * @param String $transactionContents
     * @param Transaction $transaction
     * @param Account[] $accounts
     * @param int $user_id
     * @return bool
     */
    function save(string $transactionId, \DateTime $transactionDate, String $transactionContents,Transaction $transaction,array $accounts,int $user_id):bool;
}
