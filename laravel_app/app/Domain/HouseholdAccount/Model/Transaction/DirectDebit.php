<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;


use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Withdrawals;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

/**
 * 口座引落し
 */
class DirectDebit implements Transaction
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
     * @var Withdrawals
     */
    private Withdrawals $withdrawals;

    /**
     * @param string $transactionId
     * @param \DateTime $date
     * @param TransactionAmount $transactionAmount
     * @param Withdrawals $withdrawals
     */
    public function __construct(string $transactionId, \DateTime $date, TransactionAmount $transactionAmount, Withdrawals $withdrawals)
    {
        if(! $withdrawals->isBank())
            throw new \Exception("移動元の口座種別が不正です");

        $this->transactionId = $transactionId;
        $this->date = $date;
        $this->transactionAmount = $transactionAmount;
        $this->withdrawals = $withdrawals;
    }

    /**
     * @inheritDoc
     */
    public function updateBalance()
    {
        $this->withdrawals->updateBalance($this->transactionAmount);
    }
}
