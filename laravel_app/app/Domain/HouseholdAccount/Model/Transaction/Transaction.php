<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Balancers;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

class Transaction
{

    /**
     * 取引ID Str::orderedUuid()で発行
     * @var string
     */
    private string $transactionId;
    /**
     * @var \DateTime
     */
    private \DateTime $date;
    /**
     * 金額
     * @var TransactionAmount
     */
    private TransactionAmount $transactionAmount;

    /**
     * @var string 取引内容
     */
    private string $transactionContents;

    /**
     * @param string $transactionId
     * @param \DateTime $date
     * @param TransactionAmount $transactionAmount
     * @param string $transactionContents
//     * @param Balancers $updateBalances
     */
    public function __construct(string $transactionId
        , \DateTime $date
        , TransactionAmount $transactionAmount
        , string $transactionContents
    )
    {
        $this->transactionId = $transactionId;
        $this->date = $date;
        $this->transactionAmount = $transactionAmount;
        $this->transactionContents = $transactionContents;
    }



    public function notify(NotificationTransaction $modelBuilder){

        $modelBuilder->transactionId($this->transactionId);
        $modelBuilder->transactionDate($this->date);
        $modelBuilder->transactionContents($this->transactionContents);

        $this->transactionAmount->notify($modelBuilder);

//        $this->updateBalances->notify($this->transactionId,$modelBuilder);
    }
}
