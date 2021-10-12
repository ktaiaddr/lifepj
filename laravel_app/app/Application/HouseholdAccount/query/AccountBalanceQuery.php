<?php

namespace App\Application\HouseholdAccount\query;


use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Account;

interface AccountBalanceQuery
{

    public function find(int $accountId): Account|null;
}
