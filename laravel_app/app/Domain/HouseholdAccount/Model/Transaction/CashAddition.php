<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Deposits;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

/**
 * 現金加算
 */
class CashAddition implements Transaction
{
    /**
     * 取引ID
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
     * 入金
     * @var Deposits
     */
    private Deposits $deposits;

    /**
     * @param string $transactionId
     * @param \DateTime $date
     * @param TransactionAmount $transactionAmount
     * @param Deposits $deposits
     * @throws \Exception
     */
    public function __construct(string $transactionId, \DateTime $date, TransactionAmount $transactionAmount, Deposits $deposits)
    {
        if(! $deposits->isHandMoney())
            throw new \Exception("移動先の口座種別が不正です");

        $this->transactionId = $transactionId;
        $this->date = $date;
        $this->transactionAmount = $transactionAmount;
        $this->deposits = $deposits;
    }

    /**
     * @inheritDoc
     */
    public function updateBalance()
    {
        $this->deposits->updateBalance($this->transactionAmount);
    }

    public function notify(NotificationTransaction $modelBuilder){

        $modelBuilder->transactionId($this->transactionId);
        $modelBuilder->transactionDate($this->date);
        $modelBuilder->transactionClass(Transaction::CLASSIFICATION_CASH_ADDITION);

        $this->transactionAmount->notify($modelBuilder);
        $this->deposits->notify($this->transactionId,$modelBuilder);

    }
}
