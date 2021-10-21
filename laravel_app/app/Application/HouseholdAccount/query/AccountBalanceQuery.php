<?php

namespace App\Application\HouseholdAccount\query;


use App\Application\HouseholdAccount\QueryModel\AccountBalanceSelectModel;
use App\Domain\HouseholdAccount\Model\Account\Account;

interface AccountBalanceQuery
{

    public function find(int $accountId,int $user_id,int $increase_reduce_type): Account|null;

    /**
     * @param int $user_id
     * @return AccountBalanceSelectModel[]
     */
    public function findByUser(int $user_id): array;
}
