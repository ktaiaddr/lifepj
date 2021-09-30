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

        //口座間転送
        if($transactionType->isAccountTransfer()) $this->values = [$reducer,$increaser];
        //現金加算
        if($transactionType->isCashAddition()) $this->values = [$increaser];
        //現金払い
        if($transactionType->isCashPayment()) $this->values = [$reducer];
        //引き落とし
        if($transactionType->isDirectDevit()) $this->values = [$reducer];
        //入金
        if($transactionType->isMoneyReceived()) $this->values = [$increaser];
        //現金引き出し
        if($transactionType->isWithdrawalDeposit()) $this->values = [$reducer,$increaser];
    }

    /**
     * @param TransactionAmount $transactionAmount
     */
    public function updateBalance(TransactionAmount $transactionAmount){
        foreach($this->values as $updateBalance){
            $updateBalance->updateBalance($transactionAmount);
        }
    }

    /**
     * @param string $transactionId
     * @param NotificationTransaction $modelBuilder
     */
    public function notify(string $transactionId, NotificationTransaction $modelBuilder){
        foreach($this->values as $updateBalance){
            $updateBalance->notify($transactionId,$modelBuilder);
        }
    }
}
