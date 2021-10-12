<?php

namespace App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals;

use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

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

    /**
     * 永続化のための内部データ通知処理
     * @param string $transactionId
     * @param NotificationTransaction $modelBuilder
     */
    public function notify(string $transactionId, NotificationTransaction $modelBuilder);
}
