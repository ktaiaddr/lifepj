<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Deposits;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

/**
 * 入金
 */
class MoneyReceived implements Transaction
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
     * 支払い
     * @var Deposits
     */
    private Deposits $deposits;

    /**
     * @param string $transactionId
     * @param \DateTime $date
     * @param TransactionAmount $transactionAmount
     * @param Deposits $deposits
     */
    public function __construct(string $transactionId, \DateTime $date, TransactionAmount $transactionAmount, Deposits $deposits)
    {
        if(! $deposits->isBank())
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
}
