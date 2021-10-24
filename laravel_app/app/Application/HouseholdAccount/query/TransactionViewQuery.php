<?php

namespace App\Application\HouseholdAccount\query;

use App\Application\HouseholdAccount\QueryModel\TransactionViewModel;
use App\Domain\HouseholdAccount\Model\Transaction\SearchCommand;

interface TransactionViewQuery
{
    /**
     * @param string $userId
     * @return TransactionViewModel[]
     */
    public function find(SearchCommand $searchCommand,string $userId):array;
}
