<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\AccountBalance;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;

class TransactionAmount
{
    private int $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        //金額は1円以上の数値である必要あり
        if( $value < 1 ){
            throw new \InvalidArgumentException("取引金額は1円以上である必要があります");
        }

        $this->value = $value;
    }

    public function notify(NotificationTransaction $modelBuilder){
        $modelBuilder->transactionAmount($this->value);
    }
    /**
     * @param int $accountBalanceValue
     * @return int
     */
    public function increaseBalance(int $accountBalanceValue):int{
        return ($accountBalanceValue + $this->value);
    }

    /**
     * @param int $accountBalanceValue
     * @return int
     * @throws \Exception
     */
    public function reduceBalance(int $accountBalanceValue):int{

        $afterBalance = ($accountBalanceValue - $this->value);

        //TODO 例外処理は別途作り変えが必要
//        if($afterBalance < 0){
//            throw new \Exception("残高不足で処理できません");
//        }

        return $afterBalance;
    }

}
