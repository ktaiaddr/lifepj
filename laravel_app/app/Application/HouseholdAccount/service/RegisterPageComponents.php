<?php

namespace App\Application\HouseholdAccount\service;

use App\Domain\HouseholdAccount\Model\Transaction\TransactionType;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionTypeDefine;

class RegisterPageComponents
{
    /** @var TransactionTypeDefine[]  */
    public array $transactionTypeDefinitions;

    public function __construct(){

        $this->transactionTypeDefinitions = TransactionType::getTypeDefines();
    }
}
