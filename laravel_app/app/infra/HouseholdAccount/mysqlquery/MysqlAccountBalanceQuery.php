<?php

namespace App\infra\HouseholdAccount\mysqlquery;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Account;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\AccountType;
use App\Models\HouseholdAccount\AccountBalance;

class MysqlAccountBalanceQuery implements \App\Application\HouseholdAccount\query\AccountBalanceQuery
{

    public function find(int $accountId): Account|null
    {
        $eloquentAccountBalance = AccountBalance::where('account_id',$accountId)->orderBy('created_at', 'desc')->first();

        if(! $eloquentAccountBalance)
            return new Account($accountId,10000,new AccountType(AccountType::TYPE_BANK));

        return new Account((int)$eloquentAccountBalance->account_id,$eloquentAccountBalance->balance, new AccountType(AccountType::TYPE_BANK));
    }
}
