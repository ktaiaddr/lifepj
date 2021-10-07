<?php

namespace App\infra\HouseholdAccount\InmemoryRepository;

use App\Domain\HouseholdAccount\Model\Transaction\Transaction;

class TransactionInmemoryRepository implements \App\Domain\HouseholdAccount\repository\TransactionRepository
{

    /**
     * @var Transaction[]
     */
    private array $transactions = [];

    function save(Transaction $transaction)
    {
        // TODO: Implement save() method.
    }
}
