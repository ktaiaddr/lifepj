<?php

namespace App\infra\HouseholdAccount\inmemoryQuery;


use App\Domain\HouseholdAccount\Model\Account\Account;
use App\Domain\HouseholdAccount\Model\Account\AccountType;

class InmemoryAccountBalanceQuery implements \App\Application\HouseholdAccount\query\AccountBalanceQuery
{

    /**
     * @var Account[]
     */
    private array $accountBalanceCollection = [];

    /**
     * @param Account[] $accountBalanceCollection
     */
    public function __construct()
    {
        $this->add(new Account(1,200,new AccountType(AccountType::TYPE_BANK)));
        $this->add(new Account(2,100,new AccountType(AccountType::TYPE_BANK)));
    }


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
