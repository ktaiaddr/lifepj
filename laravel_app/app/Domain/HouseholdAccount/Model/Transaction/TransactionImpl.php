<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Balancers;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

class TransactionImpl implements Transaction
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
     * @var Balancers
     */
    private Balancers $updateBalances;

    /**
     * @param string $transactionId
     * @param \DateTime $date
     * @param TransactionAmount $transactionAmount
     * @param Balancers $updateBalances
     */
    public function __construct(string $transactionId, \DateTime $date, TransactionAmount $transactionAmount, Balancers $updateBalances)
    {
        $this->transactionId = $transactionId;
        $this->date = $date;
        $this->transactionAmount = $transactionAmount;
        $this->updateBalances = $updateBalances;
    }


    /**
     * @inheritDoc
     */
    public function updateBalance()
    {
        $this->updateBalances->updateBalance($this->transactionAmount);
    }

    public function notify(NotificationTransaction $modelBuilder){

        $modelBuilder->transactionId($this->transactionId);
        $modelBuilder->transactionDate($this->date);
        $modelBuilder->transactionClass(Transaction::CLASSIFICATION_ACCOUNT_TRANSFER);

        $this->transactionAmount->notify($modelBuilder);

        $this->updateBalances->notify($this->transactionId,$modelBuilder);


    }
}
