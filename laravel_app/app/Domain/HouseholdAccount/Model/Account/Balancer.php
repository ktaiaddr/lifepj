<?php

namespace App\Domain\HouseholdAccount\Model\Account;

use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionAmount;

interface Balancer
{
    /**
     * 残高を更新
     * @param TransactionAmount $transactionAmount
     */
    public function exec(TransactionAmount $transactionAmount);

    /**
     * 処理対象が銀行口座が対象であること
     * @return bool
     */
    public function targetIsBank();

    /**
     * 処理対象が手元現金であること
     * @return bool
     */
    public function targetIsHandMoney();

}
