<?php

namespace App\Application\HouseholdAccount\query;

use App\Application\HouseholdAccount\QueryModel\TransactionViewModel;

interface TransactionViewQuery
{
    /**
     * @param string $userId
     * @return TransactionViewModel[]
     */
    public function find(string $userId):array;
}
