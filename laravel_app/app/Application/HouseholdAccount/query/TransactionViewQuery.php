<?php

namespace App\Application\HouseholdAccount\query;

use App\Application\HouseholdAccount\QueryModel\TransactionViewModel;

interface TransactionViewQuery
{
    /**
     * @param int $userId
     * @return TransactionViewModel[]
     */
    public function find(int $userId):array;
}
