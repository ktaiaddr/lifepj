<?php

namespace App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals;

use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionType;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

class Balancers
{
    /**
     * @var Balancer[]
     */
    private array $values = [];

    /**
     * @param TransactionType $transactionType
     * @param Reducer|null $reducer
     * @param Increaser|null $increaser
     * @throws \Exception
     */
    public function __construct(TransactionType $transactionType, ?Reducer $reducer, ?Increaser $increaser)
    {
        if(! $transactionType->inspectionBalancers($reducer,$increaser))
            throw new \Exception($transactionType->getLabel().'アカウントエラー');

        $this->values = $transactionType->takeBalancers($reducer,$increaser);

    }

    /**
     * @param TransactionAmount $transactionAmount
     * @return Account[]
     */
    public function updateBalance(TransactionAmount $transactionAmount){

        $fn = fn(Balancer $updateBalance):Account=>$updateBalance->updateBalance($transactionAmount);
        return array_map($fn,$this->values);

    }

}
