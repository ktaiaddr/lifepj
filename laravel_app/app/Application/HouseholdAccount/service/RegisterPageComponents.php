<?php

namespace App\Application\HouseholdAccount\service;

use App\Application\HouseholdAccount\QueryModel\AccountBalanceSelectModel;
use App\Domain\HouseholdAccount\Model\Account\Account;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionType;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionTypeDefine;

class RegisterPageComponents
{
    /** @var TransactionTypeDefine[]  */
    public array $transactionTypeDefinitions;

    /** @var Account[] */
    public array $accounts;

    /**
     * @param TransactionTypeDefine[] $transactionTypeDefinitions
     * @param AccountBalanceSelectModel[] $accounts
     */
    public function __construct(array $transactionTypeDefinitions, array $accounts)
    {
        $this->transactionTypeDefinitions = $transactionTypeDefinitions;
        $this->accounts = $accounts;
    }


}
