<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Deposits;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Withdrawals;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

/**
 * 引き出し
 */
class WithdrawalDeposit implements Transaction
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
     * 入金
     * @var Deposits
     */
    private Deposits $deposits;
    /**
     * @param string $transactionId
     * @param \DateTime $date
     * @param TransactionAmount $transactionAmount
     * @param Withdrawals $withdrawals
     */
    public function __construct(string $transactionId, \DateTime $date, TransactionAmount $transactionAmount, Withdrawals $withdrawals, Deposits $deposits)
    {
        if(! $withdrawals->isBank())
            throw new \Exception("移動元の口座種別が不正です");

        if(! $deposits->isHandMoney())
            throw new \Exception("移動先元の口座種別が不正です");

        $this->transactionId = $transactionId;
        $this->date = $date;
        $this->transactionAmount = $transactionAmount;
        $this->withdrawals = $withdrawals;
        $this->deposits = $deposits;
    }

    /**
     * @inheritDoc
     */
    public function updateBalance()
    {
        $this->withdrawals->updateBalance($this->transactionAmount);
    }
}
