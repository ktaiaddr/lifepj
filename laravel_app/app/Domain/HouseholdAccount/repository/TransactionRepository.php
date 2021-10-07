<?php

namespace App\Domain\HouseholdAccount\repository;

use App\Domain\HouseholdAccount\Model\Transaction\Transaction;

interface TransactionRepository
{

    function save(Transaction $transaction);
}
