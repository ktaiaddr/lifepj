<?php

namespace App\infra\HouseholdAccount\inmemoryQuery;


use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Account;
use JetBrains\PhpStorm\Pure;

class AccountInmemoryQuery implements \App\Application\HouseholdAccount\query\AccountQuery
{

    /**
     * @var Account[]
     */
    private array $accountBalanceCollection = [];

    public function add(Account $accountBalance){
        $this->accountBalanceCollection[] = $accountBalance;
    }

    /**
     * @param int $accountId
     * @return Account|null
     */
    public function find(int $accountId ): Account|null
    {
        foreach($this->accountBalanceCollection as $index => $accountBalance){
            if($accountBalance->getAccountId() == $accountId)
                return $accountBalance;
        }
        return null;
    }
}
